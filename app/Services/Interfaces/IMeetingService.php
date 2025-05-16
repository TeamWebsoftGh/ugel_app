<?php

namespace App\Services\Interfaces;

use App\Models\Communication\Meeting;
use Illuminate\Support\Collection;

interface IMeetingService extends IBaseService
{
    public function listMeetings(string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createMeeting(array $params);

    public function findMeetingById(int $id);

    public function updateMeeting(array $params, Meeting $meeting);

    public function deleteMeeting(Meeting $meeting);
}
