<?php

namespace App\Services\Masterdata;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionPackage;
use App\Models\TransactionBenefit;
use App\Models\TransactionAddress;
use App\Models\TransactionCharge;
use App\Models\TransactionHist;
use App\Models\Code;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    /* =========================
     * STORE a full order atomically (header + details + address + charges +
     * packages + benefits), generate a receipt number & token, and seed the
     * first history row.
     *
     * @param array $header   transactions columns (website_id, customer_*, money, status ...)
     * @param array $items    list of transaction_details rows
     * @param array|null $address  transaction_addresses row (without transaction_id)
     * @param array $charges  list of transaction_charges rows
     * @param array $packages list of transaction_packages rows
     * @param array $benefits list of transaction_benefits rows
     * ========================= */
    public function store(array $header, array $items, ?array $address = null, array $charges = [], array $packages = [], array $benefits = [])
    {
        DB::beginTransaction();
        try {
            $header['status']        = $header['status'] ?? 'STSPY';
            $header['receipt_token'] = $header['receipt_token'] ?? Str::upper(Str::random(40));

            $trx = Transaction::create($header);

            // Human-readable receipt number derived from the id, unique per website.
            if (empty($trx->receipt_no)) {
                $datePart = optional($trx->transaction_date)->format('Ymd') ?? date('Ymd');
                $trx->receipt_no = 'INV-' . $datePart . '-' . str_pad($trx->id, 6, '0', STR_PAD_LEFT);
                $trx->save();
            }

            foreach ($items as $item) {
                $item['transaction_id'] = $trx->id;
                TransactionDetail::create($item);
            }

            if ($address) {
                $address['transaction_id'] = $trx->id;
                TransactionAddress::create($address);
            }

            foreach ($charges as $charge) {
                $charge['transaction_id'] = $trx->id;
                TransactionCharge::create($charge);
            }

            foreach ($packages as $package) {
                $package['transaction_id'] = $trx->id;
                TransactionPackage::create($package);
            }

            foreach ($benefits as $benefit) {
                $benefit['transaction_id'] = $trx->id;
                TransactionBenefit::create($benefit);
            }

            // Seed the initial status history.
            $code = Code::where('parentcode', 'STS')->where('code', $trx->status)->first();
            TransactionHist::create([
                'transaction_id' => $trx->id,
                'status_from'    => null,
                'status_code'    => $trx->status,
                'status_name'    => $code->name ?? $trx->status,
                'description'    => $header['hist_description'] ?? 'Transaction created',
                'created_by'     => $header['created_by'] ?? null,
            ]);

            DB::commit();
            return array("status" => "success", "message" => "Transaction created", "data" => $trx);
        } catch (\Throwable $e) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create transaction: " . $e->getMessage());
        }
    }

    /* =========================
     * GET a single transaction by its public receipt token (scoped to website).
     * ========================= */
    public function getByToken($token, $websiteId = null)
    {
        return Transaction::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->with(['details', 'packages', 'benefits', 'address', 'chargeItems', 'statusCode', 'paymentTypeCode', 'attachments'])
            ->where('receipt_token', $token)
            ->first();
    }

    /* =========================
     * GET LIST (scoped to website when provided)
     * ========================= */
    public function getList($websiteId = null)
    {
        return Transaction::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->with('statusCode')
            ->orderByDesc('id')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW with all related data (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return Transaction::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->with(['details', 'packages', 'benefits', 'address', 'chargeItems', 'histories', 'statusCode', 'paymentTypeCode'])
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * STATUS OPTIONS (codes where parentcode = STS)
     * ========================= */
    public function statusOptions()
    {
        return Code::children('STS')->get();
    }

    /* =========================
     * UPDATE STATUS + write history row
     * ========================= */
    public function updateStatus($id, $statusCode, $description = null, $changedBy = null)
    {
        DB::beginTransaction();

        $trx = Transaction::find($id);
        if (!$trx) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Transaction not found");
        }

        // Validate the target status belongs to the STS group.
        $code = Code::where('parentcode', 'STS')->where('code', $statusCode)->first();
        if (!$code) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Invalid status");
        }

        $from = $trx->status;

        $trx->update([
            'status'     => $code->code,
            'updated_by' => $changedBy,
        ]);

        TransactionHist::create([
            'transaction_id' => $trx->id,
            'status_from'    => $from,
            'status_code'    => $code->code,
            'status_name'    => $code->name,
            'description'    => $description,
            'created_by'     => $changedBy,
        ]);

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Status updated to " . $code->name,
            "data"    => $trx,
        );
    }
}
