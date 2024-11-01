<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeOrderRequest extends FormRequest
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
            'client_id' => "required|exists:clients,id",
            'batch' => "required|exists:batches,batch",
            'products' => "required|array",
            'products.*.product_id' => "required|exists:products,id",
            'products.*.quantity' => "required|int",
            'products.*.amount' => "required|int",
        ];
    }
}
