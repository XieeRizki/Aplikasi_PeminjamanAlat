<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show user list
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('pages.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|min:3',
            'password' => 'required|string|min:6',
            'level' => 'required|in:admin,petugas,peminjam',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'level' => $validated['level'],
        ]);

        LogAktivitas::createLog(
            auth()->id(),
            "Menambah user: {$validated['username']} ({$validated['level']})",
            'User'
        );

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->user_id . ',user_id|min:3',
            'password' => 'nullable|string|min:6',
            'level' => 'required|in:admin,petugas,peminjam',
        ]);

        $updateData = [
            'username' => $validated['username'],
            'level' => $validated['level'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        LogAktivitas::createLog(
            auth()->id(),
            "Mengupdate user: {$validated['username']}",
            'User'
        );

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $username = $user->username;
        $user->delete();

        LogAktivitas::createLog(
            auth()->id(),
            "Menghapus user: {$username}",
            'User'
        );

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}