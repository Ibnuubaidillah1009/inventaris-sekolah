<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_aset'        => ['required', 'string', 'max:100', 'unique:aset,kode_aset'],
            'id_master_barang' => ['required', 'integer', 'exists:master_barang,id_master_barang'],
            'id_ruang'         => ['nullable', 'integer', 'exists:ruang,id_ruang'],
            'tahun_perolehan'  => ['nullable', 'integer', 'digits:4'],
            'nilai_perolehan'  => ['nullable', 'numeric', 'min:0'],
            'sumber_dana'      => ['nullable', 'string', 'max:100'],
            'kondisi'          => ['required', 'string', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'status'           => ['required', 'string', 'in:Tersedia,Dipinjam,Dalam Perbaikan,Dihapuskan'],
            'keterangan'       => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_aset.required'        => 'Kode aset wajib diisi.',
            'kode_aset.unique'          => 'Kode aset sudah digunakan.',
            'id_master_barang.required' => 'Master barang wajib dipilih.',
            'id_master_barang.exists'   => 'Master barang tidak ditemukan.',
            'id_ruang.exists'           => 'Ruang tidak ditemukan.',
            'kondisi.required'          => 'Kondisi aset wajib diisi.',
            'kondisi.in'               => 'Kondisi harus salah satu dari: Baik, Rusak Ringan, Rusak Berat.',
            'status.required'           => 'Status aset wajib diisi.',
            'status.in'                => 'Status harus salah satu dari: Tersedia, Dipinjam, Dalam Perbaikan, Dihapuskan.',
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
