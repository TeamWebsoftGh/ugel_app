<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Medical;
use Illuminate\Support\Collection;

interface IMedicalRepository extends IBaseRepository
{
    public function listMedicals(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createMedical(array $params) : Medical;

    public function findMedicalById(int $id) : Medical;

    public function updateMedical(array $params, Medical $medical) : bool;

    public function deleteMedical(Medical $medical);
}
