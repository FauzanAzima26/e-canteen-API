<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuppliersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return auth()->check();

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeId = $this->route('supplier');
        return [
            'code' => 'required|digits:3|unique:suppliers,code,' . $routeId . ',uuid',
            'name' => 'required|string|max:255|min:3',
            'address' => 'required|string|max:255|min:3',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:suppliers,email,' . $routeId . ',uuid',
        ];
    }
}
