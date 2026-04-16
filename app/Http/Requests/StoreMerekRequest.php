<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMerekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_merek' => ['required', 'string', 'max:100', 'unique:merek,nama_merek'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_merek.required' => 'Nama merek wajib diisi.',
            'nama_merek.unique'   => 'Nama merek sudah ada.',
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
