<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemasukanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'kategori' => 'required|in:pembayaran_kost,pemasukan_lainnya',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ];

        if ($this->input('kategori') === 'pembayaran_kost') {
            $rules['penghuni_id'] = 'required|exists:penghunis,id';
            $rules['jumlah'] = 'nullable|numeric|min:0';
        } else {
            $rules['jumlah'] = 'required|numeric|min:0';
            $rules['penghuni_id'] = 'nullable';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'kategori.required' => 'Kategori pemasukan wajib dipilih.',
            'kategori.in' => 'Kategori pemasukan tidak valid.',
            'tanggal.required' => 'Tanggal pemasukan wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'penghuni_id.required' => 'Penghuni wajib dipilih untuk kategori pembayaran kost.',
            'penghuni_id.exists' => 'Data penghuni tidak ditemukan.',
            'jumlah.required' => 'Jumlah pemasukan lainnya wajib diisi.',
            'jumlah.numeric' => 'Jumlah pemasukan harus berupa angka.',
            'jumlah.min' => 'Jumlah pemasukan tidak boleh bernilai negatif.',
        ];
    }
}
