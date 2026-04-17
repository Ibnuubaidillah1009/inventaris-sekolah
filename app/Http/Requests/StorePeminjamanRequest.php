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
     *   "nomor_peminjaman": "PJM-2026-001",
     *   "tanggal_pinjam": "2026-04-16",
     *   "id_peminjam": 5,
     *   "nomor_telepon": "081234567890",
     *   "lama_pinjam_hari": 7,
     *   "keterangan": "Untuk kegiatan praktik",
     *   "detail": [
     *     { "kode_barang": "BRG-001" },
     *     { "kode_barang": "BRG-002" }
     *   ]
     * }
     */
    public function rules(): array
    {
        return [
            'nomor_peminjaman'       => ['required', 'string', 'max:50', 'unique:peminjaman,nomor_peminjaman'],
            'tanggal_pinjam'         => ['required', 'date'],
            'id_peminjam'            => ['required', 'integer', 'exists:pengguna,id_pengguna'],
            'nomor_telepon'          => ['nullable', 'string', 'max:20'],
            'lama_pinjam_hari'       => ['required', 'integer', 'min:1'],
            'keterangan'             => ['nullable', 'string'],

            // Validasi array detail peminjaman (multi-item)
            'detail'                 => ['required', 'array', 'min:1'],
            'detail.*.kode_barang'   => ['required', 'string', 'exists:aset,kode_barang'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_peminjaman.required'     => 'Nomor peminjaman wajib diisi.',
            'nomor_peminjaman.unique'       => 'Nomor peminjaman sudah ada.',
            'tanggal_pinjam.required'       => 'Tanggal pinjam wajib diisi.',
            'id_peminjam.required'          => 'Peminjam wajib dipilih.',
            'id_peminjam.exists'            => 'Peminjam tidak ditemukan.',
            'lama_pinjam_hari.required'     => 'Lama pinjam (hari) wajib diisi.',
            'lama_pinjam_hari.min'          => 'Lama pinjam minimal 1 hari.',
            'detail.required'               => 'Detail peminjaman wajib diisi.',
            'detail.min'                    => 'Minimal harus ada 1 item yang dipinjam.',
            'detail.*.kode_barang.required' => 'Kode barang wajib diisi untuk setiap item.',
            'detail.*.kode_barang.exists'   => 'Aset tidak ditemukan.',
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
            $kodeBarangs = collect($details)->pluck('kode_barang')->unique();

            // Query semua aset yang diminta
            $asets = Aset::whereIn('kode_barang', $kodeBarangs)->get()->keyBy('kode_barang');

            foreach ($details as $index => $item) {
                $aset = $asets->get($item['kode_barang']);

                if (!$aset) {
                    $validator->errors()->add(
                        "detail.{$index}.kode_barang",
                        "Aset dengan kode {$item['kode_barang']} tidak ditemukan."
                    );
                    continue;
                }

                if ($aset->status_ketersediaan !== 'Tersedia') {
                    $validator->errors()->add(
                        "detail.{$index}.kode_barang",
                        "Aset '{$aset->kode_barang}' tidak tersedia (status saat ini: {$aset->status_ketersediaan})."
                    );
                }

                if ($aset->kondisi_barang !== 'Baik') {
                    $validator->errors()->add(
                        "detail.{$index}.kode_barang",
                        "Aset '{$aset->kode_barang}' tidak dalam kondisi baik (kondisi saat ini: {$aset->kondisi_barang})."
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
