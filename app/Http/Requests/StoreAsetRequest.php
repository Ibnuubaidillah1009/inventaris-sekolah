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
            'kode_barang'         => ['required', 'string', 'max:50', 'unique:aset,kode_barang'],
            'id_master_barang'    => ['required', 'integer', 'exists:master_barang,id_master_barang'],
            'id_ruang'            => ['nullable', 'integer', 'exists:ruang,id_ruang'],
            'tanggal_registrasi'  => ['required', 'date'],
            'id_kondisi'          => ['required', 'integer', 'exists:kondisi,id_kondisi'],
            'nilai_residu'        => ['nullable', 'numeric', 'min:0'],
            'id_status'           => ['nullable', 'integer', 'exists:status_barang,id_status'],
            'gambar'              => ['nullable', 'string', 'max:255'],
            'keterangan'          => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.required'         => 'Kode barang wajib diisi.',
            'kode_barang.unique'           => 'Kode barang sudah digunakan.',
            'id_master_barang.required'    => 'Master barang wajib dipilih.',
            'id_master_barang.exists'      => 'Master barang tidak ditemukan.',
            'id_ruang.exists'              => 'Ruang tidak ditemukan.',
            'tanggal_registrasi.required'  => 'Tanggal registrasi wajib diisi.',
            'tanggal_registrasi.date'      => 'Format tanggal registrasi tidak valid.',
            'id_kondisi.required'          => 'Kondisi barang wajib dipilih.',
            'id_kondisi.exists'            => 'Kondisi barang tidak ditemukan.',
            'id_status.exists'             => 'Status barang tidak ditemukan.',
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
