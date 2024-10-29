<?php

namespace App\Http\Controllers\Property;

use App\Constants\ResponseType;
use App\Models\ServiceType;
use App\Services\Interfaces\IServiceTypeService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceTypeController extends \App\Abstracts\Http\Controller
{
    use JsonResponseTrait;
    /**
     * @var IServiceTypeService
     */
    private IServiceTypeService $serviceTypeService;

    /**
     * ServiceTypeController constructor.
     *
     * @param IserviceTypeService $serviceTypeService
     */
    public function __construct(IServiceTypeService $serviceTypeService)
    {
        $this->serviceTypeService = $serviceTypeService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        if (request()->ajax())
        {
            $items = $this->serviceTypeService->listServiceTypes($data);

            return datatables()->of($items)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('status', function ($row)
                {
                    return $row->is_active ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-service-types'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-service-types'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('service-types.index');
    }


    public function create()
    {
        $service_type = new ServiceType();
        $service_type->is_active = 1;

        if (request()->ajax()){
            return view('service-types.edit', compact('service_type'));
        }

        return redirect()->route("service-types.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'max_amount' => 'required',
            'min_amount' => 'required',
            'provider' => 'required',
            'description' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $meeting = $this->serviceTypeService->findServiceTypeById($request->input("id"));
            $results = $this->serviceTypeService->updateServiceType($data, $meeting);
        }else{
            $results = $this->serviceTypeService->createServiceType($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('service-types.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function show(Request $request, $id)
    {
        $service_type = $this->serviceTypeService->findServiceTypeById($id);

        if ($request->ajax()){
            return view('service-types.show', compact('service_type'));
        }

        return view('service-types.show', compact('service_type'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request, $id)
    {
        $service_type = $this->serviceTypeService->findServiceTypeById($id);

        if ($request->ajax()){
            return view('service-types.edit', compact('service_type'));
        }

        return view('service-types.create', compact('service_type'));
    }

    /**
     *
     * /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $meeting = $this->serviceTypeService->findServiceTypeById($id);
        $result = $this->serviceTypeService->deleteServiceType($meeting);

        return $this->responseJson($result);
    }
}
