<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'role'     => 'required|in:admin,supervisor_k3,auditor,karyawan',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_validated'] = true; // Auto-validate if created by admin
        $data['is_active'] = true;
        User::create($data);
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
        // Proteksi: Admin utama (id=1) tidak bisa diubah role, dinonaktifkan, atau dihapus
        if ($user->isImmutableAdmin()) {
            $forbiddenChanges = [];
            if ($request->role !== 'admin') {
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
            'role'         => 'required|in:admin,supervisor_k3,auditor,karyawan,pending',
            'is_active'    => 'boolean',
            'is_validated' => 'boolean',
            'password'     => 'nullable|min:8|confirmed',
        ]);

        // Proteksi: tidak bisa mengubah role admin sendiri ke non-admin
        if ($user->id === auth()->id() && $user->role === 'admin' && $data['role'] !== 'admin') {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri dari Admin ke role lain.');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $data['is_validated'] = $request->boolean('is_validated');
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function validateUser(User $user)
    {
        if ($user->role === 'pending') {
            $user->update([
                'role' => 'karyawan',
                'is_validated' => true,
            ]);
        } else {
            $user->update(['is_validated' => true]);
        }
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
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle active/inactive user
     */
    public function toggleActive(User $user)
    {
        if ($user->isImmutableAdmin()) {
            return back()->with('error', 'Admin utama tidak dapat dinonaktifkan.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    /**
     * Reset password user
     */
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