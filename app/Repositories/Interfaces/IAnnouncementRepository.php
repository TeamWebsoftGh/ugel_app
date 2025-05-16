<?php

namespace App\Repositories\Interfaces;

use App\Models\Communication\Announcement;

interface IAnnouncementRepository extends IBaseRepository
{
    public function listAnnouncements(array $params = null, string $order = 'id', string $sort = 'desc');

    public function createAnnouncement(array $params);

    public function findAnnouncementById(int $id) ;

    public function updateAnnouncement(array $params, Announcement $announcement) : bool;

    public function deleteAnnouncement(Announcement $announcement);
}
