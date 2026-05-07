<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
                     ->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'role'          => 'required|in:receptionist,biologist,doctor',
            'username'      => 'required|string|unique:users,username|max:50',
            'password'      => 'required|string|min:6',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')
                                 ->store('avatars', 'public');
        }

        $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'role'          => $request->role,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'profile_photo' => $photoPath,
            'is_active'     => true,
        ]);

        Archive::create([
            'model_type'    => 'User',
            'model_id'      => $user->id,
            'action'        => 'created',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $user->toArray(),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'role'          => 'required|in:receptionist,biologist,doctor',
            'username'      => 'required|string|unique:users,username,'.$user->id.'|max:50',
            'password'      => 'nullable|string|min:6',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'role'       => $request->role,
            'username'   => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')
                                              ->store('avatars', 'public');
        }

        $user->update($data);

        Archive::create([
            'model_type'    => 'User',
            'model_id'      => $user->id,
            'action'        => 'updated',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $user->fresh()->toArray(),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        Archive::create([
            'model_type'    => 'User',
            'model_id'      => $user->id,
            'action'        => 'deleted',
            'performed_by'  => auth()->id(),
            'data_snapshot' => $user->toArray(),
        ]);

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
}