<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePermintaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Format body yang diharapkan:
     * {
     *   "tanggal_permintaan": "2026-04-16",
     *   "id_pemohon": 5,
     *   "keterangan": "Permintaan alat praktik",
     *   "detail": [
     *     { "id_master_barang": 1, "jumlah": 5, "keterangan": "Laptop" },
     *     { "id_master_barang": 3, "jumlah": 2, "keterangan": "Proyektor" }
     *   ]
     * }
     */
    public function rules(): array
    {
        return [
            'tanggal_permintaan'          => ['required', 'date'],
            'id_pemohon'                  => ['required', 'integer', 'exists:pengguna,id_pengguna'],
            'keterangan'                  => ['nullable', 'string'],

            // Validasi array detail permintaan
            'detail'                      => ['required', 'array', 'min:1'],
            'detail.*.id_master_barang'   => ['required', 'integer', 'exists:master_barang,id_master_barang'],
            'detail.*.jumlah'             => ['required', 'integer', 'min:1'],
            'detail.*.keterangan'         => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_permintaan.required'        => 'Tanggal permintaan wajib diisi.',
            'id_pemohon.required'                => 'Pemohon wajib dipilih.',
            'id_pemohon.exists'                  => 'Pemohon tidak ditemukan.',
            'detail.required'                    => 'Detail permintaan wajib diisi.',
            'detail.min'                         => 'Minimal harus ada 1 item yang diminta.',
            'detail.*.id_master_barang.required' => 'Master barang wajib diisi untuk setiap item.',
            'detail.*.id_master_barang.exists'   => 'Master barang tidak ditemukan.',
            'detail.*.jumlah.required'           => 'Jumlah wajib diisi untuk setiap item.',
            'detail.*.jumlah.min'                => 'Jumlah minimal 1.',
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
