<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Resources\PenggunaResource;
use App\Models\Pengguna;
use Illuminate\Http\JsonResponse;

class PenggunaController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     */
    public function index(): JsonResponse
    {
        $pengguna = Pengguna::with(['peran', 'kelas', 'mapel', 'unit'])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar pengguna berhasil diambil.',
            'data'    => PenggunaResource::collection($pengguna),
        ]);
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(StorePenggunaRequest $request): JsonResponse
    {
        $pengguna = Pengguna::create($request->validated());
        $pengguna->load(['peran', 'kelas', 'mapel', 'unit']);

        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil ditambahkan.',
            'data'    => new PenggunaResource($pengguna),
        ], 201);
    }

    /**
     * Tampilkan detail satu pengguna.
     */
    public function show(string $id): JsonResponse
    {
        $pengguna = Pengguna::with(['peran.aksesList', 'kelas.rombel.jurusan', 'mapel', 'unit'])->find($id);

        if (!$pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail pengguna berhasil diambil.',
            'data'    => new PenggunaResource($pengguna),
        ]);
    }

    /**
     * Update data pengguna.
     */
    public function update(UpdatePenggunaRequest $request, string $id): JsonResponse
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $data = $request->validated();

        // Jika password tidak diisi, jangan update password
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $pengguna->update($data);
        $pengguna->load(['peran', 'kelas', 'mapel', 'unit']);

        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil diperbarui.',
            'data'    => new PenggunaResource($pengguna),
        ]);
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(string $id): JsonResponse
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil dihapus.',
        ]);
    }
}
