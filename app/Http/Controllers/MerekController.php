<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerekRequest;
use App\Http\Requests\UpdateMerekRequest;
use App\Http\Resources\MerekResource;
use App\Models\Merek;
use Illuminate\Http\JsonResponse;

class MerekController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => 'Daftar merek berhasil diambil.',
            'data'    => MerekResource::collection(Merek::all()),
        ]);
    }

    public function store(StoreMerekRequest $request): JsonResponse
    {
        $merek = Merek::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Merek berhasil ditambahkan.',
            'data'    => new MerekResource($merek),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $merek = Merek::find($id);

        if (!$merek) {
            return response()->json([
                'status'  => false,
                'message' => 'Merek tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail merek berhasil diambil.',
            'data'    => new MerekResource($merek),
        ]);
    }

    public function update(UpdateMerekRequest $request, string $id): JsonResponse
    {
        $merek = Merek::find($id);

        if (!$merek) {
            return response()->json([
                'status'  => false,
                'message' => 'Merek tidak ditemukan.',
            ], 404);
        }

        $merek->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Merek berhasil diperbarui.',
            'data'    => new MerekResource($merek),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $merek = Merek::find($id);

        if (!$merek) {
            return response()->json([
                'status'  => false,
                'message' => 'Merek tidak ditemukan.',
            ], 404);
        }

        $merek->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Merek berhasil dihapus.',
        ]);
    }
}
