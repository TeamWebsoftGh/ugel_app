<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IBranchRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IBranchService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BranchService extends ServiceBase implements IBranchService
{
    use UploadableTrait;

    private IBranchRepository $branchRepo;

    /**
     * BranchService constructor.
     *
     * @param IBranchRepository $branchRepository
     */
    public function __construct(IBranchRepository $branchRepository)
    {
        parent::__construct();
        $this->branchRepo = $branchRepository;
    }

    /**
     * List all the Branches
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listBranches(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->branchRepo->listBranches($order, $sort);
    }

    /**
     * Create the Branchs
     *
     * @param array $params
     * @return Response
     */
    public function createBranch(array $params)
    {
        //Declaration
        $branch = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['branch_name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadOne($params['cover_image'],'branches' , $params['slug']);
            }
            $branch = $this->branchRepo->createBranch($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Branch(), 'create-branch-failed');
        }

        //Check if Branch was created successfully
        if (!$branch)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-branch-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $branch, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $branch;

        return $this->response;
    }


    /**
     * Find the Branch by id
     *
     * @param int $id
     *
     * @return Branch
     */
    public function findBranchById(int $id): Branch
    {
        return $this->branchRepo->findBranchById($id);
    }

    /**
     * Update Branch
     *
     * @param array $params
     * @param Branch $branch
     * @return Response
     */
    public function updateBranch(array $params, Branch $branch)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['branch_name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadOne($params['cover_image'],'branches' , $params['slug']);
            }
            $result = $this->branchRepo->updateBranch($params, $branch);
        } catch (\Exception $e) {
            log_error(format_exception($e), $branch, 'update-branch-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-branch-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $branch, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $branch;

        return $this->response;
    }

    /**
     * @param Branch $branch
     * @return Response
     */
    public function deleteBranch(Branch $branch)
    {
        //Declaration
        $result = false;
        try{
            if (count($branch->employees) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete this Branch. Delete all employees under branch first.";

                return $this->response;
            }

            $result = $this->branchRepo->deleteBranch($branch);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $branch, 'delete-branch-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-branch-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $branch, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
