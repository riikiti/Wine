<?php

namespace App\Actions\Crud\User;

use App\Http\Requests\RegisterRequest;

class UserStoreUserAction extends UserAction
{

    public function execute(RegisterRequest $request): void
    {
        $user = $this->userRepository->store($request);
        $this->wineRepositories->syncWines($user, $request);
    }
}