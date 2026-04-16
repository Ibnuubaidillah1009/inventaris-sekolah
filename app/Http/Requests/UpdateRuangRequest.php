<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRuangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_ruang' => ['sometimes', 'required', 'string', 'max:100'],
            'id_lokasi'  => ['sometimes', 'required', 'integer', 'exists:lokasi,id_lokasi'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_lokasi.exists' => 'Lokasi tidak ditemukan.',
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
