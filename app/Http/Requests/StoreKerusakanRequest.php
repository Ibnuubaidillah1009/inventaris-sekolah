<?php

namespace App\Http\Requests;

use App\Models\Aset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreKerusakanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi input laporan kerusakan aset.
     * Format body yang diharapkan:
     * {
     *   "kode_barang": "BRG-001",
     *   "tanggal_lapor": "2026-04-16",
     *   "deskripsi_kerusakan": "Layar monitor berkedip-kedip",
     *   "tingkat_kerusakan": "Ringan"
     * }
     */
    public function rules(): array
    {
        return [
            'kode_barang'         => ['required', 'string', 'exists:aset,kode_barang'],
            'tanggal_lapor'       => ['required', 'date'],
            'deskripsi_kerusakan' => ['required', 'string'],
            'tingkat_kerusakan'   => ['required', 'string', 'in:Ringan,Sedang,Berat'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.required'         => 'Kode barang wajib diisi.',
            'kode_barang.exists'           => 'Aset tidak ditemukan.',
            'tanggal_lapor.required'       => 'Tanggal lapor wajib diisi.',
            'tanggal_lapor.date'           => 'Format tanggal lapor tidak valid.',
            'deskripsi_kerusakan.required' => 'Deskripsi kerusakan wajib diisi.',
            'tingkat_kerusakan.required'   => 'Tingkat kerusakan wajib diisi.',
            'tingkat_kerusakan.in'         => 'Tingkat kerusakan harus salah satu dari: Ringan, Sedang, Berat.',
        ];
    }

    /**
     * Validasi tambahan:
     * Aset tidak boleh berstatus "Dihapus".
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

            if ($aset->status_ketersediaan === 'Dihapus') {
                $validator->errors()->add(
                    'kode_barang',
                    "Aset '{$aset->kode_barang}' sudah dihapus dan tidak bisa dilaporkan rusak."
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
