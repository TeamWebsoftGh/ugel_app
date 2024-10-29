<?php

namespace App\Http\Controllers\Portal\Organization;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Property\Unit;
use App\Models\Traits\JsonResponseTrait;
use App\Services\Interfaces\IUnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function datatables;
use function redirect;
use function request;
use function view;

class UnitController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var UnitController
     */
    private $unitService;

    /**
     * CategoryController constructor.
     *
     * @param IUnitService $unitService
     */
    public function __construct(IUnitService $unitService)
    {
        parent::__construct();
        $this->unitService = $unitService;
    }

    public function index()
    {
        $units = $this->unitService->listUnits('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($units)
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
        $unit = new Unit();
        return view('portal.units.create', compact('unit', 'units'));
    }

    public function show(Request $request, $id)
    {
        $units = $this->unitService->listunits();
        $unit = $this->unitService->findUnitById($id);

        if ($request->ajax()){
            return view('portal.units.edit', compact('units', 'unit'));
        }

        return view('portal.units.index', compact('units'));
    }

    public function Create()
    {
        $unit = new Unit();
        return view('portal.units.create', compact('unit'));
    }

    public function Store(Request $request)
    {
        $validatedData = $request->validate([
            'unit_name' => 'required',
            'department_id' => 'required',
            'status' => 'required',
            'email_address' => 'email|nullable',
            'phone_number' => 'nullable|phone',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $unit = $this->unitService->findUnitById($request->input("id"));
            $results = $this->unitService->updateUnit($data, $unit);
        }else{
            $results = $this->unitService->createUnit($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('units.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->unitService->findUnitById($id);
        $result = $this->unitService->deleteUnit($writer);

        return $this->responseJson($result);
    }
}
