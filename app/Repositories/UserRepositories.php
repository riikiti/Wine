<?php

namespace App\Repositories;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Wine;

class UserRepositories
{
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return User::with('wines')->get();
    }

    public function store(RegisterRequest $request)
    {
        $data = $request->validated();
        return User::create($data);
    }

    public function update(UpdateUserRequest $request, User $user): User
    {
        $data = $request->validated();
        $user->fill($data)->save();
        return $user;
    }

    public function destroy(User $user): void
    {
        $user->delete();
    }

}