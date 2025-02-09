<?php

namespace App\Http\Controllers;

use App\Actions\Crud\User\UserDestroyUserAction;
use App\Actions\Crud\User\UserStoreUserAction;
use App\Actions\Crud\User\UserUpdateUserAction;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Wine;
use App\Repositories\UserRepositories;
use App\Repositories\WineRepositories;
use Inertia\Inertia;

class UserController extends Controller
{
    private UserRepositories $userRepository;
    private WineRepositories $wineRepositories;

    public function __construct(UserRepositories $userRepository, WineRepositories $wineRepositories)
    {
        $this->userRepository = $userRepository;
        $this->wineRepositories = $wineRepositories;
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Users/Index', [
            'wines' => $this->wineRepositories->index(),
            'users' => $this->userRepository->index(),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(RegisterRequest $request, UserStoreUserAction $action): \Illuminate\Http\RedirectResponse
    {
        $action->execute($request);
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    public function edit(User $user): \Inertia\Response
    {
        return Inertia::render('Users/Edit', ['user' => $user]);
    }

    public function update(
        UpdateUserRequest $request,
        User $user,
        UserUpdateUserAction $action
    ): \Illuminate\Http\RedirectResponse {
        $action->execute($request, $user);
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }


    public function destroy(User $user, UserDestroyUserAction $action): \Illuminate\Http\RedirectResponse
    {
        $action->execute();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
