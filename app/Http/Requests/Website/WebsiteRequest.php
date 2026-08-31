<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'id'           => 'required|exists:websites,id',
            'web_name'     => 'required|string|max:191',
            'company_name' => 'nullable|string|max:191',
            'phone_number'   => 'nullable|string|max:50',
            'whatsapp_no'    => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:191',
            'facebook_link'  => 'nullable|url|max:255',
            'twitter_link'   => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'linkedin_link'  => 'nullable|url|max:255',
            'address'        => 'nullable|string|max:1000',
            'location'     => ['nullable', 'regex:/^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/'],
            'logo'         => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg,ico|max:4096',
            'logo_white'   => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg,ico|max:4096',
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
            'location.regex' => 'The location must be in "latitude,longitude" format.',
        ];
    }
}
