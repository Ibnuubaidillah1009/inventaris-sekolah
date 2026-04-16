<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSatuanRequest;
use App\Http\Requests\UpdateSatuanRequest;
use App\Http\Resources\SatuanResource;
use App\Models\Satuan;
use Illuminate\Http\JsonResponse;

class SatuanController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => 'Daftar satuan berhasil diambil.',
            'data'    => SatuanResource::collection(Satuan::all()),
        ]);
    }

    public function store(StoreSatuanRequest $request): JsonResponse
    {
        $satuan = Satuan::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Satuan berhasil ditambahkan.',
            'data'    => new SatuanResource($satuan),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status'  => false,
                'message' => 'Satuan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail satuan berhasil diambil.',
            'data'    => new SatuanResource($satuan),
        ]);
    }

    public function update(UpdateSatuanRequest $request, string $id): JsonResponse
    {
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status'  => false,
                'message' => 'Satuan tidak ditemukan.',
            ], 404);
        }

        $satuan->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Satuan berhasil diperbarui.',
            'data'    => new SatuanResource($satuan),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status'  => false,
                'message' => 'Satuan tidak ditemukan.',
            ], 404);
        }

        $satuan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Satuan berhasil dihapus.',
        ]);
    }
}
