<?php

namespace App\Services\Legal;

use App\Models\Legal\CourtCase;
use App\Repositories\Legal\Interfaces\ICourtCaseRepository;
use App\Services\Helpers\Response;
use App\Services\Legal\Interfaces\ICourtCaseService;
use App\Services\ServiceBase;

class CourtCaseService extends ServiceBase implements ICourtCaseService
{
    private ICourtCaseRepository $courtCaseRepo;

    /**
     * CourtCaseService constructor.
     *
     * @param ICourtCaseRepository $courtCaseRepository
     */
    public function __construct(ICourtCaseRepository $courtCaseRepository)
    {
        parent::__construct();
        $this->courtCaseRepo = $courtCaseRepository;
    }

    /**
     * List all the CourtCases
     *
     * @param string $order
     * @param string $sort
     *
     */
    public function listCourtCases(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->courtCaseRepo->listCourtCases($filter, $order, $sort);
    }

    /**
     * Create the CourtCases
     *
     * @param array $params
     * @return Response
     */
    public function createCourtCase(array $params)
    {
        $courtCase = $this->courtCaseRepo->create($params);
        return $this->buildCreateResponse($courtCase);
    }


    /**
     * Find the CourtCase by id
     *
     * @param int $id
     *
     * @return CourtCase
     */
    public function findCourtCaseById(int $id): CourtCase
    {
        return $this->courtCaseRepo->findOneOrFail($id);
    }

    /**
     * Update CourtCase
     *
     * @param array $params
     * @param CourtCase $courtCase
     * @return Response
     */
    public function updateCourtCase(array $params, CourtCase $courtCase)
    {
        $result = $this->courtCaseRepo->update($params, $courtCase->id);
        return $this->buildUpdateResponse($courtCase, $result);
    }

    /**
     * @param CourtCase $courtCase
     * @return Response
     */
    public function deleteCourtCase(CourtCase $courtCase)
    {
        $result = $this->courtCaseRepo->delete($courtCase->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleCourtCases(array $ids)
    {
        //Declaration
        $result = $this->courtCaseRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
