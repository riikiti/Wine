<?php

namespace App\Actions\Crud\User;

use App\Repositories\UserRepositories;
use App\Repositories\WineRepositories;

class UserAction
{
    protected UserRepositories $userRepository;
    protected WineRepositories $wineRepositories;

    public function __construct(UserRepositories $userRepository, WineRepositories $wineRepositories)
    {
        $this->userRepository = $userRepository;
        $this->wineRepositories = $wineRepositories;
    }


}