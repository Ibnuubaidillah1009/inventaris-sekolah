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
            'kondisi_barang'      => ['sometimes', 'required', 'string', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'nilai_residu'        => ['nullable', 'numeric', 'min:0'],
            'status_ketersediaan' => ['sometimes', 'required', 'string', 'in:Tersedia,Dipinjam,Non-Aktif,Dihapus'],
            'gambar'              => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.unique'           => 'Kode barang sudah digunakan.',
            'id_master_barang.exists'      => 'Master barang tidak ditemukan.',
            'id_ruang.exists'              => 'Ruang tidak ditemukan.',
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
