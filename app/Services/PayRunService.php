<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Employee;
use App\Models\PayBenefit;
use App\Models\PayBenefitDetail;
use App\Models\PayPeriod;
use App\Models\PayProcessing;
use App\Models\PayProcessingLog;
use App\Models\PaySummary;
use App\Models\Traits\PayrollTrait;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPayRunService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PayRunService extends ServiceBase implements IPayRunService
{
    use PayrollTrait;

    private $payBenefitRepo;

    /**
     * PayRunService constructor.
     *
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * List all the PayBenefits
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPaySummaries(int $payPeriodId, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return  PaySummary::where('pay_period_id', $payPeriodId)->get($columns);
    }


    /**
     * Create PayBenefit
     *
     * @param array $params
     *
     * @return Response
     */
    public function runPay(array $params)
    {
        //Declaration
        $is_valid = false;
        $payProcessing = null;
        $valid = 0;
        $invalid = 0;
        $employees = Employee::pluck("id");

        //Process Request
        try {
            DB::beginTransaction();
            $payPeriod = PayPeriod::findorFail($params['pay_period_id']);
            $data['status'] = "pending";
            $data['user_id'] = user()->id;
            $data['company_id'] = company_id();

            $data['pay_period_id'] = $payPeriod->id;
            $data['total_records'] = count($employees);
            $payProcessing = PayProcessing::create($data);
            foreach ($employees as $id)
            {
                $log = new PayProcessingLog();
                $employee = Employee::find($id);
                $result = $this->listPaySummaries($payPeriod->id)->where('employee_id', $id);

                $log->employee_id = $employee->id;
                $log->full_name = $employee->FullName;
                $log->staff_id = $employee->staff_id;
                $log->pay_processing_id = $payProcessing->id;
                $log->pay_period_id = $payPeriod->id;
                $log->company_id = company_id();

                if($employee != null && count($result) < 1)
                {
                    try {
                        $this->ProcessPay($employee, $payPeriod, $payProcessing->id);
                        $valid++;
                    }catch (\Exception $ex){
                        log_error(format_exception($ex), new PaySummary(), 'run-pay-failed');
                        $log->error_message = $ex->getMessage();
                        $invalid++;
                    }
                }else{
                    $log->error_message = "Pay already processed for ".$employee->FullName;
                }
                $log->save();
            }
            $payProcessing->status = "complete";
            $payProcessing->successful_records = $valid;
            $payProcessing->failed_records = $invalid;
            $payProcessing->save();
            DB::commit();
            $is_valid = true;
        } catch (\Exception $e) {
            DB::rollback();
            log_error(format_exception($e), new PayProcessing(), 'run-pay-failed');
        }

        //Check if pay-benefit was created successfully
        if (!$is_valid)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'run-pay-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS.". ".$valid." records successful ".$invalid." failed.";

        log_activity($auditMessage, $payProcessing, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $payProcessing;

        return $this->response;
    }


    /**
     * Find the PayBenefit by id
     *
     * @param int $id
     *
     * @return PayBenefit
     */
    public function findPaySummaryById(int $id)
    {
        return $this->payBenefitRepo->findPayBenefitById($id);
    }


    /**
     * Update PayBenefit
     *
     * @param array $params
     *
     * @param PayBenefit $payBenefit
     * @return Response
     */
    public function reversePay(array $params)
    {
        //Declaration
        $is_valid = false;
        $valid = 0;
        $invalid = 0;
        $payPeriod = PayPeriod::findorFail($params['pay_period_id']);
        $employees = Employee::pluck("id");

        //Process Request
        try {

            foreach ($employees as $id)
            {
                $pay = PaySummary::where('pay_period_id', $payPeriod->id)->firstWhere('employee_id', $id);

                if($pay != null)
                {
                    try {
                        $pay->paySummaryBenefits()->delete();
                        $pay->paySummaryDeductions()->delete();
                        $pay->paySummaryReliefs()->delete();
                        $pay->delete();
                        $valid++;
                    }catch (\Exception $ex){
                        log_error(format_exception($ex), new PaySummary(), 'reverse-pay-failed');
                        $invalid++;
                    }
                }
            }
            $is_valid = true;
        } catch (\Exception $e) {
            DB::rollback();
            log_error(format_exception($e), new PayProcessing(), 'reverse-pay-failed');
        }

        //Check if reverse pay was successful
        if (!$is_valid)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'reverse-pay-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS.". ".$valid." records successful ".$invalid." failed.";

        log_activity($auditMessage, $payPeriod, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param PayBenefit $payBenefit
     * @return Response
     */
    public function postPay($payBenefit)
    {
        //Declaration
        if ($this->payBenefitRepo->deletePayBenefit($payBenefit->id))
        {
            //Audit Trail
            $logAction = 'delete-pay-benefit-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $payBenefit, $logAction);
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;

            return $this->response;
        }

        $this->response->status = ResponseType::ERROR;
        $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

        return $this->response;
    }


    /**
     * @param array $data
     * @param PayBenefit $payBenefit
     * @return Response
     */
    public function generatePaySlip($payBenefit)
    {
        //Declaration
        $result = null;

        try{
            //Process Request
            if($payBenefit->benefit_type == 'basic-salary' || $payBenefit->benefit_type == 'overtime')
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::NO_ELEMENT_DETAILS;

                return $this->response;
            }

            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $data['pay-benefit_id'] = $payBenefit->id;

            $result = PayBenefitDetail::updateOrCreate(['id' => $data['id']], $data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $payBenefit, 'update-pay-benefit-detail-failed');
        }

        //Check if Successful
        if (!$result || $result == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-pay-benefit-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS;

        log_activity($auditMessage, $result, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $result;

        return $this->response;
    }

    /**
     * @param int $id
     * @return Response
     */
    public function deletePayBenefitDetail(int $id)
    {
        //Declaration
        $result = false;
        $payBenefitDetail = new PayBenefitDetail();

        try{
            $payBenefitDetail = PayBenefitDetail::findorFail($id);
            $result = $payBenefitDetail->delete();
        }catch (\Exception $ex){
            log_error(format_exception($ex), $payBenefitDetail, 'delete-pay-benefit-detail-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-pay-benefit-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $payBenefitDetail, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
