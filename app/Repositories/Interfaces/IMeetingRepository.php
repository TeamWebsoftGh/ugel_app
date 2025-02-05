<?php

namespace App\Repositories\Interfaces;

use App\Models\Memo\Meeting;
use Illuminate\Support\Collection;

interface IMeetingRepository extends IBaseRepository
{
    public function listMeetings(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function createMeeting(array $params) : Meeting;

    public function updateMeeting(array $params, Meeting $meeting);

    public function findMeetingById(int $id) : Meeting;

    public function deleteMeeting(Meeting $meeting) : bool;
}
