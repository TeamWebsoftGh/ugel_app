<?php

namespace App\Services\Interfaces;

use App\Models\Settings\Configuration;
use Illuminate\Support\Collection;

interface ISettingService extends IBaseService
{
    public function listSettings(string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createUpdateSetting(array $params);

    public function findSettingById(int $id);

    public function findSettingBySlug(string $slug);

    public function updateSetting(array $params, Configuration $setting);

    public function deleteSetting(Configuration $setting);

    public function getRecords($keys);

    public function getEnv($key);

    public function updateEnvKeys($keys);
}
