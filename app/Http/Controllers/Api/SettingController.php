<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Ambil semua data settings (Format Key-Value Object atau Array)
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all()->pluck('value', 'key');

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengaturan berhasil diambil',
            'data'    => $settings,
        ], 200);
    }

    /**
     * Ambil single setting berdasarkan Key
     */
    public function show(string $key): JsonResponse
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaturan tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengaturan berhasil diambil',
            'data'    => $setting,
        ], 200);
    }

    /**
     * Update atau Create Settings (Mendukung Single dan Bulk Update)
     */
    public function updateOrCreate(SettingRequest $request): JsonResponse
    {
        $data = $request->validated();

        // 1. Opsi Bulk Update (Menerima array of settings)
        if ($request->has('settings')) {
            foreach ($data['settings'] as $item) {
                Setting::updateOrCreate(
                    ['key' => $item['key']],
                    ['value' => $item['value'] ?? null]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Beberapa pengaturan berhasil diperbarui',
                'data'    => Setting::all()->pluck('value', 'key'),
            ], 200);
        }

        // 2. Opsi Single Update
        $setting = Setting::updateOrCreate(
            ['key' => $data['key']],
            ['value' => $data['value'] ?? null]
        );

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui',
            'data'    => $setting,
        ], 200);
    }

    /**
     * Hapus setting berdasarkan key
     */
    public function destroy(string $key): JsonResponse
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaturan tidak ditemukan',
            ], 404);
        }

        $setting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil dihapus',
        ], 200);
    }
}