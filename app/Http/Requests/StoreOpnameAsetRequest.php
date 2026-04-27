<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOpnameAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_barang'       => ['required', 'string', 'max:50', 'exists:aset,kode_barang'],
            'tanggal_opname'    => ['required', 'date'],
            'kondisi_ditemukan' => ['required', 'string', 'in:Baik,Rusak Ringan,Rusak Berat,Hilang'],
            'keterangan'        => ['nullable', 'string'],
            'id_pemeriksa' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.exists'        => 'Kode barang tidak ditemukan di data aset.',
            'kondisi_ditemukan.in'      => 'Kondisi ditemukan harus salah satu dari: Baik, Rusak Ringan, Rusak Berat, Hilang.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status'  => false,
            'message' => 'Validasi gagal.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
