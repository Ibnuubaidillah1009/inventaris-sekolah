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

    /**
     * Validasi input aset baru.
     * Format body yang diharapkan:
     * {
     *   "kode_barang": "BRG-2026-001",
     *   "id_master_barang": 1,
     *   "id_ruang": 1,
     *   "tanggal_registrasi": "2026-04-16",
     *   "kondisi_barang": "Baik",
     *   "nilai_residu": 0,
     *   "status_ketersediaan": "Tersedia",
     *   "gambar": "foto.jpg"
     * }
     */
    public function rules(): array
    {
        return [
            'kode_barang'         => ['required', 'string', 'max:50', 'unique:aset,kode_barang'],
            'id_master_barang'    => ['required', 'integer', 'exists:master_barang,id_master_barang'],
            'id_ruang'            => ['nullable', 'integer', 'exists:ruang,id_ruang'],
            'tanggal_registrasi'  => ['required', 'date'],
            'kondisi_barang'      => ['required', 'string', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'nilai_residu'        => ['nullable', 'numeric', 'min:0'],
            'status_ketersediaan' => ['nullable', 'string', 'in:Tersedia,Dipinjam,Non-Aktif,Dihapus'],
            'gambar'              => ['nullable', 'string', 'max:255'],
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
            'kondisi_barang.required'      => 'Kondisi barang wajib diisi.',
            'kondisi_barang.in'            => 'Kondisi barang harus salah satu dari: Baik, Rusak Ringan, Rusak Berat.',
            'status_ketersediaan.in'       => 'Status harus salah satu dari: Tersedia, Dipinjam, Non-Aktif, Dihapus.',
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
