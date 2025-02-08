<?php

namespace App\Http\Controllers;

use App\Actions\Import\ImportCsvWineFavoritesUsersAction;
use App\Http\Requests\ImportRequest;
use App\Models\User;
use App\Models\Wine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use League\Csv\Reader;

class UserController extends Controller
{
    public function importCsv(ImportRequest $request,ImportCsvWineFavoritesUsersAction $action): void
    {
       $action->importCsv($request);
    }

}