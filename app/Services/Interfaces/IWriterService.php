<?php

namespace App\Services\Interfaces;

use App\Models\ApplicationWriter;
use App\Models\Branch;
use App\Models\Writer;
use http\Env\Request;
use Illuminate\Support\Collection;

interface IWriterService extends IBaseService
{
    public function listWriters(string $order = 'id', string $sort = 'desc'): Collection;

    public function listAvailableWriters(string $order = 'id', string $sort = 'desc'): Collection;

    public function createWriter(array $params);

    public function findWriterById(int $id) : Writer;

    public function updateWriter(array $params, Writer $writer);

    public function changePassword(array $params, Writer $writer);

    public function changeStatus(bool $status, Writer $writer);

    public function resetPassword(Writer $writer);

    public function confirmAccount(Writer $writer);

    public function deleteWriter(Writer $writer);

    public function getCreateWriter(array $request);
}
