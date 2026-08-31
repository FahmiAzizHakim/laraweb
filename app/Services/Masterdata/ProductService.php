<?php

namespace App\Services\Masterdata;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductSpecification;
use App\Models\PackageDetail;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Products belonging to a website (via their service).
     */
    public function getList($websiteId = null)
    {
        return Product::with(['service', 'categories', 'images', 'variants', 'specifications'])
            ->when($websiteId, fn ($q) => $q->whereHas('service', fn ($s) => $s->where('website_id', $websiteId)))
            ->orderByDesc('id')
            ->get();
    }

    public function getRow($id, $websiteId = null)
    {
        return Product::with(['service', 'categories', 'images', 'variants', 'specifications'])
            ->when($websiteId, fn ($q) => $q->whereHas('service', fn ($s) => $s->where('website_id', $websiteId)))
            ->where('id', $id)
            ->first();
    }

    /**
     * @param array $images each: ['image_name' => ..., 'image_url' => ...]
     */
    public function create($params, array $categoryIds = [], array $images = [], array $variants = [], array $specifications = [])
    {
        DB::beginTransaction();

        $product = Product::create($params);
        if (!$product) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create product");
        }

        $product->categories()->sync($categoryIds);
        $this->insertImages($product->id, $images, 0);
        $this->syncVariants($product->id, $variants);
        $this->syncSpecifications($product->id, $specifications);

        DB::commit();
        return array("status" => "success", "message" => "Product created successfully", "data" => $product);
    }

    public function update($id, $params, array $categoryIds = [], array $newImages = [], array $removeImageIds = [], array $variants = [], array $specifications = [])
    {
        DB::beginTransaction();

        $product = Product::find($id);
        if (!$product) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Product not found");
        }

        $product->update($params);
        $product->categories()->sync($categoryIds);

        if (!empty($removeImageIds)) {
            $toRemove = ProductImage::where('product_id', $id)->whereIn('id', $removeImageIds)->get();
            $this->deleteImageFiles($toRemove);
            ProductImage::where('product_id', $id)->whereIn('id', $removeImageIds)->delete();
        }

        $nextOrder = (int) ProductImage::where('product_id', $id)->max('order') + 1;
        $this->insertImages($id, $newImages, $nextOrder);

        $this->syncVariants($id, $variants);
        $this->syncSpecifications($id, $specifications);

        DB::commit();
        return array("status" => "success", "message" => "Product updated successfully", "data" => $product);
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return array("status" => "failed", "message" => "Product not found");
        }

        DB::beginTransaction();
        $this->deleteImageFiles($product->images()->get());
        $product->images()->delete();
        $product->variants()->delete();
        $product->specifications()->delete();
        $product->categories()->detach();
        // Drop package lines pointing at this product so no package keeps a dangling row.
        PackageDetail::where('product_id', $id)->delete();
        $product->delete();
        DB::commit();

        return array("status" => "success", "message" => "Product deleted successfully");
    }

    /**
     * Create / update / delete variants from submitted rows.
     * Each row: [id?, _remove?, variant_name, variant_price, variant_description,
     *            variant_weight, variant_width, variant_length, variant_height, is_active].
     */
    private function syncVariants($productId, array $variants): void
    {
        foreach ($variants as $row) {
            $id     = $row['id'] ?? null;
            $remove = !empty($row['_remove']);
            $name   = trim($row['variant_name'] ?? '');

            if ($id) {
                $variant = ProductVariant::where('product_id', $productId)->where('id', $id)->first();
                if (!$variant) {
                    continue;
                }
                if ($remove) {
                    $variant->delete();
                    continue;
                }
                $variant->update($this->variantData($row));
            } else {
                // Skip blank / removed new rows.
                if ($remove || $name === '') {
                    continue;
                }
                $data = $this->variantData($row);
                $data['product_id'] = $productId;
                ProductVariant::create($data);
            }
        }
    }

    private function variantData(array $row): array
    {
        $nullableInt = fn ($v) => ($v === null || $v === '') ? null : (int) $v;

        return [
            'variant_name'        => $row['variant_name'] ?? '',
            'variant_price'       => ($row['variant_price'] ?? '') === '' ? null : $row['variant_price'],
            'variant_description' => $row['variant_description'] ?? null,
            'variant_weight'      => $nullableInt($row['variant_weight'] ?? null),
            'variant_width'       => $nullableInt($row['variant_width'] ?? null),
            'variant_length'      => $nullableInt($row['variant_length'] ?? null),
            'variant_height'      => $nullableInt($row['variant_height'] ?? null),
            'is_active'           => !empty($row['is_active']) ? 1 : 0,
        ];
    }

    /**
     * Create / update / delete specification rows from submitted rows.
     * Each row: [id?, _remove?, attribute, value]. A row is only kept when both
     * attribute and value are filled in.
     */
    private function syncSpecifications($productId, array $specifications): void
    {
        foreach ($specifications as $row) {
            $id        = $row['id'] ?? null;
            $remove    = !empty($row['_remove']);
            $attribute = trim($row['attribute'] ?? '');
            $value     = trim($row['value'] ?? '');

            if ($id) {
                $spec = ProductSpecification::where('product_id', $productId)->where('id', $id)->first();
                if (!$spec) {
                    continue;
                }
                if ($remove || $attribute === '' || $value === '') {
                    $spec->delete();
                    continue;
                }
                $spec->update(['attribute' => $attribute, 'value' => $value]);
            } else {
                // Skip blank / removed new rows.
                if ($remove || $attribute === '' || $value === '') {
                    continue;
                }
                ProductSpecification::create([
                    'product_id' => $productId,
                    'attribute'  => $attribute,
                    'value'      => $value,
                ]);
            }
        }
    }

    /**
     * Unlink the physical files for the given image rows.
     * Restricted to the uploads/ folder so shared/seeded assets are never touched.
     */
    private function deleteImageFiles($images): void
    {
        foreach ($images as $img) {
            $url = is_array($img) ? ($img['image_url'] ?? null) : $img->image_url;

            if ($url && str_starts_with($url, 'uploads/')) {
                $path = public_path($url);
                if (is_file($path)) {
                    @unlink($path);
                }
            }
        }
    }

    private function insertImages($productId, array $images, int $startOrder): void
    {
        foreach (array_values($images) as $i => $img) {
            ProductImage::create([
                'product_id' => $productId,
                'image_name' => $img['image_name'] ?? 'image',
                'image_url'  => $img['image_url'],
                'order'      => $startOrder + $i,
                'is_active'  => true,
            ]);
        }
    }
}
