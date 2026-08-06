<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Update the user role.
     */
    public function updateRole(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->id === $request->user()->id && $request->role !== 'admin') {
             return response()->json(['message' => 'Cannot remove your own admin role.'], 403);
        }

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Toggle the user status.
     */
    public function toggleStatus(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->id === $request->user()->id) {
             return response()->json(['message' => 'Cannot toggle your own status.'], 403);
        }

        // Toggle logic based on requirement
        // If Active -> Inactive
        // If Inactive -> Active
        // If Blocked -> Active
        
        $newStatus = 'Active';
        if ($user->status === 'Active') {
            $newStatus = 'Inactive';
        }
        
        $user->status = $newStatus;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Cannot delete your own account.'], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }
}
