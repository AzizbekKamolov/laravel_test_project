<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        return [
            "name" => [
                'required',
                Rule::unique('categories', 'name')
                    ->where(function ($query) {
                        return $query->where('provider_id', $this->request->get('category_id'))
                            ->orWhere('provider_id', $this->request->get('provider_id'));
                    })

            ],
            "category_id" => "required_without:provider_id|exists:categories,id",
            "provider_id" => "required_without:category_id|exists:providers,id",
        ];
    }
}
