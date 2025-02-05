<?php

namespace App\Repositories;

use App\Models\Memo\Meeting;
use App\Repositories\Interfaces\IMeetingRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class MeetingRepository extends BaseRepository implements IMeetingRepository
{
    /**
     * MeetingRepository constructor.
     * @param Meeting $meeting
     */
    public function __construct(Meeting $meeting)
    {
        parent::__construct($meeting);
        $this->model = $meeting;
    }

    /**
     * List all the Meetings
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return Collection
     */
    public function listMeetings(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        $result = Meeting::query();

        $ed = $params['filter_end_date']??null;
        $sd = $params['filter_start_date']??null;
        $s = $params['filter_subsidiary']??null;
        $d = $params['filter_department']??null;
        $b = $params['filter_branch']??null;

        if(!user()->can('read-service-types'))
        {
            $d = user()->department_id;
            $s = user()->subsidiary_id;
            $b = user()->branch_id;
        }

        $result = $result->when($sd, function ($q, $sd) {
            return $q->where('start_date', '>=', $sd);
        });

        $result = $result->when($ed, function ($q, $ed) {
            return $q->where('start_date', '<=', $ed);
        });

        $result->when($s, function ($q, $s) {
            return $q->Where('subsidiary_id', $s)
                ->orWhere('subsidiary_id','=',null);
        });

        $result->when($d, function ($q, $d) {
            return $q->Where('department_id', $d)
                ->orWhere('department_id','=',null);
        });

        $result->when($b, function ($q, $b) {
            return $q->Where('branch_id', $b)
                ->orWhere('branch_id','=',null);
        });

        return $result->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * Create the Meeting
     *
     * @param array $params
     *
     * @return Meeting
     */
    public function createMeeting(array $params) : Meeting
    {
        return $this->create($params);
    }

    /**
     * Update the Meeting
     *
     * @param array $params
     *
     * @param Meeting $meeting
     */
    public function updateMeeting(array $params, Meeting $meeting) : bool
    {
        return $this->update($params, $meeting->id);;
    }

    /**
     * @param int $id
     * @return Meeting
     * @throws ModelNotFoundException
     */
    public function findMeetingById(int $id) : Meeting
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Delete a Meeting
     *
     * @param Meeting $meeting
     * @return bool
     * @throws Exception
     */
    public function deleteMeeting(Meeting $meeting) : bool
    {
        return $this->delete($meeting->id);
    }
}
