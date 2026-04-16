<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;
use App\Http\Resources\KelasResource;
use App\Models\Kelas;
use Illuminate\Http\JsonResponse;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar semua kelas beserta relasi rombel.
     */
    public function index(): JsonResponse
    {
        $kelas = Kelas::with('rombel.jurusan')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar kelas berhasil diambil.',
            'data'    => KelasResource::collection($kelas),
        ]);
    }

    /**
     * Simpan kelas baru.
     */
    public function store(StoreKelasRequest $request): JsonResponse
    {
        $kelas = Kelas::create($request->validated());
        $kelas->load('rombel.jurusan');

        return response()->json([
            'status'  => true,
            'message' => 'Kelas berhasil ditambahkan.',
            'data'    => new KelasResource($kelas),
        ], 201);
    }

    /**
     * Tampilkan detail satu kelas.
     */
    public function show(string $id): JsonResponse
    {
        $kelas = Kelas::with('rombel.jurusan')->find($id);

        if (!$kelas) {
            return response()->json([
                'status'  => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail kelas berhasil diambil.',
            'data'    => new KelasResource($kelas),
        ]);
    }

    /**
     * Update data kelas.
     */
    public function update(UpdateKelasRequest $request, string $id): JsonResponse
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'status'  => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $kelas->update($request->validated());
        $kelas->load('rombel.jurusan');

        return response()->json([
            'status'  => true,
            'message' => 'Kelas berhasil diperbarui.',
            'data'    => new KelasResource($kelas),
        ]);
    }

    /**
     * Hapus kelas.
     */
    public function destroy(string $id): JsonResponse
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'status'  => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $kelas->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Kelas berhasil dihapus.',
        ]);
    }
}
