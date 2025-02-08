<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Models\User;
use App\Models\Wine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function import(ImportRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedRequest = $request->validated();
        $path = $validatedRequest->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $data = [];

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data[] = [
                'name' => $row[0],
                'phone' => $row[1],
                'birth_date' => Carbon::createFromFormat('d.m.Y', $row[2])->format('Y-m-d'),
                'address' => $row[3],
                'wine_name' => $row[4],
            ];
        }
        fclose($file);
        $errors = [];
        foreach ($data as $userData) {
            try {
                $wine = Wine::firstOrCreate(['name' => strtolower(trim($userData['wine_name']))]);
                User::create([
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'birth_date' => $userData['birth_date'],
                    'address' => $userData['address'],
                    'wine_id' => $wine->id,
                ]);
            } catch (\Exception $e) {
                $errors[] = $userData;
            }
        }

        return back()->with('success', 'Импорт завершён')->with('errors', $errors);
    }

}
