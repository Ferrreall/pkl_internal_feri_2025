<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // GANTI DARI false KE true
        return true; 
    }

    public function rules(): array
    {
        return [
            // Pastikan rules-nya sudah benar di sini
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            // ... dst
        ];
    }
}