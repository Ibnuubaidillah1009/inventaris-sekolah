<?php

namespace App\Http\Requests;

use App\Models\Kerusakan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePerbaikanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi input perbaikan aset.
     * Format body yang diharapkan:
     * {
     *   "id_kerusakan": 3,
     *   "tanggal_perbaikan": "2026-04-17",
     *   "tanggal_selesai": "2026-04-20",
     *   "pelaksana": "CV Teknik Jaya",
     *   "biaya": 500000,
     *   "status_perbaikan": "Selesai",
     *   "keterangan": "Ganti layar LCD"
     * }
     */
    public function rules(): array
    {
        return [
            'id_kerusakan'      => ['required', 'integer', 'exists:kerusakan,id_kerusakan'],
            'tanggal_perbaikan' => ['required', 'date'],
            'tanggal_selesai'   => ['nullable', 'date', 'after_or_equal:tanggal_perbaikan'],
            'pelaksana'         => ['required', 'string', 'max:255'],
            'biaya'             => ['nullable', 'numeric', 'min:0'],
            'status_perbaikan'  => ['required', 'string', 'in:Proses,Selesai'],
            'keterangan'        => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_kerusakan.required'             => 'ID kerusakan wajib diisi.',
            'id_kerusakan.exists'               => 'Data kerusakan tidak ditemukan.',
            'tanggal_perbaikan.required'        => 'Tanggal perbaikan wajib diisi.',
            'tanggal_perbaikan.date'            => 'Format tanggal perbaikan tidak valid.',
            'tanggal_selesai.after_or_equal'    => 'Tanggal selesai tidak boleh sebelum tanggal perbaikan.',
            'pelaksana.required'                => 'Nama pelaksana/teknisi wajib diisi.',
            'biaya.numeric'                     => 'Biaya harus berupa angka.',
            'biaya.min'                         => 'Biaya tidak boleh negatif.',
            'status_perbaikan.required'         => 'Status perbaikan wajib diisi.',
            'status_perbaikan.in'               => 'Status perbaikan harus berisi: Proses atau Selesai.',
        ];
    }

    /**
     * Validasi tambahan:
     * 1. Jika status_perbaikan = "Selesai", tanggal_selesai wajib diisi.
     * 2. Kerusakan yang dirujuk tidak boleh sudah berstatus "Selesai Diperbaiki".
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            // Jika selesai, tanggal_selesai wajib ada
            if ($this->input('status_perbaikan') === 'Selesai' && empty($this->input('tanggal_selesai'))) {
                $validator->errors()->add(
                    'tanggal_selesai',
                    'Tanggal selesai wajib diisi jika status perbaikan adalah Selesai.'
                );
            }

            // Cek status kerusakan
            $kerusakan = Kerusakan::find($this->input('id_kerusakan'));

            if ($kerusakan && $kerusakan->status_kerusakan === 'Selesai Diperbaiki') {
                $validator->errors()->add(
                    'id_kerusakan',
                    'Kerusakan ini sudah selesai diperbaiki.'
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
