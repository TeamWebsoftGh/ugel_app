<?php

namespace App\Repositories\Interfaces;

interface IBaseRepository
{
    public function create(array $attributes);

    public function createMultiple(array $attributes);

    public function count();

    public function update(array $attributes, int $id);

    public function createOrUpdate(array $attributes);

    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'desc');

    public function find(int $id);

    public function findOneOrFail(int $id);

    public function findBy(array $data);

    public function findOneBy(array $data);

    public function findOneByOrFail(array $data);

    public function paginate(array $data, int $perPage = 20);

    public function delete(int $id);

    public function deleteMultipleById(array $ids);
}
