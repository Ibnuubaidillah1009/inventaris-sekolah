<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermintaanRequest;
use App\Http\Resources\PermintaanResource;
use App\Models\DetailPermintaan;
use App\Models\Permintaan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanController extends Controller
{
    /**
     * Tampilkan daftar semua permintaan.
     *
     * @OA\Get(
     *     path="/permintaan",
     *     operationId="indexPermintaan",
     *     tags={"Permintaan"},
     *     summary="Daftar semua permintaan",
     *     description="Mengambil daftar semua permintaan barang beserta pemohon, penyetuju, dan detail barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar permintaan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar permintaan berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Permintaan::with([
            'pemohon',
            'penyetuju',
            'detailPermintaan.masterBarang',
        ])->orderByDesc('id_permintaan')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar permintaan berhasil diambil.',
            'data'    => PermintaanResource::collection($data),
        ]);
    }

    /**
     * Simpan permintaan baru menggunakan DB::transaction().
     *
     * @OA\Post(
     *     path="/permintaan",
     *     operationId="storePermintaan",
     *     tags={"Permintaan"},
     *     summary="Buat permintaan baru",
     *     description="Menyimpan permintaan barang baru beserta detail item yang diminta.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"tanggal_permintaan","id_pemohon","detail"},
     *             @OA\Property(property="tanggal_permintaan", type="string", format="date", example="2026-04-18"),
     *             @OA\Property(property="id_pemohon", type="integer", example=5),
     *             @OA\Property(property="keterangan", type="string", nullable=true, example="Permintaan alat praktik"),
     *             @OA\Property(
     *                 property="detail",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id_master_barang","jumlah"},
     *                     @OA\Property(property="id_master_barang", type="integer", example=1),
     *                     @OA\Property(property="jumlah", type="integer", minimum=1, example=5),
     *                     @OA\Property(property="keterangan", type="string", nullable=true, example="Laptop")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Permintaan berhasil disimpan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permintaan berhasil disimpan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan permintaan")
     * )
     */
    public function store(StorePermintaanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $permintaan = DB::transaction(function () use ($validated) {
                // Insert header permintaan
                $permintaan = Permintaan::create([
                    'tanggal_permintaan' => $validated['tanggal_permintaan'],
                    'id_pemohon'         => $validated['id_pemohon'],
                    'status_permintaan'  => 'Menunggu',
                    'keterangan'         => $validated['keterangan'] ?? null,
                ]);

                // Insert detail permintaan (multi-item)
                foreach ($validated['detail'] as $item) {
                    DetailPermintaan::create([
                        'id_permintaan'    => $permintaan->id_permintaan,
                        'id_master_barang' => $item['id_master_barang'],
                        'jumlah'           => $item['jumlah'],
                        'keterangan'       => $item['keterangan'] ?? null,
                    ]);
                }

                return $permintaan;
            });

            $permintaan->load([
                'pemohon',
                'detailPermintaan.masterBarang',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Permintaan berhasil disimpan.',
                'data'    => new PermintaanResource($permintaan),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan permintaan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu permintaan.
     *
     * @OA\Get(
     *     path="/permintaan/{id}",
     *     operationId="showPermintaan",
     *     tags={"Permintaan"},
     *     summary="Detail permintaan",
     *     description="Mengambil detail satu permintaan beserta pemohon, penyetuju, dan detail barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID permintaan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail permintaan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail permintaan berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Permintaan tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $permintaan = Permintaan::with([
            'pemohon',
            'penyetuju',
            'detailPermintaan.masterBarang',
        ])->find($id);

        if (!$permintaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail permintaan berhasil diambil.',
            'data'    => new PermintaanResource($permintaan),
        ]);
    }

    /**
     * Setujui atau tolak permintaan.
     *
     * PUT /api/permintaan/{id}/keputusan
     * Body: { "status_permintaan": "Disetujui" | "Ditolak", "id_penyetuju": int }
     *
     * @OA\Put(
     *     path="/permintaan/{id}/keputusan",
     *     operationId="keputusanPermintaan",
     *     tags={"Permintaan"},
     *     summary="Keputusan permintaan (setujui/tolak)",
     *     description="Menyetujui atau menolak permintaan barang. Hanya permintaan berstatus 'Menunggu' yang dapat diproses.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID permintaan", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"status_permintaan","id_penyetuju"},
     *             @OA\Property(property="status_permintaan", type="string", enum={"Disetujui","Ditolak"}, example="Disetujui"),
     *             @OA\Property(property="id_penyetuju", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Permintaan berhasil diproses",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permintaan berhasil Disetujui."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Permintaan tidak ditemukan"),
     *     @OA\Response(
     *         response=422,
     *         description="Permintaan sudah diproses atau validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Permintaan ini sudah diproses sebelumnya.")
     *         )
     *     )
     * )
     */
    public function keputusan(Request $request, string $id): JsonResponse
    {
        $permintaan = Permintaan::find($id);

        if (!$permintaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan tidak ditemukan.',
            ], 404);
        }

        if ($permintaan->status_permintaan !== 'Menunggu') {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan ini sudah diproses sebelumnya.',
            ], 422);
        }

        $request->validate([
            'status_permintaan' => ['required', 'string', 'in:Disetujui,Ditolak'],
            'id_penyetuju'      => ['required', 'integer', 'exists:pengguna,id_pengguna'],
        ]);

        $permintaan->update([
            'status_permintaan' => $request->status_permintaan,
            'id_penyetuju'      => $request->id_penyetuju,
        ]);

        $permintaan->load([
            'pemohon',
            'penyetuju',
            'detailPermintaan.masterBarang',
        ]);

        return response()->json([
            'status'  => true,
            'message' => "Permintaan berhasil {$request->status_permintaan}.",
            'data'    => new PermintaanResource($permintaan),
        ]);
    }

    /**
     * Hapus permintaan (hanya jika masih Menunggu).
     *
     * @OA\Delete(
     *     path="/permintaan/{id}",
     *     operationId="destroyPermintaan",
     *     tags={"Permintaan"},
     *     summary="Hapus permintaan",
     *     description="Menghapus permintaan barang. Hanya permintaan berstatus 'Menunggu' yang dapat dihapus.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID permintaan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Permintaan berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permintaan berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Permintaan tidak ditemukan"),
     *     @OA\Response(
     *         response=422,
     *         description="Permintaan tidak berstatus Menunggu",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Hanya permintaan berstatus Menunggu yang dapat dihapus.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $permintaan = Permintaan::find($id);

        if (!$permintaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan tidak ditemukan.',
            ], 404);
        }

        if ($permintaan->status_permintaan !== 'Menunggu') {
            return response()->json([
                'status'  => false,
                'message' => 'Hanya permintaan berstatus "Menunggu" yang dapat dihapus.',
            ], 422);
        }

        DB::transaction(function () use ($permintaan) {
            $permintaan->detailPermintaan()->delete();
            $permintaan->delete();
        });

        return response()->json([
            'status'  => true,
            'message' => 'Permintaan berhasil dihapus.',
        ]);
    }
}
