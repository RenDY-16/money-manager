<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengeluaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:100',
            'kategori_baru' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ];

        if ($this->input('kategori') === '__custom__') {
            $rules['kategori_baru'] = 'required|string|max:100';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'jumlah.required' => 'Jumlah pengeluaran wajib diisi.',
            'jumlah.numeric' => 'Jumlah pengeluaran harus berupa angka.',
            'jumlah.min' => 'Jumlah pengeluaran tidak boleh bernilai negatif.',
            'tanggal.required' => 'Tanggal pengeluaran wajib diisi.',
            'kategori.required' => 'Kategori pengeluaran wajib dipilih.',
            'kategori_baru.required' => 'Nama kategori baru wajib diisi.',
            'kategori_baru.max' => 'Nama kategori baru maksimal 100 karakter.',
        ];
    }
}
