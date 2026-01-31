<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = User::query();

        // Filter by role (admin or client)
        if ($request->has('role') && $request->role !== 'all') {
            if ($request->role === 'admin') {
                $query->where('is_admin', 1);
            } elseif ($request->role === 'client') {
                $query->where('is_admin', 0);
            }
        }
        // Default: show all users (both admin and client)

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('created_at')->paginate(15);
        
        // Stats - Include all users (admin and client)
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $newThisMonth = User::whereDate('created_at', '>=', now()->startOfMonth())
            ->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();

        return view('Admin.users', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'newThisMonth' => $newThisMonth,
            'verifiedUsers' => $verifiedUsers,
        ]);
    }

    public function create()
    {
        return view('Admin.users-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'is_admin' => 'boolean',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['is_admin'] ?? false,
            'status' => $validated['status'] ?? 'active',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', __('User created successfully'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'is_admin' => 'boolean',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->is_admin = $validated['is_admin'] ?? false;
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', __('User updated successfully'));
    }

    public function toggleStatus(User $user)
    {
        $newStatus = match($user->status) {
            'active' => 'inactive',
            'inactive' => 'active',
            'blocked' => 'active',
            default => 'active',
        };

        $user->status = $newStatus;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => __('User status updated successfully'),
            'status' => $newStatus,
        ]);
    }

    public function block(User $user)
    {
        $user->status = 'blocked';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => __('User blocked successfully'),
            'status' => 'blocked',
        ]);
    }

    public function show(User $user)
    {
        return view('Admin.users-show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        return view('Admin.users-edit', [
            'user' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        // Prevent deleting admin users
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Cannot delete admin users'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('User deleted successfully'));
    }
}
