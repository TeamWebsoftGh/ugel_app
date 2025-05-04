<?php

namespace App\Repositories;

use App\Models\Communication\Announcement;
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
        $result = $this->getFilteredList();

        $ed = $params['filter_end_date']??null;
        $sd = $params['filter_start_date']??null;
        $p = $params['filter_property']??null;
        $pt = $params['filter_property_type']??null;
        $ct = $params['filter_customer_type']??null;
        $u = $params['filter_user_id']??null;
        $t= $params['filter_type']??null;

//        if(!user()->can('read-global'))
//        {
//            $c = user()->company_id;
//            $u = user()->user_id;
//        }

        $result = $result->when($u, function ($q, $u) {
            return $q->where('created_by', '=', $u);
        });

        $result = $result->when($sd, function ($q, $sd) {
            return $q->where('created_at', '>=', $sd);
        });

        $result = $result->when($ed, function ($q, $ed) {
            return $q->where('created_at', '<=', $ed);
        });

        $result = $result->when($t, function ($q, $t) {
            return $q->where('type', '=', $t);
        });

        $result->when($p, function ($q, $p) {
            return $q->Where('property_id', $p);
                //->orWhere('property_id','=',null);
        });

        $result->when($ct, function ($q, $ct) {
            return $q->Where('client_type_id', $ct);
                //->orWhere('client_type_id','=',null);
        });

        $result->when($pt, function ($q, $pt) {
            return $q->Where('property_type_id', $pt);
                //->orWhere('property_type_id','=',null);
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
