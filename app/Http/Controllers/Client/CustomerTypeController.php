<?php

namespace App\Http\Controllers\Client;



use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Client\ClientType;
use App\Services\Interfaces\IClientTypeService;
use Illuminate\Http\Request;

class CustomerTypeController extends Controller
{
    private IClientTypeService $clientTypeService;
	//

    /**
     * Create a new controller instance.
     *
     * @param IClientTypeService $clientTypeService
     */
    public function __construct(IClientTypeService $clientTypeService)
    {
        parent::__construct();
        $this->clientTypeService = $clientTypeService;
    }

    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $types = $this->clientTypeService->listClientTypes($data);
            return datatables()->of($types)
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
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-client-types'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-client-types'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('client.client-types.index');
    }

    public function create()
    {
        $client_type = new ClientType();
        $client_type->is_active = 1;

        if (request()->ajax()){
            return view('client.client-types.edit', compact('client_type'));
        }

        return redirect()->route("admin.customer-types.index");
    }

    public function edit(Request $request, $id)
    {
        $client_type = $this->clientTypeService->findClientTypeById($id);

        if ($request->ajax()){
            return view('client.client-types.edit', compact('client_type'));
        }

        return redirect()->route("admin.customer-types.index");
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|unique:client_types,code,'.$request->input("id"),
            'name' => 'required',
            'category' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $contact = $this->clientTypeService->findClientTypeById($request->input("id"));
            $results = $this->clientTypeService->updateClientType($data, $contact);
        }else{
            $results = $this->clientTypeService->createClientType($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route("admin.customer-types.index");
    }

    public function destroy(int $id)
    {
        $contact = $this->clientTypeService->findClientTypeById($id);
        $result = $this->clientTypeService->deleteClientType($contact);

        return $this->responseJson($result);
    }
}
