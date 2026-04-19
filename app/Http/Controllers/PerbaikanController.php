<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerbaikanRequest;
use App\Http\Resources\PerbaikanResource;
use App\Models\Aset;
use App\Models\Kerusakan;
use App\Models\Perbaikan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PerbaikanController extends Controller
{
    /**
     * Tampilkan daftar semua perbaikan.
     * Eager load: kerusakan → aset → masterBarang
     *
     * @OA\Get(
     *     path="/perbaikan",
     *     operationId="indexPerbaikan",
     *     tags={"Perbaikan"},
     *     summary="Daftar semua perbaikan",
     *     description="Mengambil daftar semua data perbaikan beserta kerusakan dan aset terkait.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar perbaikan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar perbaikan berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Perbaikan::with([
            'kerusakan.aset.masterBarang',
        ])->orderByDesc('id_perbaikan')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar perbaikan berhasil diambil.',
            'data'    => PerbaikanResource::collection($data),
        ]);
    }

    /**
     * Simpan data perbaikan baru menggunakan DB::transaction().
     *
     * Proses:
     * 1. Insert data ke tabel perbaikan.
     * 2. Update status_kerusakan menjadi "Sedang Diperbaiki".
     *
     * Validasi dilakukan di StorePerbaikanRequest::withValidator().
     *
     * @OA\Post(
     *     path="/perbaikan",
     *     operationId="storePerbaikan",
     *     tags={"Perbaikan"},
     *     summary="Simpan data perbaikan baru",
     *     description="Menyimpan data perbaikan untuk kerusakan tertentu. Status kerusakan otomatis diubah menjadi 'Sedang Diperbaiki'.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"id_kerusakan","tanggal_perbaikan","tindakan_perbaikan"},
     *             @OA\Property(property="id_kerusakan", type="integer", example=1),
     *             @OA\Property(property="tanggal_perbaikan", type="string", format="date", example="2026-04-18"),
     *             @OA\Property(property="teknisi", type="string", nullable=true, example="Budi Santoso"),
     *             @OA\Property(property="biaya_perbaikan", type="number", nullable=true, minimum=0, example=250000),
     *             @OA\Property(property="tindakan_perbaikan", type="string", example="Ganti layar LCD")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Data perbaikan berhasil disimpan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data perbaikan berhasil disimpan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan data perbaikan")
     * )
     */
    public function store(StorePerbaikanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $perbaikan = DB::transaction(function () use ($validated) {

                // ──────────────────────────────────────────────────────
                // 1. Insert data perbaikan
                // ──────────────────────────────────────────────────────
                $perbaikan = Perbaikan::create([
                    'id_kerusakan'       => $validated['id_kerusakan'],
                    'tanggal_perbaikan'  => $validated['tanggal_perbaikan'],
                    'teknisi'            => $validated['teknisi'] ?? null,
                    'biaya_perbaikan'    => $validated['biaya_perbaikan'] ?? null,
                    'tindakan_perbaikan' => $validated['tindakan_perbaikan'],
                ]);

                // ──────────────────────────────────────────────────────
                // 2. Update status kerusakan → "Sedang Diperbaiki"
                // ──────────────────────────────────────────────────────
                $kerusakan = Kerusakan::findOrFail($validated['id_kerusakan']);
                $kerusakan->update([
                    'status_kerusakan' => 'Sedang Diperbaiki',
                ]);

                return $perbaikan;
            });

            // Eager load untuk response
            $perbaikan->load([
                'kerusakan.aset.masterBarang',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data perbaikan berhasil disimpan.',
                'data'    => new PerbaikanResource($perbaikan),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan data perbaikan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu perbaikan.
     *
     * @OA\Get(
     *     path="/perbaikan/{id}",
     *     operationId="showPerbaikan",
     *     tags={"Perbaikan"},
     *     summary="Detail perbaikan",
     *     description="Mengambil detail satu data perbaikan beserta kerusakan dan aset terkait.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID perbaikan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail perbaikan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail perbaikan berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Perbaikan tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $perbaikan = Perbaikan::with([
            'kerusakan.aset.masterBarang',
        ])->find($id);

        if (!$perbaikan) {
            return response()->json([
                'status'  => false,
                'message' => 'Perbaikan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail perbaikan berhasil diambil.',
            'data'    => new PerbaikanResource($perbaikan),
        ]);
    }

    /**
     * Hapus data perbaikan.
     *
     * @OA\Delete(
     *     path="/perbaikan/{id}",
     *     operationId="destroyPerbaikan",
     *     tags={"Perbaikan"},
     *     summary="Hapus data perbaikan",
     *     description="Menghapus data perbaikan berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID perbaikan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Data perbaikan berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data perbaikan berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Perbaikan tidak ditemukan"),
     *     @OA\Response(response=500, description="Gagal menghapus data perbaikan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $perbaikan = Perbaikan::find($id);

        if (!$perbaikan) {
            return response()->json([
                'status'  => false,
                'message' => 'Perbaikan tidak ditemukan.',
            ], 404);
        }

        try {
            $perbaikan->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data perbaikan berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus data perbaikan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
