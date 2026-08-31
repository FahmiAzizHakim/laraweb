<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = $this->route('id'); // null on create

        return [
            'banner_title' => [
                'required', 'string', 'max:191',
                Rule::unique('banners', 'banner_title')->ignore($id),
            ],
            // Image required on create; on edit the existing image is kept when omitted.
            'banner_img'   => [$id ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
            'banner_text'  => 'nullable|string|max:2000',
            'is_active'    => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function messages(): array
    {
        return [
            'banner_img.required' => 'Please upload a banner image.',
        ];
    }
}
