<?php

namespace App\Actions\Csv;

use App\Http\Requests\ImportRequest;
use App\Models\User;
use App\Models\Wine;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ParseCsvWineFavoritesUsersAction
{
    public function importCsv(ImportRequest $request)
    {
        $file = $request->file('file');
        $path = $file->getRealPath();

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $warnings = [];

        $chunkSize = 10;
        $batchData = [];

        DB::beginTransaction();
        try {
            foreach ($records as $index => $record) {
                $name = trim($record['ФИО']);
                $phone = preg_replace('/\D/', '', $record['Телефон']);
                $address = trim($record['Адрес']);
                $birthdate = date('Y-m-d', strtotime($record['Дата рождения']));
                $favoriteWines = array_map('trim', explode(',', $record['Любимое вино']));

                if (empty($name) || empty($birthdate)) {
                    $warnings[] = "Пропущены обязательные поля для записи: {$name}.";
                    continue;
                }

                $existingUser = User::where('phone', $phone)->first();
                if ($existingUser) {
                    $warnings[] = "Пользователь с номером {$phone} уже существует.";
                    continue;
                }

                $batchData[] = [
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'birth_date' => $birthdate,
                    'favoriteWines' => $favoriteWines
                ];

                if (count($batchData) >= $chunkSize) {
                    $this->processBatch($batchData, $warnings);
                    $batchData = [];
                }
            }

            // Обработка оставшихся записей
            if (!empty($batchData)) {
                $this->processBatch($batchData, $warnings);
            }

            DB::commit();

            return back()->with('message', 'Импорт успешно выполнен.')->with('warnings', $warnings);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Ошибка при обработке файла: ' . $e->getMessage()]);
        }
    }

    private function processBatch(array $batchData, array &$warnings)
    {
        foreach ($batchData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'birth_date' => $data['birth_date'],
            ]);

            $wineIds = [];
            foreach ($data['favoriteWines'] as $wineName) {
                if (empty($wineName)) continue;
                $wine = Wine::firstOrCreate(['name' => $wineName]);
                $wineIds[] = $wine->id;
            }

            if (!empty($wineIds)) {
                $user->wines()->attach($wineIds);
            }
        }
    }



}