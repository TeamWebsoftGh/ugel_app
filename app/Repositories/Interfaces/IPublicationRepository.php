<?php

namespace App\Repositories\Interfaces;

use App\Models\Resource\Publication;

interface IPublicationRepository extends IBaseRepository
{
    public function createPublication(array $attributes);

    public function findPublicationById(int $id);

    public function listPublications(array $params = null, string $order = 'id', string $sort = 'desc');

    public function updatePublication(array $params);

    public function deletePublication(Publication $Publication);

    public function deleteCoverImage(Publication $Publication) : bool;
}
