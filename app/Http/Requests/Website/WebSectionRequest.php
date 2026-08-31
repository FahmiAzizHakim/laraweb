<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class WebSectionRequest extends FormRequest
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
            'section_name' => 'required|string|max:100',
            'nav_label'    => 'nullable|string|max:60',
            // The in-page anchor: letters, digits and dashes, no "#".
            'anchor'       => 'nullable|string|max:60|regex:/^[A-Za-z0-9_-]+$/',
            'order'        => 'required|integer|min:0|max:9999',
            'show_in_page' => 'required|boolean',
            'show_in_nav'  => 'required|boolean',
            'remark'       => 'nullable|string|max:2000',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'show_in_page' => $this->boolean('show_in_page'),
            'show_in_nav'  => $this->boolean('show_in_nav'),
            // Tolerate a pasted "#products".
            'anchor'       => ltrim((string) $this->input('anchor'), '#') ?: null,
        ]);
    }

    public function attributes(): array
    {
        return [
            'section_name' => 'name',
            'nav_label'    => 'menu label',
            'show_in_page' => 'show on page',
            'show_in_nav'  => 'show in menu',
        ];
    }

    public function messages(): array
    {
        return [
            'anchor.regex' => 'The anchor may only contain letters, numbers, dashes and underscores.',
        ];
    }
}
