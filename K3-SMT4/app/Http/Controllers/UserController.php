<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->search) {
            $query->where('name', 'ilike', "%{$request->search}%")
                  ->orWhere('email', 'ilike', "%{$request->search}%");
        }
        if ($request->role) $query->where('role', $request->role);
        $users = $query->latest()->paginate(15)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create() { return view('users.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:super_admin,k3_manager,k3_officer,dept_head,employee,auditor,viewer',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_validated'] = true;
        $data['is_active'] = true;
        $user = User::create($data);

        ActivityLogService::log('user.created', 'users', "User {$user->name} created", $user, [], $user->toArray());

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $oldRole = $user->role;
        $oldActive = $user->is_active;

        // Proteksi: Admin utama (id=1) tidak bisa diubah role, dinonaktifkan, atau dihapus
        if ($user->isImmutableAdmin()) {
            $forbiddenChanges = [];
            if ($request->role !== 'super_admin') {
                $forbiddenChanges[] = 'role';
            }
            if (!$request->boolean('is_active')) {
                $forbiddenChanges[] = 'nonaktif';
            }
            if (!empty($forbiddenChanges)) {
                return back()->with('error', 'Admin utama tidak dapat diubah: ' . implode(', ', $forbiddenChanges) . '.');
            }
        }

        $data = $request->validate([
            'name'         => 'required|max:100',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'role'         => 'required|in:super_admin,k3_manager,k3_officer,dept_head,employee,auditor,viewer',
            'is_active'    => 'boolean',
            'is_validated' => 'boolean',
            'password'     => 'nullable|min:8|confirmed',
        ]);

        // Proteksi: tidak bisa mengubah role admin sendiri ke non-admin
        if ($user->id === auth()->id() && $user->role === 'super_admin' && $data['role'] !== 'super_admin') {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri dari Super Admin ke role lain.');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $data['is_validated'] = $request->boolean('is_validated');
        $user->update($data);

        // Log role changes
        if ($oldRole !== $user->role) {
            ActivityLogService::log('user.role_changed', 'users', "User {$user->name} role changed from {$oldRole} to {$user->role}", $user, ['role' => $oldRole], ['role' => $user->role]);
        }
        if ($oldActive !== $user->is_active) {
            $action = $user->is_active ? 'user.activated' : 'user.deactivated';
            ActivityLogService::log($action, 'users', "User {$user->name} " . ($user->is_active ? 'activated' : 'deactivated'), $user, ['is_active' => $oldActive], ['is_active' => $user->is_active]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function validateUser(User $user)
    {
        $user->update(['is_validated' => true]);
        ActivityLogService::log('user.validated', 'users', "User {$user->name} validated", $user);
        return redirect()->route('users.index')->with('success', 'User ' . $user->name . ' berhasil divalidasi.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        if ($user->isImmutableAdmin()) {
            return back()->with('error', 'Admin utama tidak dapat dihapus.');
        }

        $user->delete();
        ActivityLogService::log('user.deleted', 'users', "User {$user->name} deleted", $user);
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function toggleActive(User $user)
    {
        if ($user->isImmutableAdmin()) {
            return back()->with('error', 'Admin utama tidak dapat dinonaktifkan.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $oldActive = $user->is_active;
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLogService::log($user->is_active ? 'user.activated' : 'user.deactivated', 'users', "User {$user->name} {$status}", $user, ['is_active' => $oldActive], ['is_active' => $user->is_active]);

        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Password user ' . $user->name . ' berhasil direset.');
    }
}