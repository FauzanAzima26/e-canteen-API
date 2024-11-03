<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255|min:3',
            'price' => 'required|integer|numeric',
            'stock' => 'required|numeric',
            'description' => 'nullable|string|max:255|min:3',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:2048',
        ];
    }
}
