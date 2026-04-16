<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRuangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_ruang' => ['required', 'string', 'max:100'],
            'id_lokasi'  => ['required', 'integer', 'exists:lokasi,id_lokasi'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_ruang.required' => 'Nama ruang wajib diisi.',
            'id_lokasi.required'  => 'Lokasi wajib dipilih.',
            'id_lokasi.exists'    => 'Lokasi tidak ditemukan.',
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
