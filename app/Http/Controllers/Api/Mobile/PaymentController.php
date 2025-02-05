<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\Loan\EmploymentDetailResource;
use App\Http\Resources\Loan\PersonalDetailResource;
use App\Models\Client\Client;
use App\Models\Common\Bank;
use App\Models\Loan\Loan;
use App\Models\LoanDetails\EmploymentInformation;
use App\Models\LoanDetails\PersonalDetail;
use App\Services\Helpers\CoreBankingHelper;
use App\Services\Interfaces\IClientDetailService;
use App\Services\Interfaces\IClientService;
use App\Services\Interfaces\ILoanProductService;
use App\Services\Interfaces\ILoanService;
use App\Traits\LoanTransformable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends MobileController
{
    use LoanTransformable;

    public IClientDetailService $clientDetailService;
    public IClientService $clientService;

    public function __construct(IClientDetailService $clientDetailService, IClientService $clientService)
    {
        parent::__construct();
        $this->clientDetailService = $clientDetailService;
        $this->clientService = $clientService;
    }

    public function banks()
    {
        $banks = Bank::where('is_active', 1)->get(['name', 'code']);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $banks);
    }

    public function verifyMoMo(Request $request)
    {
        $validatedData = $request->validate([
            'wallet_number' => 'required',
            'channel' => 'required|in:mtn,vodafone,tigo',
        ], [
            'channel.in' => 'The :attribute must be either mtn or vodafone or tigo.',  // Custom error message
        ]);

        $data = $request->all();

        return $this->apiResponseJson(CoreBankingHelper::verifyMomoNumber($data['wallet_number'], $data['channel']));
    }

    public function verifyBank(Request $request)
    {
        $validatedData = $request->validate([
            'account_number' => 'required',
            'bank_code' => 'required',
        ]);
        $data = $request->all();

        return $this->apiResponseJson(CoreBankingHelper::verifyBankAccountNumber($data['account_number'], $data['bank_code']));
    }

    public function creditMoMo(Request $request)
    {
        $validatedData = $request->validate([
            'account_number' => 'required',
            'wallet_number' => 'required',
            'amount' => 'required',
            'narration' => 'required',
            'reference' => 'required',
            'channel' => 'required|in:mtn,vodafone,tigo',
        ], [
            'channel.in' => 'The :attribute must be either mtn or vodafone or tigo.',  // Custom error message
        ]);
        $data = $request->all();
        $client = $this->clientService->findClientById(user()->id);

        $results = CoreBankingHelper::transferToMoMo($client, $data['account_number'], $data['wallet_number'], $data['amount'], $data['narration'], $data['reference'], $data['channel']);

        return $this->apiResponseJson($results);
    }

    public function creditBank(Request $request)
    {
        $validatedData = $request->validate([
            'debit_account_number' => 'required',
            'credit_account_number' => 'required',
            'amount' => 'required',
            'narration' => 'required',
            'reference' => 'required',
            'bank_code' => 'required|exists:banks,code',
        ], [
            'channel.in' => 'The :attribute must be either mtn or vodafone or tigo.',  // Custom error message
        ]);
        $data = $request->all();
        $client = $this->clientService->findClientById(user()->id);

        $results = CoreBankingHelper::transferToBank($client, $data['debit_account_number'], $data['credit_account_number'], $data['amount'], $data['narration'], $data['reference'], $data['bank_code']);

        return $this->apiResponseJson($results);
    }

    public function debitMoMo(Request $request)
    {
        $validatedData = $request->validate([
            'account_number' => 'required',
            'wallet_number' => 'required',
            'amount' => 'required',
            'narration' => 'required',
            'reference' => 'required',
            'channel' => 'required|in:mtn,vodafone,tigo',
        ], [
            'channel.in' => 'The :attribute must be either mtn or vodafone or tigo.',  // Custom error message
        ]);
        $data = $request->all();
        $client = $this->clientService->findClientById(user()->id);

        $results = CoreBankingHelper::collectMoMo($client, $data['account_number'], $data['wallet_number'], $data['amount'], $data['narration'], $data['reference'], $data['channel']);

        return $this->apiResponseJson($results);
    }
}
