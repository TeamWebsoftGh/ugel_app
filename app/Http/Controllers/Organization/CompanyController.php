<?php

namespace App\Http\Controllers\Organization;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Organization\Subsidiary;
use App\Services\Interfaces\ICompanyService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var IcompanyService
     */
    private IcompanyService $companyService;

    /**
     * SubsidiaryController constructor.
     *
     */
    public function __construct(ICompanyService $companyService)
    {
        parent::__construct();
        $this->companyService = $companyService;
    }

    public function index()
    {
        $company = new Subsidiary();
        $company->status = 1;
        $companies = $this->companyService->listCompanies('updated_at', 'desc');
        if (request()->ajax())
        {
            return datatables()->of($companies)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#list-content';
                    },
                ])
                ->make(true);
        }
        return view('organization.companies.create', compact('companies', 'company'));
    }

    public function show(Request $request, $id)
    {
        $companies = $this->companyService->listCompanies();
        $company = $this->companyService->findCompanyById($id);

        if ($request->ajax()){
            return view('organization.companies.edit', compact('companies', 'company'));
        }

        return view('organization.companies.index', compact('companies'));
    }

    public function Store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:companies,name,'.$request->input("id"),
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $subsidiary = $this->companyService->findCompanyById($request->input("id"));
            $results = $this->companyService->updateCompany($data, $subsidiary);
        }else{
            $results = $this->companyService->createCompany($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('organization.companies.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->companyService->findCompanyById($id);
        $result = $this->companyService->deleteCompany($writer);

        return $this->responseJson($result);
    }
}
