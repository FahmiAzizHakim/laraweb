<?php

namespace App\Http\Requests\Masterdata;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'service_name'        => 'required|string|max:191',
            'service_title'       => 'nullable|string|max:191',
            'service_subtitle'    => 'nullable|string|max:191',
            'service_description' => 'nullable|string|max:5000',
            'service_image'       => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg|max:4096',
            'service_icon'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg|max:2048',
            'remark'              => 'nullable|string|max:2000',
            'is_active'           => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
