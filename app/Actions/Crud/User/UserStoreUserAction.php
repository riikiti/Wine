<?php

namespace App\Actions\Crud\User;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;

class UserStoreUserAction extends UserAction
{

    public function execute(UpdateUserRequest $request): void
    {
        $user = $this->userRepository->store($request);
        $this->wineRepositories->syncWines($user, $request);
    }
}