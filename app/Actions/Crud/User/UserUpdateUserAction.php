<?php

namespace App\Actions\Crud\User;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserUpdateUserAction  extends UserAction
{

    public function execute(UpdateUserRequest $request, User $user): void
    {
        $user = $this->userRepository->update($request, $user);
        $this->wineRepositories->syncWines($user, $request);
    }
}