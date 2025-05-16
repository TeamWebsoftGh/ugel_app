<?php

namespace App\Services\Interfaces;

use App\Models\Communication\Announcement;
use Illuminate\Support\Collection;

interface IAnnouncementService extends IBaseService
{
    public function listAnnouncements(array $params = null, string $order = 'id', string $sort = 'desc');

    public function createAnnouncement(array $params);

    public function findAnnouncementById(int $id) : Announcement;

    public function updateAnnouncement(array $params, Announcement $announcement);

    public function sendQuickSms(array $params);

    public function deleteAnnouncement(Announcement $announcement);
}
