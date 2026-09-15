<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    /**
     * GET api/team-members
     */
    public function index(): JsonResponse
    {
        $teamMembers = TeamMember::with('position')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar anggota tim',
            'data'    => $teamMembers,
        ], 200);
    }

    /**
     * POST api/team-members
     */
    public function store(TeamMemberRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('team-members', 'public');
            $data['photo_url'] = Storage::url($path);
        }

        $teamMember = TeamMember::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Anggota tim berhasil ditambahkan',
            'data'    => $teamMember->load('position'),
        ], 201);
    }

    /**
     * GET api/team-members/{team_member}
     */
    public function show(TeamMember $teamMember): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail anggota tim',
            'data'    => $teamMember->load('position'),
        ], 200);
    }

    /**
     * PUT/POST api/team-members/{team_member}
     */
    public function update(TeamMemberRequest $request, TeamMember $teamMember): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($teamMember->photo_url) {
                $oldPath = str_replace('/storage/', '', $teamMember->photo_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('photo')->store('team-members', 'public');
            $data['photo_url'] = Storage::url($path);
        }

        $teamMember->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data anggota tim berhasil diperbarui',
            'data'    => $teamMember->load('position'),
        ], 200);
    }

    /**
     * DELETE api/team-members/{team_member}
     */
    public function destroy(TeamMember $teamMember): JsonResponse
    {
        if ($teamMember->photo_url) {
            $oldPath = str_replace('/storage/', '', $teamMember->photo_url);
            Storage::disk('public')->delete($oldPath);
        }

        $teamMember->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anggota tim berhasil dihapus',
        ], 200);
    }
}