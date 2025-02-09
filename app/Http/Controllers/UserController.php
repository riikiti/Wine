<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Wine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(): \Inertia\Response
    {
        $users = User::with('wines')->get();
        $wines = Wine::query()->get();
        return Inertia::render('Users/Index', [
            'wines' => $wines,
            'users' => $users,
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(RegisterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        $user->wines()->sync($request->wine_ids);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    public function edit(User $user): \Inertia\Response
    {
        return Inertia::render('Users/Edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $user->fill($data)->save();

        $user->wines()->sync($request->wine_ids);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }


    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
