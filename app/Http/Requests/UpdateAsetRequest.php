<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('aset');

        return [
            'kode_barang'         => ['sometimes', 'required', 'string', 'max:50', "unique:aset,kode_barang,{$id},kode_barang"],
            'id_master_barang'    => ['sometimes', 'required', 'integer', 'exists:master_barang,id_master_barang'],
            'id_ruang'            => ['nullable', 'integer', 'exists:ruang,id_ruang'],
            'tanggal_registrasi'  => ['sometimes', 'required', 'date'],
            'id_kondisi'          => ['sometimes', 'required', 'integer', 'exists:kondisi,id_kondisi'],
            'nilai_residu'        => ['nullable', 'numeric', 'min:0'],
            'id_status'           => ['sometimes', 'required', 'integer', 'exists:status_barang,id_status'],
            'gambar'              => ['nullable', 'string'],
            'keterangan'          => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.unique'           => 'Kode barang sudah digunakan.',
            'id_master_barang.exists'      => 'Master barang tidak ditemukan.',
            'id_ruang.exists'              => 'Ruang tidak ditemukan.',
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
