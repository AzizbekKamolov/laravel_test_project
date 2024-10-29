<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProviderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->routeIs('providers.update'))
            return [
                "name" => "required|unique:providers,name," . $this->route()->parameter('id'),
                "category_id" => [
                    "required",
                    Rule::exists('categories', 'id')->whereNull('category_id')
                ]
            ];
        return [
            "name" => "required|unique:providers,name",
            "category_id" => [
                "required",
                Rule::exists('categories', 'id')->whereNull('category_id')
            ]
        ];
    }
}
