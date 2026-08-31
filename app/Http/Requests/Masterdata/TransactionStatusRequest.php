<?php

namespace App\Http\Requests\Masterdata;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required', 'string', 'max:60',
                // Must be a status code (codes.parentcode = 'STS').
                Rule::exists('codes', 'code')->where(fn ($q) => $q->where('parentcode', 'STS')),
            ],
            'description' => 'nullable|string|max:2000',
        ];
    }
}
