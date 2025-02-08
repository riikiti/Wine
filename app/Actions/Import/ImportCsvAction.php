<?php

namespace App\Actions\Import;

use App\Http\Requests\ImportRequest;
use App\Models\User;
use App\Models\Wine;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class ImportCsvAction
{
    protected int $chunkSize;


    public function __construct()
    {
        $this->chunkSize = config('app.chunk_size');
    }

    protected function getRecords(ImportRequest $request): \Iterator
    {
        $file = $request->file('file');
        $path = $file->getRealPath();

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        return $csv->getRecords();
    }
    private function setParams(array $record): array
    {
        return [];
    }
    private function processBatch(array $batchData) : void
    {
    }
    private function attachFavoriteWines(User $user, array $favoriteWines): void
    {

    }
}