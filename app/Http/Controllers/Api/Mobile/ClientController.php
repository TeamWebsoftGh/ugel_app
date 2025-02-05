<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\Loan\EmploymentDetailResource;
use App\Http\Resources\Loan\PersonalDetailResource;
use App\Models\Client\Client;
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

class ClientController extends MobileController
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

    public function storePersonalDetails(Request $request)
    {
        $data = $request->except('first_name', 'surname', 'other_name', 'date_of_birth', 'type_of_card',
            'card_number','date_of_issue', 'date_of_expiry');
        $client = $this->clientService->findClientById(user()->id);
        $results = $this->clientDetailService->savePersonalDetails($data, $client);

        return $this->apiResponseJson($results);
    }

    public function getPersonalDetails()
    {
        $client = $this->clientService->findClientById(user()->id);

        $pd = new PersonalDetailResource($client->personal_details);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $pd);
    }

    public function storeEmploymentDetails(Request $request)
    {
        $data = $request->all();
        $client = $this->clientService->findClientById(user()->id);
        $results = $this->clientDetailService->saveEmploymentDetails($data, $client);

        return $this->apiResponseJson($results);
    }

    public function getEmploymentDetails()
    {
        $client = $this->clientService->findClientById(user()->id);

        if(!isset($client->employment_details)){
            $pd = new EmploymentDetailResource(new EmploymentInformation());

            return $this->sendResponse("001", "No employment details found.", $pd);
        }

        $pd = new EmploymentDetailResource($client->employment_details);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $pd);
    }

    public function getAccounts()
    {
        $client = $this->clientService->findClientById(user()->id);
        $results = $this->clientService->getAccounts($client);

        return $this->apiResponseJson($results);
    }

    public function getAccountDetail(string $accountNumber)
    {
        $client = $this->clientService->findClientById(user()->id);

        $results = $this->clientService->getClientAccountDetails($accountNumber, $client);

        return $this->apiResponseJson($results);
    }

    public function miniStatement(string $accountNumber)
    {
        $results = CoreBankingHelper::getMiniStatement($accountNumber);
        return $this->apiResponseJson($results);
    }

    public function verifyAccount(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required',
            'customer_id' => 'nullable',
        ]);
        $data = $request->all();
        $results = CoreBankingHelper::verifyAccount($data['phone_number'], $data['customer_id']);
        return $this->apiResponseJson($results);
    }


    public function getPassportPicture()
    {
        $client = $this->clientService->findClientById(user()->id);

        $pd = $client->identification?->passport_picture;

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $pd);
    }

}
