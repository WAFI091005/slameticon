<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $services = Service::orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar layanan berhasil diambil',
            'data'    => $services,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('services', 'public');
            $data['icon_url'] = Storage::url($path);
        }

        $service = Service::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil ditambahkan',
            'data'    => $service,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail layanan berhasil diambil',
            'data'    => $service,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            if ($service->icon_url) {
                $oldPath = str_replace('/storage/', '', $service->icon_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('icon')->store('services', 'public');
            $data['icon_url'] = Storage::url($path);
        }

        $service->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil diperbarui',
            'data'    => $service,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): JsonResponse
    {
        if ($service->icon_url) {
            $oldPath = str_replace('/storage/', '', $service->icon_url);
            Storage::disk('public')->delete($oldPath);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil dihapus',
        ], 200);
    }
}