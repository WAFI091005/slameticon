<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityLogRequest;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs with filter & pagination.
     */
    public function index(ActivityLogRequest $request): JsonResponse
    {
        $query = ActivityLog::with(['user:id,name,email', 'target'])
            ->latest();

        // Filter berdasarkan pencarian deskripsi
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan aksi (misal: 'create', 'update', 'login')
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan ID user/aktor
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $perPage = $request->input('per_page', 15);
        $logs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar log aktivitas berhasil diambil',
            'data'    => $logs,
        ], 200);
    }

    /**
     * Display the specified activity log.
     */
    public function show(string $id): JsonResponse
    {
        $log = ActivityLog::with(['user:id,name,email', 'target'])->find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log aktivitas tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail log aktivitas berhasil diambil',
            'data'    => $log,
        ], 200);
    }

    /**
     * Optional: Clear/Delete old activity logs (Khusus Admin Utama).
     */
    public function destroy(string $id): JsonResponse
    {
        $log = ActivityLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log aktivitas tidak ditemukan',
            ], 404);
        }

        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Log aktivitas berhasil dihapus',
        ], 200);
    }
}