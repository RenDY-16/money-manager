<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenghuniRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'no_hp' => ['required', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
        ];
    }

    public function messages(): array
    {
        return [
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'no_hp.regex' => 'Nomor telepon hanya boleh berisi angka. Hapus huruf, spasi, tanda plus, atau simbol lain.',
            'no_hp.min' => 'Nomor telepon minimal 10 digit.',
            'no_hp.max' => 'Nomor telepon maksimal 15 digit.',
            'tanggal_keluar.after_or_equal' => 'Tanggal keluar tidak boleh lebih awal dari tanggal masuk.',
        ];
    }
}
