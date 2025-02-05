<?php

namespace App\Repositories;

use App\Models\Client\ClientType;
use App\Repositories\Interfaces\IClientTypeRepository;
use Illuminate\Support\Collection;

class ClientTypeRepository extends BaseRepository implements IClientTypeRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param ClientType $clientType
     */
    public function __construct(ClientType $clientType)
    {
        parent::__construct($clientType);
        $this->model = $clientType;
    }

    /**
     * Find the ClientType by id
     *
     * @param int $id
     *
     * @return ClientType
     */
    public function findClientTypeById(int $id): ClientType
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return ClientType
     */
    public function createClientType(array $data) : ClientType
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param ClientType $clientType
     * @return bool
     */
    public function updateClientType(array $data, ClientType $clientType) : bool
    {
        return $this->update($data, $clientType->id);
    }

    /**
     * @param ClientType $clientType
     * @return bool
     */
    public function deleteClientType(ClientType $clientType) : bool
    {
        return $this->delete($clientType->id);
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listClientTypes(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->getFilteredList($filter);

        return $result->orderBy($order, $sort)->get($columns);
    }
}
