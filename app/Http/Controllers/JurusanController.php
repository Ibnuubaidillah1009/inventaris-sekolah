<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJurusanRequest;
use App\Http\Requests\UpdateJurusanRequest;
use App\Http\Resources\JurusanResource;
use App\Models\Jurusan;
use Illuminate\Http\JsonResponse;

class JurusanController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Jurusan::with('rombel')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar jurusan berhasil diambil.',
            'data'    => JurusanResource::collection($data),
        ]);
    }

    public function store(StoreJurusanRequest $request): JsonResponse
    {
        $jurusan = Jurusan::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Jurusan berhasil ditambahkan.',
            'data'    => new JurusanResource($jurusan),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $jurusan = Jurusan::with('rombel.kelas')->find($id);

        if (!$jurusan) {
            return response()->json([
                'status'  => false,
                'message' => 'Jurusan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail jurusan berhasil diambil.',
            'data'    => new JurusanResource($jurusan),
        ]);
    }

    public function update(UpdateJurusanRequest $request, string $id): JsonResponse
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'status'  => false,
                'message' => 'Jurusan tidak ditemukan.',
            ], 404);
        }

        $jurusan->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Jurusan berhasil diperbarui.',
            'data'    => new JurusanResource($jurusan),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'status'  => false,
                'message' => 'Jurusan tidak ditemukan.',
            ], 404);
        }

        $jurusan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Jurusan berhasil dihapus.',
        ]);
    }
}
