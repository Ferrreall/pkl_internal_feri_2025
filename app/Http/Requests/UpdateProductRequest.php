<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Tentukan apakah user punya izin untuk melakukan request ini.
     */
    public function authorize(): bool
    {
        // Pastikan ini TRUE agar request tidak ditolak (403 Forbidden)
        return true; 
    }

    /**
     * Aturan validasi untuk Update Produk.
     */
    public function rules(): array
    {
        return [
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            
            // Aturan sakti: discount_price boleh kosong, tapi kalau diisi harus angka 
            // dan harus lebih kecil (lt = less than) dari kolom price.
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            
            'stock'          => 'required|integer|min:0',
            'weight'         => 'required|integer|min:1',
            
            // Checkbox biasanya mengirimkan nilai '1' atau tidak terkirim sama sekali (null)
            'is_active'      => 'sometimes|boolean',
            'is_featured'    => 'sometimes|boolean',
            
            // Validasi untuk file gambar baru jika diupload
            'images.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            
            // Untuk gambar lama yang mau dihapus atau dijadikan primary
            'delete_images'  => 'nullable|array',
            'delete_images.*'=> 'exists:product_images,id',
            'primary_image'  => 'nullable|exists:product_images,id',
        ];
    }

    /**
     * Pesan error kustom agar lebih user-friendly.
     */
    public function messages(): array
    {
        return [
            'discount_price.lt' => 'Harga diskon harus lebih murah dari harga asli!',
            'price.required'    => 'Harga asli wajib diisi.',
            'name.required'     => 'Nama produk tidak boleh kosong.',
        ];
    }
}