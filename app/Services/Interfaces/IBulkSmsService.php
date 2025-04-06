<?php

namespace App\Services\Interfaces;

use App\Models\Memo\Announcement;
use Illuminate\Support\Collection;

interface IBulkSmsService extends IBaseService
{
    public function listAnnouncements(array $filter = [], string $order = 'id', string $sort = 'desc');

    public function createAnnouncement(array $params);

    public function sendQuickSms(array $params);

    public function findAnnouncementById(int $id) : Announcement;

    public function updateAnnouncement(array $params, Announcement $announcement);

    public function deleteAnnouncement(Announcement $announcement);
}
