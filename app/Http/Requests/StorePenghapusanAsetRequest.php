<?php

namespace App\Http\Requests;

use App\Models\Aset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePenghapusanAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi input penghapusan aset.
     * Format body yang diharapkan:
     * {
     *   "id_aset": 1,
     *   "tanggal_penghapusan": "2026-04-16",
     *   "alasan": "Sudah tidak layak pakai",
     *   "metode_penghapusan": "Dimusnahkan",
     *   "id_penyetuju": 2,
     *   "dokumen_pendukung": "SK-001/2026",
     *   "keterangan": "Dimusnahkan sesuai berita acara"
     * }
     */
    public function rules(): array
    {
        return [
            'id_aset'              => ['required', 'integer', 'exists:aset,id_aset'],
            'tanggal_penghapusan'  => ['required', 'date'],
            'alasan'               => ['required', 'string'],
            'metode_penghapusan'   => ['required', 'string', 'in:Dimusnahkan,Dilelang,Dihibahkan'],
            'id_penyetuju'         => ['required', 'integer', 'exists:pengguna,id_pengguna'],
            'dokumen_pendukung'    => ['nullable', 'string', 'max:255'],
            'keterangan'           => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_aset.required'              => 'ID aset wajib diisi.',
            'id_aset.exists'                => 'Aset tidak ditemukan.',
            'tanggal_penghapusan.required'  => 'Tanggal penghapusan wajib diisi.',
            'tanggal_penghapusan.date'      => 'Format tanggal penghapusan tidak valid.',
            'alasan.required'               => 'Alasan penghapusan wajib diisi.',
            'metode_penghapusan.required'   => 'Metode penghapusan wajib dipilih.',
            'metode_penghapusan.in'         => 'Metode penghapusan harus berisi: Dimusnahkan, Dilelang, atau Dihibahkan.',
            'id_penyetuju.required'         => 'Penyetuju wajib dipilih.',
            'id_penyetuju.exists'           => 'Penyetuju tidak ditemukan.',
        ];
    }

    /**
     * Validasi tambahan:
     * 1. Aset tidak boleh sudah berstatus "Dihapus" atau "Dimusnahkan".
     * 2. Aset tidak boleh sedang "Dipinjam".
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $aset = Aset::find($this->input('id_aset'));

            if (!$aset) {
                return;
            }

            if (in_array($aset->status, ['Dihapus', 'Dimusnahkan'])) {
                $validator->errors()->add(
                    'id_aset',
                    "Aset '{$aset->kode_aset}' sudah berstatus {$aset->status}."
                );
            }

            if ($aset->status === 'Dipinjam') {
                $validator->errors()->add(
                    'id_aset',
                    "Aset '{$aset->kode_aset}' sedang dipinjam dan tidak dapat dihapuskan."
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
