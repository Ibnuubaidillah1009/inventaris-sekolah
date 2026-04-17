<?php

namespace App\Http\Requests;

use App\Models\Aset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMutasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi input mutasi aset.
     * Format body yang diharapkan:
     * {
     *   "kode_barang": "BRG-001",
     *   "id_ruang_tujuan": 5,
     *   "tanggal_mutasi": "2026-04-16",
     *   "alasan_mutasi": "Pemindahan ke Lab Komputer"
     * }
     */
    public function rules(): array
    {
        return [
            'kode_barang'     => ['required', 'string', 'exists:aset,kode_barang'],
            'id_ruang_tujuan' => ['required', 'integer', 'exists:ruang,id_ruang'],
            'tanggal_mutasi'  => ['required', 'date'],
            'alasan_mutasi'   => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.required'     => 'Kode barang wajib diisi.',
            'kode_barang.exists'       => 'Aset tidak ditemukan.',
            'id_ruang_tujuan.required' => 'Ruang tujuan wajib dipilih.',
            'id_ruang_tujuan.exists'   => 'Ruang tujuan tidak ditemukan.',
            'tanggal_mutasi.required'  => 'Tanggal mutasi wajib diisi.',
            'tanggal_mutasi.date'      => 'Format tanggal mutasi tidak valid.',
        ];
    }

    /**
     * Validasi tambahan:
     * 1. Aset harus berstatus "Tersedia" (tidak sedang dipinjam/dihapus).
     * 2. Ruang tujuan tidak boleh sama dengan ruang asal aset saat ini.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $aset = Aset::find($this->input('kode_barang'));

            if (!$aset) {
                return;
            }

            // Cek status aset — hanya aset Tersedia yang boleh dimutasi
            if ($aset->status_ketersediaan !== 'Tersedia') {
                $validator->errors()->add(
                    'kode_barang',
                    "Aset '{$aset->kode_barang}' tidak dapat dimutasi (status saat ini: {$aset->status_ketersediaan})."
                );
            }

            // Cek ruang tujuan tidak sama dengan ruang asal
            if ((int) $aset->id_ruang === (int) $this->input('id_ruang_tujuan')) {
                $validator->errors()->add(
                    'id_ruang_tujuan',
                    "Ruang tujuan tidak boleh sama dengan ruang asal aset saat ini."
                );
            }
        });
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
