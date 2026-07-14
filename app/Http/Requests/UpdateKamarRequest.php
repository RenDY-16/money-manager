<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKamarRequest extends FormRequest
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
