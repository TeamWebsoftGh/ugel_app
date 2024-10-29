<?php

namespace App\Services\Interfaces;

use App\Models\Resource\Publication;

interface IPublicationService extends IBaseService
{
    public function listPublications(array $params = null, string $order = 'id', string $sort = 'desc');

    public function createPublication(array $params);

    public function updatePublication(array $params, Publication $Publication);

    public function findCPublicationById(int $id);

    public function deletePublication(Publication $Publication);
}
