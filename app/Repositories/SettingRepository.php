<?php

namespace App\Repositories;

use App\Models\Settings\Configuration;
use App\Repositories\Interfaces\ISettingRepository;
use Illuminate\Support\Collection;

class SettingRepository extends BaseRepository implements ISettingRepository
{
    /**
     * Setting Repository
     *
     * @param Configuration $setting
     */
    public function __construct(Configuration $setting)
    {
        parent::__construct($setting);
        $this->model = $setting;
    }

    /**
     * List all Settings
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listSettings(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return Configuration
     */
    public function createSetting(array $data): Configuration
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return Configuration
     */
    public function findSettingById(int $id): Configuration
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateSetting(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteSetting(int $id): bool
    {
        return $this->delete($id);
    }
}
