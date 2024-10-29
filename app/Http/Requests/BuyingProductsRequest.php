<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyingProductsRequest extends FormRequest
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
            "batches" => "required|array",
            "batches.*.product_id" => "required|exists:products,id",
            "batches.*.provider_id" => "required|exists:providers,id",
            "batches.*.quantity" => "required|int",
            "batches.*.amount" => "required|int",
        ];
    }
}
