<?php

namespace App\Http\Requests\Website;

use App\Models\WebStyle;
use Illuminate\Foundation\Http\FormRequest;

class WebStyleRequest extends FormRequest
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
     * For the "Colors" group the style_type is switchable
     * (color | gradient | text) and the value is validated against the
     * chosen type. Other groups keep their fixed type (value only).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $style       = WebStyle::find($this->route('id'));
        $group       = $style->style_group ?? null;
        $currentType = $style->style_type ?? 'text';

        $rules = [];

        if ($group === 'Colors') {
            $rules['style_type'] = 'required|in:color,gradient,text';
            $type = $this->input('style_type', $currentType);
        } else {
            $type = $currentType;
        }

        $valueRule = ['required', 'string', 'max:1000'];

        switch ($type) {
            case 'color':
                $valueRule[] = 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/';
                break;
            case 'size':
                $valueRule[] = 'regex:/^-?\d*\.?\d+(px|%|vh|vw|em|rem|pt|vmin|vmax)$/i';
                break;
            case 'gradient':
                $valueRule[] = 'regex:/gradient\(/i';
                break;
            // 'text' accepts any string
        }

        $rules['style_value'] = $valueRule;

        return $rules;
    }

    public function messages(): array
    {
        return [
            'style_value.regex' => 'The value format is not valid for this style type.',
        ];
    }
}
