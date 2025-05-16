<?php

namespace App\Services\Legal;

use App\Models\Legal\CourtHearing;
use App\Repositories\Legal\Interfaces\ICourtHearingRepository;
use App\Services\Helpers\Response;
use App\Services\Legal\Interfaces\ICourtHearingService;
use App\Services\ServiceBase;

class CourtHearingService extends ServiceBase implements ICourtHearingService
{
    private ICourtHearingRepository $courtHearingRepo;

    /**
     * CourtHearingService constructor.
     *
     * @param ICourtHearingRepository $courtHearingRepository
     */
    public function __construct(ICourtHearingRepository $courtHearingRepository)
    {
        parent::__construct();
        $this->courtHearingRepo = $courtHearingRepository;
    }

    /**
     * List all the CourtHearings
     *
     * @param string $order
     * @param string $sort
     *
     */
    public function listCourtHearings(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->courtHearingRepo->listCourtHearings($filter, $order, $sort);
    }

    /**
     * Create the CourtHearings
     *
     * @param array $params
     * @return Response
     */
    public function createCourtHearing(array $params)
    {
        $courtHearing = $this->courtHearingRepo->create($params);
        return $this->buildCreateResponse($courtHearing);
    }


    /**
     * Find the CourtHearing by id
     *
     * @param int $id
     *
     * @return CourtHearing
     */
    public function findCourtHearingById(int $id): CourtHearing
    {
        return $this->courtHearingRepo->findOneOrFail($id);
    }

    /**
     * Update CourtHearing
     *
     * @param array $params
     * @param CourtHearing $courtHearing
     * @return Response
     */
    public function updateCourtHearing(array $params, CourtHearing $courtHearing)
    {
        $result = $this->courtHearingRepo->update($params, $courtHearing->id);
        return $this->buildUpdateResponse($courtHearing, $result);
    }

    /**
     * @param CourtHearing $courtHearing
     * @return Response
     */
    public function deleteCourtHearing(CourtHearing $courtHearing)
    {
        $result = $this->courtHearingRepo->delete($courtHearing->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleCourtHearings(array $ids)
    {
        //Declaration
        $result = $this->courtHearingRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
