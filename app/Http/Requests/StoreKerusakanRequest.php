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
     *   "kode_barang": 1,
     *   "tanggal_kerusakan": "2026-04-16",
     *   "jenis_kerusakan": "Ringan",
     *   "deskripsi": "Layar monitor berkedip-kedip",
     *   "keterangan": "Perlu dicek segera"
     * }
     */
    public function rules(): array
    {
        return [
            'kode_barang'            => ['required', 'integer', 'exists:aset,kode_barang'],
            'tanggal_kerusakan'  => ['required', 'date'],
            'jenis_kerusakan'    => ['required', 'string', 'max:100'],
            'deskripsi'          => ['required', 'string'],
            'keterangan'         => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.required'           => 'ID aset wajib diisi.',
            'kode_barang.exists'             => 'Aset tidak ditemukan.',
            'tanggal_kerusakan.required' => 'Tanggal kerusakan wajib diisi.',
            'tanggal_kerusakan.date'     => 'Format tanggal kerusakan tidak valid.',
            'jenis_kerusakan.required'   => 'Jenis kerusakan wajib diisi.',
            'deskripsi.required'         => 'Deskripsi kerusakan wajib diisi.',
        ];
    }

    /**
     * Validasi tambahan:
     * Aset tidak boleh berstatus "Dihapus" atau "Dimusnahkan".
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

            if (in_array($aset->status, ['Dihapus', 'Dimusnahkan'])) {
                $validator->errors()->add(
                    'kode_barang',
                    "Aset '{$aset->kode_aset}' sudah dihapus/dimusnahkan dan tidak bisa dilaporkan rusak."
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
