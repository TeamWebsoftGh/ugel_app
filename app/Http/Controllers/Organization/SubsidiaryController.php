<?php

namespace App\Http\Controllers\Organization;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Organization\Subsidiary;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\ISubsidiaryService;
use Illuminate\Http\Request;

class SubsidiaryController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var ISubsidiaryService
     */
    private ISubsidiaryService $subsidiaryService;

    /**
     * SubsidiaryController constructor.
     *
     */
    public function __construct(ISubsidiaryService $subsidiaryService)
    {
        parent::__construct();
        $this->subsidiaryService = $subsidiaryService;
    }

    public function index()
    {
        $subsidiary = new Subsidiary();
        $subsidiary->status = 1;
        $subsidiaries = $this->subsidiaryService->listSubsidiaries('updated_at', 'desc');
        if (request()->ajax())
        {
            return datatables()->of($subsidiaries)
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
        return view('organization.subsidiaries.create', compact('subsidiaries', 'subsidiary'));
    }

    public function show(Request $request, $id)
    {
        $subsidiaries = $this->subsidiaryService->listSubsidiaries();
        $subsidiary = $this->subsidiaryService->findSubsidiaryById($id);

        if ($request->ajax()){
            return view('organization.subsidiaries.edit', compact('subsidiaries', 'subsidiary'));
        }

        return view('organization.subsidiaries.index', compact('subsidiaries'));
    }

    public function Store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:subsidiaries,name,'.$request->input("id"),
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $subsidiary = $this->subsidiaryService->findSubsidiaryById($request->input("id"));
            $results = $this->subsidiaryService->updateSubsidiary($data, $subsidiary);
        }else{
            $results = $this->subsidiaryService->createSubsidiary($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('organization.subsidiaries.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->subsidiaryService->findSubsidiaryById($id);
        $result = $this->subsidiaryService->deleteSubsidiary($writer);

        return $this->responseJson($result);
    }
}
