<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Termination;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface ITerminationRepository extends IBaseRepository
{
    public function findTerminationById(int $id);

    public function listTerminations(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createTermination(array $params);

    public function updateTermination(array $params, Termination $termination);

    public function deleteTermination(Termination $termination);
}
