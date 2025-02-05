<?php

namespace App\Repositories;

use App\Models\Memo\Announcement;
use App\Repositories\Interfaces\IBulkSmsRepository;

class BulkSmsRepository extends BaseRepository implements IBulkSmsRepository
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
     * @return \Illuminate\Database\Eloquent\Collection $announcements
     */
    public function listAnnouncements(array $params = null, string $order = 'id', string $sort = 'desc')
    {
        $result = Announcement::query();

        $ed = $params['filter_end_date']??null;
        $sd = $params['filter_start_date']??null;
        $s = $params['filter_constituency']??null;
        $ps = $params['filter_polling_station']??null;
        $c = $params['filter_company_id']??null;
        $u = $params['filter_user_id']??null;
        $t= $params['filter_type']??null;

//        if(!user()->can('read-global'))
//        {
//            $c = user()->company_id;
//            $u = user()->user_id;
//        }

        $result = $result->when($c, function ($q, $c) {
            return $q->where('company_id', '=', $c);
        });

        $result = $result->when($u, function ($q, $u) {
            return $q->where('created_by', '=', $u);
        });

        $result = $result->when($sd, function ($q, $sd) {
            return $q->where('start_date', '>=', $sd);
        });

        $result = $result->when($ed, function ($q, $ed) {
            return $q->where('start_date', '<=', $ed);
        });

        $result = $result->when($t, function ($q, $t) {
            return $q->where('type', '=', $t);
        });

        $result->when($s, function ($q, $s) {
            return $q->Where('constituency_id', $s)
                ->orWhere('constituency_id','=',null);
        });

        $result->when($ps, function ($q, $ps) {
            return $q->Where('polling_station_id', $ps)
                ->orWhere('polling_station_id','=',null);
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
