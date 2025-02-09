<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('wines')->get();
        $wines = Wine::query()->get();
        return Inertia::render('Users/Index', [
            'wines' => $wines,
            'users' => $users,
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|unique:users,phone',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'email' => 'nullable|unique:users,email|email',
            'password' => 'nullable|string|min:6',
            'wine_ids' => 'array',
            'wine_ids.*' => 'exists:wines,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->wines()->sync($request->wine_ids);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|unique:users,phone,' . $user->id,
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'email' => 'nullable|unique:users,email,' . $user->id . '|email',
            'password' => 'nullable|string|min:6',
            'wine_ids' => 'array',
            'wine_ids.*' => 'exists:wines,id',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        $user->wines()->sync($request->wine_ids);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
