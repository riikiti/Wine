<?php

namespace App\Actions\Import;

use App\Http\Requests\ImportRequest;
use App\Models\User;
use App\Models\Wine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class ImportCsvWineFavoritesUsersAction extends ImportCsvAction
{

    private function setParams(array $record): array
    {
        $newRecord['name'] = trim($record['ФИО']);
        $newRecord['phone'] = preg_replace('/\D/', '', $record['Телефон']);
        $newRecord['address'] = trim($record['Адрес']);
        $newRecord['birth_date'] = $record['Дата рождения'] ? date('Y-m-d', strtotime($record['Дата рождения'])) : null;
        $newRecord['favoriteWines'] = array_map('trim', explode(',', $record['Любимое вино']));

        return $newRecord;
    }

    public function importCsv(ImportRequest $request)
    {
        $records = $this->getRecords($request);
        $warnings =[];
        DB::beginTransaction();
        try {
            foreach ($records as $index => $record) {
                $newRecord = $this->setParams($record);
                if (empty($newRecord['name']) || empty($newRecord['birth_date'])) {
                    $this->warnings[] = "Пропущены обязательные поля для записи: " . $newRecord['name'] . "(строка {$index}).";
                    continue;
                }
                $existingUser = User::where('phone', $newRecord['phone'])->first();

                if ($existingUser) {
                    $this->attachFavoriteWines($existingUser, $newRecord['favoriteWines']);
                    $warnings[] = "Обновлены данные пользователя с номером " . $newRecord['phone'];
                    continue;
                }
                $batchData[] = [
                    'name' => $newRecord['name'],
                    'phone' => $newRecord['phone'],
                    'address' => $newRecord['address'],
                    'birth_date' => $newRecord['birth_date'],
                    'favoriteWines' => $newRecord['favoriteWines']
                ];

                if (count($batchData) >= $this->chunkSize) {
                    $this->processBatch($batchData,$warnings);
                    $batchData = [];
                }
            }

            if (!empty($batchData)) {
                $this->processBatch($batchData,$warnings);
            }

            DB::commit();

            return back()->with('message', 'Импорт успешно выполнен.')->with('warnings', $warnings);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('db')->error($e->getMessage());
            Log::channel('importcsv')->error("Ошибка при обработке импорта CSV: {$e->getMessage()}");
            return back()->withErrors(['file' => 'Ошибка при обработке файла: ' . $e->getMessage()]);
        }
    }

    private function processBatch(array $batchData, array &$warnings): void
    {
        foreach ($batchData as $data) {
            try {
                $user = User::create([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'birth_date' => $data['birth_date'],
                ]);

                $this->attachFavoriteWines($user, $data['favoriteWines']);
            } catch (\Exception $e) {
                Log::channel('importcsv')->error(
                    "Ошибка добавления пользователя: {$data['name']} - {$e->getMessage()}"
                );
                $warnings[] = "Не удалось добавить пользователя {$data['name']}.";
            }
        }
    }

    private function attachFavoriteWines(User $user, array $favoriteWines): void
    {
        $wineIds = [];
        foreach ($favoriteWines as $wineName) {
            if (empty($wineName)) {
                continue;
            }

            try {
                $wine = Wine::firstOrCreate(['name' => $wineName]);

                if (!$user->wines()->where('wine_id', $wine->id)->exists()) {
                    $wineIds[] = $wine->id;
                }
            } catch (\Exception $e) {
                Log::channel('importcsv')->error(
                    "Ошибка добавления вина '{$wineName}' для пользователя '{$user->name}': {$e->getMessage()}"
                );
            }
        }

        if (!empty($wineIds)) {
            $user->wines()->attach($wineIds);
        }
    }


}