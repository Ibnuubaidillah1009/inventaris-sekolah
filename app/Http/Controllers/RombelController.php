<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRombelRequest;
use App\Http\Requests\UpdateRombelRequest;
use App\Http\Resources\RombelResource;
use App\Models\Rombel;
use Illuminate\Http\JsonResponse;

class RombelController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Rombel::with(['jurusan', 'kelas'])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar rombel berhasil diambil.',
            'data'    => RombelResource::collection($data),
        ]);
    }

    public function store(StoreRombelRequest $request): JsonResponse
    {
        $rombel = Rombel::create($request->validated());
        $rombel->load('jurusan');

        return response()->json([
            'status'  => true,
            'message' => 'Rombel berhasil ditambahkan.',
            'data'    => new RombelResource($rombel),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $rombel = Rombel::with(['jurusan', 'kelas'])->find($id);

        if (!$rombel) {
            return response()->json([
                'status'  => false,
                'message' => 'Rombel tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail rombel berhasil diambil.',
            'data'    => new RombelResource($rombel),
        ]);
    }

    public function update(UpdateRombelRequest $request, string $id): JsonResponse
    {
        $rombel = Rombel::find($id);

        if (!$rombel) {
            return response()->json([
                'status'  => false,
                'message' => 'Rombel tidak ditemukan.',
            ], 404);
        }

        $rombel->update($request->validated());
        $rombel->load('jurusan');

        return response()->json([
            'status'  => true,
            'message' => 'Rombel berhasil diperbarui.',
            'data'    => new RombelResource($rombel),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $rombel = Rombel::find($id);

        if (!$rombel) {
            return response()->json([
                'status'  => false,
                'message' => 'Rombel tidak ditemukan.',
            ], 404);
        }

        $rombel->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Rombel berhasil dihapus.',
        ]);
    }
}
