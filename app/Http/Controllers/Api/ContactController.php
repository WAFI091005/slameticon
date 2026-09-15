<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactStatusRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts (Admin/Protected).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Contact::orderBy('submitted_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $contacts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesan kontak berhasil diambil',
            'data'    => $contacts,
        ], 200);
    }

    /**
     * Store a newly created contact message (Public).
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['status'] = 'unread';
        $data['submitted_at'] = now();

        $contact = Contact::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda berhasil dikirim',
            'data'    => $contact,
        ], 201);
    }

    /**
     * Display the specified contact (Admin/Protected).
     */
    public function show(Contact $contact): JsonResponse
    {
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pesan kontak berhasil diambil',
            'data'    => $contact,
        ], 200);
    }

    /**
     * Update contact status (Admin/Protected).
     */
    public function updateStatus(UpdateContactStatusRequest $request, Contact $contact): JsonResponse
    {
        $contact->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesan kontak berhasil diperbarui',
            'data'    => $contact,
        ], 200);
    }

    /**
     * Remove the specified contact from storage (Admin/Protected).
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesan kontak berhasil dihapus',
        ], 200);
    }
}