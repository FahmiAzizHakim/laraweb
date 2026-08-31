<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAttachment extends Model
{
    protected $table = 'transaction_attachments';

    protected $fillable = [
        'transaction_id',
        'type',
        'file_path',
        'original_name',
        'mime',
        'size',
        'note',
        'uploaded_by',
    ];

    /** Attachment type slugs and their human labels. */
    const TYPES = [
        'PAYMENT'  => 'Payment Proof',
        'PACKAGE'  => 'Package Photo',
        'DELIVERY' => 'Delivery Proof',
        'OTHER'    => 'Other',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
