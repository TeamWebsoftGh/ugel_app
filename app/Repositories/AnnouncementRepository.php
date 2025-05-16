<?php

namespace App\Repositories;

use App\Models\Communication\Announcement;
use App\Repositories\Interfaces\IAnnouncementRepository;

class AnnouncementRepository extends BaseRepository implements IAnnouncementRepository
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
        $s = $params['filter_company']??null;

        if(!is_owner())
        {
            $s = user()->company_id;
        }

        $result = $result->when($sd, function ($q, $sd) {
            return $q->where('start_date', '>=', $sd);
        });

        $result = $result->when($ed, function ($q, $ed) {
            return $q->where('start_date', '<=', $ed);
        });

        $result = $result->when($params['filter_property_type'], function ($q, $params) {
            return $q->where('property_type_id', '<=', $params['filter_property_type']);
        });

        $result->when($s, function ($q, $s) {
            return $q->Where('company_id', $s)
                ->orWhere('company_id','=',null);
        });


        return $result->orderBy($order, $sort);
    }

    /**
     * Create the Announcement
     *
     * @param array $data
     *
     * @return Announcement
     */
    public function createAnnouncement(array $data)
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
    public function findAnnouncementById(int $id)
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
