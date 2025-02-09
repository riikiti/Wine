<?php

namespace App\Repositories;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Wine;

class WineRepositories
{
    public function index()
    {
        return Wine::query()->get();
    }

    public function syncWines(User $user, RegisterRequest|UpdateUserRequest $request): void
    {
        $user->wines()->sync($request->wine_ids);
    }

}