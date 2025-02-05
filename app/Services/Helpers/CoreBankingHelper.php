<?php

namespace App\Services\Helpers;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Client\Client;
use App\Models\Client\Transaction;
use App\Models\Common\ProductType;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanProduct;
use App\Models\Loan\LoanRepaymentSchedule;
use App\Repositories\ClientRepository;
use App\Traits\CoreBankingTrait;
use App\Traits\HubtelTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CoreBankingHelper
{
    use CoreBankingTrait, HubtelTrait;

    /**
     * @param Client $client
     * @return Response
     */
    public static function getAccounts(Client $client)
    {
        try {
            $res = isset($client->customer_id)
                ? (new CoreBankingHelper)->getAccountsByCustomerId($client->customer_id)
                : (new CoreBankingHelper)->getAccountsByPhoneNumber($client->phone_number);


            if ($res && $res->response_code === 0) {
                if ($client->phone_number !== $res->ph_no) {
                    return static::errorResponse("Your phone number is not linked to this account.");
                }

                $accounts = $res->account_list;
                if (!isset($client->customer_id)) {
                    $client->customer_id = $res->customer_id;
                    $client->save();
                }

                foreach ($accounts as $account) {
                    $pd = ProductType::firstOrCreate(['code' => $account->pr_code], ['name' => $account->pr_desc]);
                    $data = [
                        'account_name' => $account->acc_no,
                        'account_number' => $account->acc_no,
                        'ledger_balance' => $account->ledger_bal,
                        'available_balance' => $account->available_bal,
                        'product_type_id' => $pd->id,
                        'last_sync' => Carbon::now()
                    ];
                    $client->account_details()->updateOrCreate(['account_number' => $account->acc_no], $data);
                }

                return static::successResponse('sync-accounts-successful', ResponseMessage::DEFAULT_SUCCESS, $client, $client->account_details);
            }
        }catch (\Exception $e) {
            log_error(format_exception($e), $client, 'sync-accounts-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }

        return static::errorResponse("No record found.");
    }

    /**
     * @param string|null $phone_number
     * @param string|null $customer_id
     * @return Response
     */
    public static function verifyAccount(string $phone_number = null, string $customer_id = null)
    {
        try {
            if ($phone_number == null) {
                return static::errorResponse("Phone number is required.");
            }
            $res = isset($customer_id)
                ? (new CoreBankingHelper)->getAccountsByCustomerId($customer_id)
                : (new CoreBankingHelper)->getAccountsByPhoneNumber($phone_number);

            if ($res && $res->response_code === 0) {
                if ($phone_number !== $res->ph_no) {
                    return static::errorResponse("Your phone number is not linked to this account.");
                }

                return static::successResponse('verify-accounts-successful', ResponseMessage::DEFAULT_SUCCESS, new Client(), null);
            }
        }catch (\Exception $e) {
            log_error(format_exception($e), new Client(), 'verify-accounts-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }

        return static::errorResponse("No records found.");
    }

    /**
     * @param string $accountNumber
     * @param Client $client
     * @return Response
     */
    public static function getClientAccountDetails(string $accountNumber, Client $client)
    {
        try {
            $account = (new CoreBankingHelper)->getAccountDetails($accountNumber);

            if ($account && $account->response_code === 0) {
                if ($client->phone_number !== $account->phone_number || (isset($client->customer_id) && $client->customer_id !== $account->customer_id)) {
                    return static::errorResponse("Account Number is not linked to this profile.");
                }

                $pd = ProductType::firstWhere(['name' => $account->account_type]);
                $data =[];
                $data['branch_code'] = $account->branch_code??null;
                $data['account_name'] = $account->account_name??null;
                $data['account_number'] = $account->account_number;
                $data['ledger_balance'] = $account->ledger_balance;
                $data['available_balance'] = $account->available_balance;
                $data['uncleared_balance'] = $account->uncleared_balance??null;
                $data['account_status'] = $account->account_state??null;
                $data['od_limit'] = $account->od_limit??null;
                $data['accrued_interest'] = $account->accrued_int??null;
                $data['product_type_id'] = $pd?->id;
                $data['last_sync'] = \Carbon\Carbon::now();

                try {
                    $data['date_opened'] = \Carbon\Carbon::createFromFormat('d-M-y', $account->date_opened);
                    $data['last_activity_date'] = Carbon::createFromFormat('d-M-y', $account->last_activity_date);
                }catch (\Exception $exception){
                    log_error(format_exception($exception), $client, 'update-account-detail-failed');
                }

                $acc = $client->account_details()->updateOrCreate(['account_number' => $account->account_number], $data);

                return static::successResponse('update-account-detail-successful', ResponseMessage::DEFAULT_SUCCESS, $acc, $acc);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $client, 'update-account-detail-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }

        return static::errorResponse("No records found.");
    }

    /**
     * @param Client $client
     * @param string $product_code
     * @return Response
     */
    public static function registerClient(Client $client, string $product_code)
    {
        try {
            $res = (new CoreBankingHelper)->getAccountsByPhoneNumber($client->phone_number);

            if ($res && $res->response_code == 0) {
                return static::errorResponse("Phone number already registered.");
            }

            $data = (new CoreBankingHelper)->createCustomer($client, $product_code);
            if ($data && $data->response_code === 0) {
                $client->customer_id = $data->customer_id;
                $client->save();
                return static::getClientAccountDetails($data->account_no, $client);
            } else {
                log_activity($data->response_message??ResponseMessage::DEFAULT_ERROR, $client, "register-client-failed");
                return static::errorResponse($data->response_message??"Error from core banking.");
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $client, 'register-account-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }

    /**
     * @param Client $client
     * @param string $image_type
     * @param $image
     * @return Response
     */
    public static function uploadClientImage(Client $client, string $image_type, $image)
    {
        try {
            if ($client->customer_id == null) {
                return static::errorResponse("Account not yet registered with core banking.");
            }

            $data = (new CoreBankingHelper)->uploadCustomerImage($client->customer_id, $image_type, $image);
            if ($data && $data->response_code === 0) {
                return static::getClientAccountDetails($data->account_no, $client);
            } else {
                log_activity($data->response_message??ResponseMessage::DEFAULT_ERROR, $client, "register-client-failed");
                return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_CORE_BANKING_ERROR);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $client, 'register-account-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }

    /**
     * @param Loan $loan
     * @return Response
     */
    public static function initiateLoan(Loan $loan)
    {
        try {
            if ($loan->client->customer_id == null) {
                return static::errorResponse("Your account is not yet approved.");
            }
            if ($loan->external_id != null) {
                return static::errorResponse("Loan already submitted for approval.");
            }

            $data = (new CoreBankingHelper)->originateLoan($loan);
            if ($data && $data->response_code === 0) {
                $loan->external_id = $data->loan_application_id;
                $loan->save();
                return static::successResponse("originate-loan-successful", $data->response_message, $loan, $loan);
            }else{
                return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_CORE_BANKING_ERROR);            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $loan, 'originate-loan-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }

    /**
     * @param Loan $loan
     * @return Response
     */
    public static function checkLoanAppStatus(Loan $loan)
    {
        try {
            if ($loan->external_id == null) {
                return static::errorResponse("Loan not yet pushed for disbursement.");
            }

            $data = (new CoreBankingHelper)->checkLoanStatus($loan->external_id);
            Log::Info($data);
            if ($data && $data->response_code === 0) {
                $loan->approved_amount = $data->requested_amount;
                $loan->note = $data->requested_amount;
                $loan->save();
                return static::successResponse("check-loan-status-successful", $data->response_message, $loan, $loan);
            }else{
                return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_CORE_BANKING_ERROR);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $loan, 'originate-loan-failed');
        }

        return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_ERROR);
    }

    /**
     * @param Client $client
     * @return Response
     */
    public static function getAllLoans(Client $client)
    {
        try {
            $res = isset($client->customer_id)
                ? (new CoreBankingHelper)->getLoanFacilities($client->customer_id)
                : (new CoreBankingHelper)->getAccountsByPhoneNumber($client->phone_number);

            if ($res && $res->response_code === 0) {
                if ($client->phone_number !== $res->ph_no) {
                    return static::errorResponse("Your phone number is not linked to this loan details.");
                }

                $loans = $res->loan_list;
                if (!isset($client->customer_id)) {
                    $client->customer_id = $res->customer_id;
                    $client->save();
                }

                foreach ($loans as $loan) {
                    $pd = LoanProduct::firstOrCreate(['product_code' => $loan->pr_code], ['name' => $loan->pr_desc]);
                    $data = [
                        'external_id' => $loan->f_no,
                        'reference' => $loan->f_no,
                        'loan_account_number' => $loan->p_acct,
                        'account_number' => $loan->m_acct,
                        'interest_rate' => $pd->default_interest_rate,
                        'accrued_interest' => $loan->accr_int,
                        'principal' => $loan->p_amt,
                        'applied_amount' => $loan->p_amt,
                        'approved_amount' => $loan->p_amt,
                        'interest_disbursed_derived' => $loan->i_amt,
                        'total_disbursed_derived' => $loan->tot_amt,
                        'principal_repaid_derived' => $loan->pp,
                        'interest_repaid_derived' => $loan->ip,
                        'principal_outstanding_derived' => $loan->pos,
                        'interest_outstanding_derived' => $loan->ios,
                        'total_outstanding_derived' => $loan->tos,
                        'repayment_frequency' => 1,
                        'company_id' => company_id()??1,
                        'loan_term' => $loan->tenure,
                        'amortization_method' => "equal_installments",
                        'repayment_frequency_type' => convert_frequency_to_plural($loan->p_freq),
                        'loan_product_id' => $pd->id,
                    ];

                    try {
                        $data['disbursed_on'] = Carbon::createFromFormat('d/m/y', $loan->e_date);
                        $data['first_payment_date'] = Carbon::createFromFormat('d/m/y', $loan->f_date)->format(env('Date_Format'));
                        $data['last_payment_date'] = Carbon::createFromFormat('d/m/y', $loan->l_date);
                    }catch (\Exception $exception){
                        log_error(format_exception($exception), $client, 'update-account-detail-failed');
                    }
                    $client->loans()->updateOrCreate(['external_id' => $loan->f_no], $data);
                }

                return static::successResponse('sync-loans-successful', ResponseMessage::DEFAULT_SUCCESS, $client, $client->account_details);
            }else{
                return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_CORE_BANKING_ERROR);
            }
        }catch (\Exception $e) {
            log_error(format_exception($e), $client, 'sync-loans-failed');
        }

        return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
    }

    /**
     * @param Loan $loan
     * @return Response
     */
    public static function getLoanDetails(Loan $loan)
    {
        try {
            $result = (new CoreBankingHelper)->getLoanFacilityDetails($loan->loan_account_number);

            if ($result && $result->response_code === 0) {
                if ($loan->client->phone_number !== $result->ph_no || (isset($loan->client->customer_id) && $loan->client->customer_id !== $result->customer_id)) {
                    return static::errorResponse("Loan is not linked to this profile.");
                }

                $data = [
                    'external_id' => $result->f_no,
                    'account_number' => $result->m_acct,
                    'approved_amount' => $result->p_amt,
                    'interest_disbursed_derived' => $result->i_amt,
                    'total_disbursed_derived' => $result->tot_amt,
                    'principal_repaid_derived' => $result->pp,
                    'interest_repaid_derived' => $result->ip,
                    'principal_outstanding_derived' => $result->pos,
                    'interest_outstanding_derived' => $result->ios,
                    'total_outstanding_derived' => $result->tos,
                ];

                try {
                    $data['disbursed_on'] = Carbon::createFromFormat('d/m/y', $loan->e_date);
                    $data['first_payment_date'] = Carbon::createFromFormat('d/m/y', $loan->f_date)->format(env('Date_Format'));
                    $data['last_payment_date'] = Carbon::createFromFormat('d/m/y', $loan->l_date);
                }catch (\Exception $exception){
                    log_error(format_exception($exception), $loan, 'update-loan-detail-failed');
                }

                $acc = $loan->update($data);
                if($acc)
                {
                    $total_principal = 0;
                    $total_interest = 0;

                    foreach ($result->repay_schedule as $item)
                    {
                        try {
                            $loan_repayment_schedule = new LoanRepaymentSchedule();
                            $loan_repayment_schedule->created_by = admin_user()?->id;
                            $loan_repayment_schedule->company_id = company_id()??1;
                            $loan_repayment_schedule->loan_id = $loan->id;
                            $loan_repayment_schedule->installment = $item->eppmt+$item->eipmt;
                            $loan_repayment_schedule->interest = $item->eipmt;
                            $loan_repayment_schedule->principal = $item->eppmt;
                            $loan_repayment_schedule->from_date = $loan->first_payment_date;

                            try {
                                //Assuming $result->e_date is "31/05/19"
                                $date = Carbon::createFromFormat('d/m/y', $result->e_date); // Changed format to 'd/m/y'

                                $loan_repayment_schedule->due_date = $date; // Storing Carbon instance in due_date
                                $loan_repayment_schedule->month = $date->format('m'); // Formatting month as two digits
                                $loan_repayment_schedule->year = $date->format('Y'); // Formatting year as four digits

                                $loan_repayment_schedule->payment_date = $result->ppdd != ""?Carbon::createFromFormat('d/m/y', $result->ppdd):null; // Storing Carbon instance in due_date

                            }catch (\Exception $exception){
                                log_error(format_exception($exception), $loan, 'update-loan-detail-failed');
                            }

                            $total_principal = $total_principal + $loan_repayment_schedule->principal;
                            $total_interest = $total_interest + $loan_repayment_schedule->interest;
                            $loan_repayment_schedule->total_due = $loan_repayment_schedule->principal + $loan_repayment_schedule->interest;
                            $loan_repayment_schedule->save();

                            $paid = $result->ppd+$result->ipd;

                            if($paid>0)
                            {
                                $data['credit'] = $paid;
                                $data['reference'] = $loan_repayment_schedule->id;
                                $data['status'] = "paid";
                                $data['name'] = trans_choice('loan.repayment', 1);
                                $data['loan_transaction_type_id'] = 2;
                                $data['created_by'] = user()?->id;
                                $data['company_id'] = company_id()??1;

                                $result = $loan->transactions()->updateOrCreate(['reference' => $data['reference']], $data);
                            }
                        }catch (\Exception $exception){
                            log_error(format_exception($exception), $loan, 'update-loan-detail-failed');
                        }
                    }
                }

                return static::successResponse('update-account-detail-successful', ResponseMessage::DEFAULT_SUCCESS, $acc, $acc);
            }else{
                return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_CORE_BANKING_ERROR);
            }
        } catch (\Exception $e) {

            log_error(format_exception($e), $loan, 'update-loan-detail-failed');
        }

        return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
    }

    /**
     * @param Client $client
     * @return Response
     */
    public static function getAllInvestments(Client $client)
    {
        try {
            $res = (new CoreBankingHelper)->getInvestments($client->customer_id);

            if ($res && $res->response_code === 0) {
                if ($client->phone_number !== $res->ph_no) {
                    return static::errorResponse("Your phone number is not linked to this loan details.");
                }

                $investments = $res->investment_list;

                return static::successResponse('sync-investments-successful', ResponseMessage::DEFAULT_SUCCESS, $client, $investments);
            }else{
                return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_CORE_BANKING_ERROR);
            }
        }catch (\Exception $e) {
            log_error(format_exception($e), $client, 'sync-loans-failed');
        }

        return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
    }

    /**
     * @param string|null $account_number
     * @return Response
     */
    public static function getMiniStatement(string $account_number = null)
    {
        try {
            if ($account_number == null) {
                return static::errorResponse("Account number is required.");
            }
            $res = (new CoreBankingHelper)->miniStatement($account_number);

            if ($res && $res->response_code === 0) {
                $list = $res->trans_list;

                return static::successResponse('get-statement-successful', "Statement for $account_number", new Client(), null);
            }else{
                return static::errorResponse($data->response_message??ResponseMessage::DEFAULT_CORE_BANKING_ERROR);
            }
        }catch (\Exception $e) {
            log_error(format_exception($e), new Client(), 'get-mini-statement-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }

        return static::errorResponse("No records found.");
    }

    /**
     * @param string $account_number
     * @param string $channel
     * "data": {
     * "isRegistered": true,
     * "name": "Emmanuel Doe",
     * "status": "active",
     * "profile": "Subscriber"
     * }
     * @return Response
     */
    public static function verifyMomoNumber(string $account_number, string $channel)
    {
        try
        {
            $res = (new CoreBankingHelper)->verifyMoMoAccount($account_number, $channel);

            if ($res && $res->success)
            {
                return static::successResponse('verify-momo-successful', "Verify MoMo Number for $account_number", new Client(), $res->data);
            }else{
                return static::errorResponse("MoMo Number verification failed.");
            }
        }catch (\Exception $e) {
            log_error(format_exception($e), new Client(), 'verify-momo-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }

    /**
     * @param string $account_number
     * @param string $bank_code
     * "data": {
     * "name": "EMMANUEL DOE"
     * }
     * @return Response
     */
    public static function verifyBankAccountNumber(string $account_number, string $bank_code): Response
    {
        try
        {
            $res = (new CoreBankingHelper)->verifyAccountNumber($account_number, $bank_code);

            if ($res && $res->success)
            {
                return static::successResponse('verify-account-number-successful', "Verify Account Number for $account_number", new Client(), $res->data);
            }else{
                return static::errorResponse("MoMo Number verification failed.");
            }
        }catch (\Exception $e) {
            log_error(format_exception($e), new Client(), 'verify-momo-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }

    /**
     * @param Client $client
     * @param string $debit_acc
     * @param string $amount
     * @param string $narration
     * @param $client_ref
     * @return Response
     */
    public static function transferToMoMo(Client $client, string $debit_acc, string $momo_number, string $amount, string $narration, $client_ref, $channel)
    {
        $credit_acc = settings("gl_account");
        try
        {
            if(Transaction::firstWhere('core_ref', $client_ref))
            {
                return static::errorResponse("Duplicate transaction.");
            }

            $res = (new CoreBankingHelper)->fundTransfer($debit_acc, $credit_acc,$amount,$narration,$client_ref);

            if ($res && $res->response_code === 0)
            {
                $trans = new Transaction();
                $trans->client_id = $client->id;
                $trans->client_reference = $client_ref;
                $trans->core_ref = $client_ref;
                $trans->transfer_type = "momo";
                $trans->channel = $channel;
                $trans->bank = $channel;
                $trans->credit_account = $credit_acc;
                $trans->debit_account = $debit_acc;
                $trans->amount = $amount;
                $trans->core_status = "Success";
                $trans->narration = $narration;
                $trans->status = "started";

                $res1 = (new CoreBankingHelper)->sendMoneyMoMo($client_ref,$amount,$channel,$momo_number,$narration,$client->email, $client->fullname);

                if($res1 && $res1->success)
                {
                    $dat = $res1->data->Data;
                    $trans->transaction_id = $dat->TransactionId;
                    $trans->client_reference = $dat->ClientReference;
                    $trans->message = $dat->Description;
                    $trans->status = "Pending";

                    $trans->save();
                    return static::successResponse('momo-transfer-successful', $dat->Description, $client, $dat);
                }else{
                    $dat = $res1?->data?->Data;
                    $trans->transaction_id = $dat?->TransactionId;
                    $trans->client_reference = $dat?->ClientReference;
                    $trans->message = $dat?->Description;
                    $trans->status = "Failed";

//                    $res3 = (new CoreBankingHelper)->fundTransferReversal($debit_acc, $client_ref);
//
//                    if ($res3 && $res3->response_code === 0)
//                    {
//                        $trans->core_status = "Reversed";
//                    }

                    $trans->save();
                }
            }

            return static::errorResponse("Transaction failed. Try again later.");

        }catch (\Exception $e) {
            log_error(format_exception($e), new Client(), 'fund-transfer-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }


    /**
     * @param Client $client
     * @param string $debit_acc
     * @param string $amount
     * @param string $narration
     * @param $client_ref
     * @return Response
     */
    public static function transferToMoMoStatus($client_ref)
    {
        try
        {
            $trans = Transaction::firstWhere('client_reference', $client_ref);
            if(!$trans)
            {
                return static::errorResponse("Transaction not found.");
            }

            $res = (new CoreBankingHelper)->checkMoMoCreditStatus($client_ref);

            if ($res && $res->response_code === 0)
            {
                $trans->message = "Successful";

               if($res)
               {
                   $trans->status = "Successful ";
               }else{
                   $res3 = (new CoreBankingHelper)->fundTransferReversal($trans->debit_account, $client_ref);

                    if ($res3 && $res3->response_code === 0)
                    {
                        $trans->core_status = "Reversed";
                    }

                   $trans->status = "Failed";
               }

                $trans->save();
            }

            return static::errorResponse("Transaction failed. Try again later.");

        }catch (\Exception $e) {
            log_error(format_exception($e), new Client(), 'fund-transfer-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }

    /**
     * @param Client $client
     * @param string $debit_acc
     * @param string $amount
     * @param string $narration
     * @param $client_ref
     * @return Response
     */
    public static function transferToBank(Client $client, string $debit_acc, string $account_number, string $amount, string $narration, $client_ref, $bank_code)
    {
        $credit_acc = settings("gl_account");
        try
        {
            if(Transaction::firstWhere('core_ref', $client_ref))
            {
                return static::errorResponse("Duplicate transaction.");
            }

            $res = (new CoreBankingHelper)->fundTransfer($debit_acc, $credit_acc,$amount,$narration,$client_ref);

            if ($res && $res->response_code === 0)
            {
                $trans = new Transaction();
                $trans->client_id = $client->id;
                $trans->client_reference = $client_ref;
                $trans->core_ref = $client_ref;
                $trans->transfer_type = Constants::TRANSFER_TYPE['internal'];
                $trans->channel = $bank_code;
                $trans->bank = $bank_code;
                $trans->credit_account = $credit_acc;
                $trans->debit_account = $debit_acc;
                $trans->amount = $amount;
                $trans->core_status = "Success";
                $trans->narration = $narration;
                $trans->status = "started";

                $res1 = (new CoreBankingHelper)->sendMoneyBank($client_ref,$amount,$bank_code,$account_number,$narration,$client->phone_number, $client->fullname);

                if($res1 && $res1->success)
                {
                    $dat = $res1->data->Data;
                    $trans->transaction_id = $dat->TransactionId;
                    $trans->client_reference = $dat->ClientReference;
                    $trans->message = $dat->Description;
                    $trans->status = "Pending";

                    $trans->save();
                    return static::successResponse('bank-transfer-successful', $dat->Description, $client, $dat);
                }else{
                    $dat = $res1?->data?->Data;
                    $trans->transaction_id = $dat?->TransactionId;
                    $trans->client_reference = $dat?->ClientReference;
                    $trans->message = $dat?->Description;
                    $trans->status = "Failed";

//                    $res3 = (new CoreBankingHelper)->fundTransferReversal($debit_acc, $client_ref);
//
//                    if ($res3 && $res3->response_code === 0)
//                    {
//                        $trans->core_status = "Reversed";
//                    }

                    $trans->save();
                }
            }

            return static::errorResponse("Transaction failed. Try again later.");

        }catch (\Exception $e) {
            log_error(format_exception($e), new Client(), 'fund-transfer-failed');
            return static::errorResponse(ResponseMessage::DEFAULT_ERROR);
        }
    }


    protected static function successResponse($logAction, $auditMessage, $model, $data= null)
    {
        log_activity($auditMessage, $model, $logAction);
        $response = new Response();
        $response->status = ResponseType::SUCCESS;
        $response->message = $auditMessage;
        $response->data = $data;

        return $response;
    }

    protected static function errorResponse($message)
    {
        $response = new Response();
        $response->status = ResponseType::ERROR;
        $response->message = $message;
        return $response;
    }

}
