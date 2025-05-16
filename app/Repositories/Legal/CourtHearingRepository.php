<?php

namespace App\Repositories\Legal;

use App\Models\Legal\CourtHearing;
use App\Repositories\BaseRepository;
use App\Repositories\Legal\Interfaces\ICourtHearingRepository;

class CourtHearingRepository extends BaseRepository implements ICourtHearingRepository
{
    /**
     * CourtHearingRepository constructor.
     *
     * @param CourtHearing $courtHearing
     */
    public function __construct(CourtHearing $courtHearing)
    {
        parent::__construct($courtHearing);
        $this->model = $courtHearing;
    }

    /**
     * List all the Court Cases
     *
     * @param string $order
     * @param string $sort
     *
     */
    public function listCourtHearings(array $filter =[], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = $this->getFilteredList();
        return $query->orderBy($order, $sort);
    }
}
