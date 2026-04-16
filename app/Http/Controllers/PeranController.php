<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeranRequest;
use App\Http\Requests\UpdatePeranRequest;
use App\Http\Resources\PeranResource;
use App\Models\Peran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PeranController extends Controller
{
    /**
     * Tampilkan daftar semua peran.
     */
    public function index(): JsonResponse
    {
        $peran = Peran::with('aksesList')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar peran berhasil diambil.',
            'data'    => PeranResource::collection($peran),
        ]);
    }

    /**
     * Simpan peran baru.
     */
    public function store(StorePeranRequest $request): JsonResponse
    {
        $peran = Peran::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Peran berhasil ditambahkan.',
            'data'    => new PeranResource($peran),
        ], 201);
    }

    /**
     * Tampilkan detail satu peran.
     */
    public function show(string $id): JsonResponse
    {
        $peran = Peran::with('aksesList')->find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail peran berhasil diambil.',
            'data'    => new PeranResource($peran),
        ]);
    }

    /**
     * Update data peran.
     */
    public function update(UpdatePeranRequest $request, string $id): JsonResponse
    {
        $peran = Peran::find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        $peran->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Peran berhasil diperbarui.',
            'data'    => new PeranResource($peran),
        ]);
    }

    /**
     * Hapus peran.
     */
    public function destroy(string $id): JsonResponse
    {
        $peran = Peran::find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        $peran->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Peran berhasil dihapus.',
        ]);
    }

    /**
     * Sinkronisasi hak akses pada sebuah peran.
     * Menerima array id_akses untuk di-sync ke tabel pivot peran_akses.
     *
     * POST /api/peran/{id}/sync-akses
     * Body: { "id_akses": [1, 2, 3] }
     */
    public function syncAkses(Request $request, string $id): JsonResponse
    {
        $peran = Peran::find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'id_akses'   => ['required', 'array'],
            'id_akses.*' => ['integer', 'exists:akses,id_akses'],
        ]);

        $peran->aksesList()->sync($request->id_akses);
        $peran->load('aksesList');

        return response()->json([
            'status'  => true,
            'message' => 'Hak akses peran berhasil disinkronisasi.',
            'data'    => new PeranResource($peran),
        ]);
    }
}
