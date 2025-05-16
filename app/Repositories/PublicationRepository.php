<?php

namespace App\Repositories;

use App\Models\Resource\Publication;
use App\Repositories\Interfaces\IPublicationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PublicationRepository extends BaseRepository implements IPublicationRepository
{
    /**
     * PublicationRepository constructor.
     *
     * @param Publication $publication
     */
    public function __construct(Publication $publication)
    {
        parent::__construct($publication);
        $this->model = $publication;
    }

    /**
     * @param array $data
     *
     * @return Publication
     */
    public function createPublication(array $data) : Publication
    {
        return $this->create($data);
    }

    /**
     * @param int $id
     *
     * @return Publication
     * @throws ModelNotFoundException
     */
    public function findPublicationById(int $id) : Publication
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updatePublication(array $params)
    {
        return $this->model->update($params);
    }

    /**
     * @param bool $paginate
     * @param int $perPage
     * @param string $orderBy
     * @param string $sortBy
     *
     * @param array $columns
     * @param array|null $where
     * @return Collection
     */
    public function listPublications(array $params = null, string $order = 'id', string $sort = 'desc')
    {
        $result = Publication::query();

        if (!empty($params['filter_property']))
        {
            $result = $result->Where('property_id', $params['filter_property'])
                ->orWhere('property_id','=',null);
        };

        if (!empty($params['filter_property_type']))
        {
            $result = $result->where('property_type_id', $params['filter_property_type'])
                ->orWhere('property_type_id','=',null);
        };

        return $result->orderBy($order, $sort)->get();
    }

    /**
    * Sync the categories
    *
    * @param array $params
    */
    public function syncCategories(array $params)
    {
        $this->model->categories()->sync($params);
    }


    public function deletePublication(Publication $publication)
    {
        return $this->delete($publication->id);
    }

    /**
     * @param Publication $publication
     * @return bool
     */
    public function deleteCoverImage(Publication $publication) : bool
    {
        Storage::delete('public/'.$publication->cover);
        return $this->update(['cover' => null], $publication->id);
    }
}
