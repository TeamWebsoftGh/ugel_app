<?php

namespace App\Repositories\Legal;

use App\Models\Legal\CourtCase;
use App\Repositories\BaseRepository;
use App\Repositories\Legal\Interfaces\ICourtCaseRepository;

class CourtCaseRepository extends BaseRepository implements ICourtCaseRepository
{
    /**
     * CourtCaseRepository constructor.
     *
     * @param CourtCase $courtCase
     */
    public function __construct(CourtCase $courtCase)
    {
        parent::__construct($courtCase);
        $this->model = $courtCase;
    }

    /**
     * List all the Court Cases
     *
     * @param string $order
     * @param string $sort
     *
     */
    public function listCourtCases(array $filter =[], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = $this->getFilteredList();
        return $query->orderBy($order, $sort);
    }
}
