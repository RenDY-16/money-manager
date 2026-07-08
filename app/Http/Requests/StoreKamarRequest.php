<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKamarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_kamar' => 'required|string|max:50',
            'tipe' => 'required|in:single,double',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,terisi',
        ];
    }
}
