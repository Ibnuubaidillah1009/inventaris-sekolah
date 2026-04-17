<?php

namespace App\Http\Requests;

use App\Models\Aset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi input peminjaman.
     * Format body yang diharapkan:
     * {
     *   "kode_peminjaman": "PJM-2026-001",
     *   "tanggal_pinjam": "2026-04-16",
     *   "tanggal_kembali": "2026-04-23",
     *   "id_peminjam": 5,
     *   "keterangan": "Untuk kegiatan praktik",
     *   "detail": [
     *     { "kode_barang": 1, "jumlah": 1, "keterangan": "Laptop A" },
     *     { "kode_barang": 2, "jumlah": 1, "keterangan": "Proyektor B" }
     *   ]
     * }
     */
    public function rules(): array
    {
        return [
            'nomor_peminjaman'    => ['required', 'string', 'max:100', 'unique:peminjaman,nomor_peminjaman'],
            'tanggal_pinjam'     => ['required', 'date'],
            'tanggal_kembali'    => ['nullable', 'date', 'after_or_equal:tanggal_pinjam'],
            'id_peminjam'        => ['required', 'integer', 'exists:pengguna,id_pengguna'],
            'keterangan'         => ['nullable', 'string'],

            // Validasi array detail peminjaman (multi-item)
            'detail'             => ['required', 'array', 'min:1'],
            'detail.*.kode_barang'   => ['required', 'string', 'exists:aset,kode_barang'],
            'detail.*.jumlah'    => ['required', 'integer', 'min:1'],
            'detail.*.keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_peminjaman.required'  => 'Kode peminjaman wajib diisi.',
            'nomor_peminjaman.unique'    => 'Kode peminjaman sudah ada.',
            'tanggal_pinjam.required'   => 'Tanggal pinjam wajib diisi.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'id_peminjam.required'      => 'Peminjam wajib dipilih.',
            'id_peminjam.exists'        => 'Peminjam tidak ditemukan.',
            'detail.required'           => 'Detail peminjaman wajib diisi.',
            'detail.min'                => 'Minimal harus ada 1 item yang dipinjam.',
            'detail.*.kode_barang.required' => 'ID aset wajib diisi untuk setiap item.',
            'detail.*.kode_barang.exists'   => 'Aset tidak ditemukan.',
            'detail.*.jumlah.required'  => 'Jumlah wajib diisi untuk setiap item.',
            'detail.*.jumlah.min'       => 'Jumlah minimal 1.',
        ];
    }

    /**
     * Validasi tambahan: cek ketersediaan setiap aset setelah validasi dasar lolos.
     * Aset WAJIB berstatus "Tersedia" dan kondisi "Baik".
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return; // Jangan cek availability jika ada error lain
            }

            $details = $this->input('detail', []);
            $idAsets = collect($details)->pluck('kode_barang')->unique();

            // Query semua aset yang diminta
            $asets = Aset::whereIn('kode_barang', $idAsets)->get()->keyBy('kode_barang');

            foreach ($details as $index => $item) {
                $aset = $asets->get($item['kode_barang']);

                if (!$aset) {
                    $validator->errors()->add(
                        "detail.{$index}.kode_barang",
                        "Aset dengan ID {$item['kode_barang']} tidak ditemukan."
                    );
                    continue;
                }

                if ($aset->status !== 'Tersedia') {
                    $validator->errors()->add(
                        "detail.{$index}.kode_barang",
                        "Aset '{$aset->kode_aset}' tidak tersedia (status saat ini: {$aset->status})."
                    );
                }

                if ($aset->kondisi !== 'Baik') {
                    $validator->errors()->add(
                        "detail.{$index}.kode_barang",
                        "Aset '{$aset->kode_aset}' tidak dalam kondisi baik (kondisi saat ini: {$aset->kondisi})."
                    );
                }
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
