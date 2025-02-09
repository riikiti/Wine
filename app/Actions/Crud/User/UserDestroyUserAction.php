<?php

namespace App\Actions\Crud\User;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class UserDestroyUserAction extends UserAction
{

    public function execute(User $user): void
    {
        $this->userRepository->destroy($user);
    }
}