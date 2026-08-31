<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'about_title'    => 'required|string|max:191',
            'about_subtitle' => 'nullable|string|max:191',
            'about_content'  => 'nullable|string|max:20000',
            // Image is optional on both create and edit; on edit the existing
            // one is kept when no new file is uploaded.
            'about_image'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
            'order'          => 'nullable|integer|min:0|max:9999',
            'is_active'      => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function attributes(): array
    {
        return [
            'about_title'    => 'title',
            'about_subtitle' => 'subtitle',
            'about_content'  => 'content',
            'about_image'    => 'image',
        ];
    }
}
