<?php

namespace App\Http\Controllers\Memo;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Imports\ContactsImport;
use App\Models\Delegate\Constituency;
use App\Models\Memo\Contact;
use App\Models\Memo\ContactGroup;
use App\Services\Interfaces\IContactService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\Exceptions\Exception;

class ContactController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var IContactService
     */
    private IContactService $contactService;

    /**
     * ContactController constructor.
     *
     * @param IContactService $contactService
     */
    public function __construct(IContactService $contactService)
    {
        parent::__construct();
        $this->contactService = $contactService;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if (request()->ajax())
        {
            $items = $this->contactService->listContacts($data);

            return datatables()->of($items)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('contact_group_name', function ($row)
                {
                    return $row->contact_group->name ?? '';
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-contacts'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-contacts'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('memo.contacts.index');
    }

    public function create()
    {
        $contact = new Contact();
        $contact->is_active = 1;
        $contact_groups = ContactGroup::where('is_active', 1)->get();

        if (request()->ajax()){
            return view('memo.contacts.edit', compact('contact', 'contact_groups'));
        }

        return redirect()->route("contacts.index");
    }

    public function edit(Request $request, $id)
    {
        $contact = $this->contactService->findContactById($id);
        $contact_groups = ContactGroup::where('is_active', 1)->get();

        if ($request->ajax()){
            return view('memo.contacts.edit', compact('contact', 'contact_groups'));
        }

        return redirect('contacts.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|unique:contacts,phone_number,'.$request->input("id"),
            'contact_group_id' => 'required',
            'first_name' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $contact = $this->contactService->findContactById($request->input("id"));
            $results = $this->contactService->updateContact($data, $contact);
        }else{
            $results = $this->contactService->createContact($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('contacts.create');
    }

    public function groupStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $group = ContactGroup::firstOrCreate(['name' => $request->input("name")]);

        if ($group)
        {
            return response()->json([
                'status' => "success",
                'id' => $group->id,
                'name' => $group->name,
            ]);
        }
        return response()->json([
            'status' => "error",
            'id' => $group?->id,
            'name' => $group?->name,
        ]);
    }

    public function destroy(int $id)
    {
        $contact = $this->contactService->findContactById($id);
        $result = $this->contactService->deleteContact($contact);

        return $this->responseJson($result);
    }

    public function import()
    {
        return view('memo.contacts.import');
    }

    public function importPost(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls',
        ]);

        try
        {
            Excel::queueImport(new ContactsImport(), request()->file('file'));
        } catch (ValidationException $e)
        {
            $failures = $e->failures();
            return view('shared.importError', compact('failures'));
        }catch (\Exception $ex){
            return redirect()->back()->with('error', $ex->getMessage());
        }
        request()->session()->flash('message', "Contacts Imported Successfully");

        return redirect()->route('contacts.index');
    }
}
