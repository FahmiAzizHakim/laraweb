<?php

namespace App\Http\Requests\Masterdata;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $id  = $this->route('id');
        $wid = admin_website_id();

        return [
            'category_name' => 'required|string|max:191',
            'category_code' => [
                'required', 'string', 'max:100',
                Rule::unique('categories', 'category_code')
                    ->where(fn ($q) => $q->where('website_id', $wid))
                    ->ignore($id),
            ],
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(fn ($q) => $q->where('website_id', $wid)),
                'different:__self',
            ],
            'remark'    => 'nullable|string|max:2000',
            'is_active' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            // helper value so a category can't be its own parent
            '__self'    => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'parent_id.different' => 'A category cannot be its own parent.',
        ];
    }
}
