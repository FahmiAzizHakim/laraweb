<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentRequest extends FormRequest
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
            'content_title'    => [
                'required', 'string', 'max:191',
                Rule::unique('contents', 'content_title')->ignore($id),
            ],
            'content_subtitle' => 'nullable|string|max:191',
            'description'      => 'nullable|string|max:2000',
            'content'          => 'nullable|string',
            // Image required on create; kept on edit when omitted.
            'media'            => [$id ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
            'reference'        => 'nullable|string|max:191',
            'additional_url'   => 'nullable|url|max:255',
            'is_active'        => 'required|boolean',
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
            'media.required' => 'Please upload a cover image.',
        ];
    }
}
