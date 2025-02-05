<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Delegate\Delegate;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IDelegateRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IDelegateService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DelegateService extends ServiceBase implements IDelegateService
{
    use UploadableTrait;

    private IDelegateRepository $delegateRepo;

    /**
     * DelegateService constructor.
     *
     * @param IDelegateRepository $delegateRepository
     */
    public function __construct(IDelegateRepository $delegateRepository)
    {
        parent::__construct();
        $this->delegateRepo = $delegateRepository;
    }

    /**
     * List all the Delegates
     *
     * @param array|null $filter
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listDelegates(array $filter = null, string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->delegateRepo->listDelegates($filter, $order, $sort);
    }

    /**
     * Create the Delegate
     *
     * @param array $params
     * @return Response
     */
    public function createDelegate(array $params)
    {
        //Declaration
        $delegate = null;

        //Process Request
        try {
            $delegate = $this->delegateRepo->createDelegate($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Delegate(), 'create-delegate-failed');
        }

        //Check if Delegate was created successfully
        if (!$delegate)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-delegate-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $delegate, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $delegate;

        return $this->response;
    }

    /**
     * Find the Delegate by id
     *
     * @param int $id
     *
     * @return Delegate
     */
    public function findDelegateById(int $id): Delegate
    {
        return $this->delegateRepo->findDelegateById($id);
    }

    /**
     * Update Delegate
     *
     * @param array $params
     * @param Delegate $delegate
     * @return Response
     */
    public function updateDelegate(array $params, Delegate $delegate)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->delegateRepo->updateDelegate($params, $delegate);
        } catch (\Exception $e) {
            log_error(format_exception($e), $delegate, 'update-delegate-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-delegate-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $delegate, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $delegate;

        return $this->response;
    }

    /**
     * @param Delegate $delegate
     * @return Response
     */
    public function deleteDelegate(Delegate $delegate)
    {
        //Declaration
        $result = false;
        try {
            if (count($delegate->subDelegates) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

                return $this->response;
            }

            $result = $this->delegateRepo->deleteDelegate($delegate);
        } catch (\Exception $ex) {
            log_error(format_exception($ex), $delegate, 'delete-delegate-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-delegate-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $delegate, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
