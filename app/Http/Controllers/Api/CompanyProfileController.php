<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyProfileRequest;
use App\Models\CompanyProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    /**
     * GET api/company-profile (Ambil data profil utama)
     */
    public function show(): JsonResponse
    {
        $profile = CompanyProfile::first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Data profil perusahaan belum diatur',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail profil perusahaan',
            'data'    => $profile,
        ], 200);
    }

    /**
     * POST/PUT api/company-profile (Simpan atau Perbarui profil perusahaan)
     */
    public function updateOrCreate(CompanyProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        $profile = CompanyProfile::first();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($profile && $profile->logo_url) {
                $oldPath = str_replace('/storage/', '', $profile->logo_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('logo')->store('company-profile', 'public');
            $data['logo_url'] = Storage::url($path);
        }

        if ($profile) {
            $profile->update($data);
            $message = 'Profil perusahaan berhasil diperbarui';
        } else {
            $profile = CompanyProfile::create($data);
            $message = 'Profil perusahaan berhasil dibuat';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $profile,
        ], 200);
    }
}