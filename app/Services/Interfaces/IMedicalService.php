<?php

namespace App\Services\Interfaces;

use App\Models\Property\Medical;
use Illuminate\Support\Collection;

interface IMedicalService extends IBaseService
{
    public function listMedicals(array $filter, string $order = 'id', string $sort = 'desc') : Collection;

    public function createMedical(array $params);

    public function updateMedical(array $params, Medical $medical);

    public function findMedicalById(int $id);

    public function deleteMedical(Medical $medical);
}
