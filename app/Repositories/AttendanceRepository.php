<?php

namespace App\Repositories;

use App\Models\Communication\Announcement;
use App\Repositories\Interfaces\IAnnouncementRepository;

class AttendanceRepository extends BaseRepository implements IAnnouncementRepository
{
    /**
     * AnnouncementRepository constructor.
     *
     * @param Announcement $announcement
     */
    public function __construct(Announcement $announcement)
    {
        parent::__construct($announcement);
        $this->model = $announcement;
    }

    /**
     * List all the Announcements
     *
     * @param string $order
     * @param string $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder $announcements
     */
    public function listAnnouncements(array $params = null, string $order = 'id', string $sort = 'desc')
    {
        $result = Announcement::query();

        $ed = $params['filter_end_date']??null;
        $sd = $params['filter_start_date']??null;
        $s = $params['filter_subsidiary']??null;
        $d = $params['filter_department']??null;
        $b = $params['filter_branch']??null;

        if(!user()->can('read-announcements'))
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

        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the Announcement
     *
     * @param array $data
     *
     * @return Announcement
     */
    public function createAnnouncement(array $data): Announcement
    {
        return $this->create($data);
    }

    /**
     * Find the Announcement by id
     *
     * @param int $id
     *
     * @return Announcement
     */
    public function findAnnouncementById(int $id): Announcement
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Announcement
     *
     * @param array $params
     *
     * @param Announcement $announcement
     * @return bool
     */
    public function updateAnnouncement(array $params, Announcement $announcement): bool
    {
        return $announcement->update($params);
    }

    /**
     * @param Announcement $announcement
     * @return bool|null
     * @throws \Exception
     */
    public function deleteAnnouncement(Announcement $announcement)
    {
        return $announcement->delete();
    }
}
