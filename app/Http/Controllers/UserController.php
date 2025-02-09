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
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

/**
 * @tags Пользоавтели
 *
 */
class UserController extends Controller
{
    private UserRepositories $userRepository;
    private WineRepositories $wineRepositories;

    public function __construct(UserRepositories $userRepository, WineRepositories $wineRepositories)
    {
        $this->userRepository = $userRepository;
        $this->wineRepositories = $wineRepositories;
    }

    /**
     * Все пользователи
     *
     * @return \Inertia\Response
     */
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

    /**
     * Создание пользователя в таблицу
     * @param UpdateUserRequest $request
     * @param UserStoreUserAction $action
     * @return RedirectResponse
     */
    public function store(UpdateUserRequest $request, UserStoreUserAction $action): \Illuminate\Http\RedirectResponse
    {
        $action->execute($request);
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    public function edit(User $user): \Inertia\Response
    {
        return Inertia::render('Users/Edit', ['user' => $user]);
    }

    /**
     * Редактирование пользователя в таблице
     * @param UpdateUserRequest $request
     * @param UserUpdateUserAction $action
     * @return RedirectResponse
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UserUpdateUserAction $action
    ): \Illuminate\Http\RedirectResponse {
        $action->execute($request, $user);
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Удаление пользователя в таблице
     * @param User $user
     * @param UserDestroyUserAction $action
     * @return RedirectResponse
     */
    public function destroy(User $user, UserDestroyUserAction $action): \Illuminate\Http\RedirectResponse
    {
        $action->execute($user);
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
