<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\FinancialYear;
use App\Models\PayPeriod;
use App\Repositories\Interfaces\IComplaintRepository;
use App\Repositories\Interfaces\IFinancialYearRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IFinancialYearService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FinancialYearService extends ServiceBase implements IFinancialYearService
{
    private $financialYearRepo;

    /**
     * FinancialYearService constructor.
     * @param IFinancialYearRepository $financialYear
     */
    public function __construct(IFinancialYearRepository $financialYear)
    {
        parent::__construct();
        $this->financialYearRepo = $financialYear;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listFinancialYears(string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->financialYearRepo->listFinancialYears($orderBy, $sortBy);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createFinancialYear(array $data)
    {
        //Declaration
        $financialYear = null;

        try{
            //Process Request
            $s_date = Carbon::createFromFormat(env('Date_Format'), $data['start_date']);
            $e_date = Carbon::createFromFormat(env('Date_Format'), $data['end_date']);
            $som =  $s_date->copy()->startOfMonth();
            $eom   = $e_date->copy()->lastOfMonth();

            if(isset($data['pay_type']) && $data['pay_type'] == 'weekly')
            {
                $interval = '1 week';
            }else{
                $interval = '1 month';
            }

            $period = CarbonPeriod::create($som, $interval, $eom);

            $data['name'] = $s_date->format('Y').' Financial Year';
            $data['year'] = $s_date->format('Y');
            $data['pay_period'] = count($period);
            $data['pay_type'] = 'monthly';
            $data['company_id'] = company_id();;

            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $financialYear = $this->financialYearRepo->createOrUpdateFinancialYear($data);

            foreach ($period as $dt) {
                $financialYear->payPeriods()->updateOrCreate(
                    [
                        'pay_month' => $dt->format("F").' '.$dt->format("Y"),
                        'company_id' => company_id(),
                    ],
                    [
                        'start_date' => Carbon::parse($dt->copy()->firstOfMonth())->format(env('Date_Format')),
                        'end_date' => Carbon::parse($dt->copy()->lastOfMonth())->format(env('Date_Format')),
                        'pay_month' => $dt->format("F").' '.$dt->format("Y"),
                        'company_id' => company_id(),
                    ]
                );
            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), new FinancialYear(), 'create-financial-year-failed');
       }

        //Check if Successful
        if ($financialYear == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-financial-year-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $financialYear, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $financialYear;

        return $this->response;
    }


    /**
     * @param array $data
     * @param FinancialYear $financialYear
     * @return Response
     */
    public function updateFinancialYear(array $data, FinancialYear $financialYear)
    {
        //Declaration
        $result = false;

        try{
            if(isset($data['status']))
                $data['is_active'] = $data['status'];
            $result = $this->financialYearRepo->updateFinancialYear($data, $financialYear);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $financialYear, 'update-financial-year-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-financial-year-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $financialYear, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $financialYear;

        return $this->response;
    }

    /**
     * @param int $id
     * @return FinancialYear|null
     */
    public function findFinancialYearById($id): ?FinancialYear
    {
        return $this->financialYearRepo->findFinancialYearById($id);
    }

    /**
     * @param FinancialYear $financialYear
     * @return Response
     */
    public function deleteFinancialYear(FinancialYear $financialYear)
    {
        //Declaration
        $result = false;

        try{
            $financialYear->payPeriods()->delete();
            $result = $this->financialYearRepo->deleteFinancialYear($financialYear);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $financialYear, 'delete-financial-year-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-financial-year-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $financialYear, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function payPeriods($id)
    {
        return PayPeriod::all()->where('financial_year_id', $id);
    }

    /**
     * @param bool $status
     * @param User $user
     * @return Response
     */
    public function changePayPeriodStatus(bool $status, PayPeriod $payPeriod)
    {
        //Declaration
        $response = false;

        //Process Request
        try {
            $response = $payPeriod->update(['is_open' => $status, 'is_active' => 1]);

        } catch (\Exception $e) {
            log_error(format_exception($e), $payPeriod, "change-status-failed");
        }

        if (!$response)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        $changeAction = $status?"Open":"Close";

        //Audit Trail
        $logAction = "change-status-successful";
        $auditMessage = $changeAction.' pay period successful.';

        log_activity($auditMessage, $payPeriod, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
