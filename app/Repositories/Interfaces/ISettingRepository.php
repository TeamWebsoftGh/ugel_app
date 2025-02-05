<?php

namespace App\Repositories\Interfaces;

use App\Models\Settings\Configuration;
use Illuminate\Support\Collection;

interface ISettingRepository extends IBaseRepository
{
    public function listSettings(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createSetting(array $params) : Configuration;

    public function findSettingById(int $id) : Configuration;

    public function updateSetting(array $params, int $id) : bool;

    public function deleteSetting(int $id);
}
