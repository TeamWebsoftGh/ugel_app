<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\ContactGroupResource;
use App\Http\Resources\ContactResource;
use App\Models\Memo\ContactGroup;
use App\Services\Interfaces\IContactService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class ContactController extends MobileController
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
        $items = $this->contactService->listContacts($data);

        $item = ContactResource::collection($items);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function groups()
    {
        $contact_groups = ContactGroup::where('is_active', 1)->get();
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, ContactGroupResource::collection($contact_groups));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|unique:contacts,phone_number,'.$request->input("id"),
            'contact_group_id' => 'required',
            'first_name' => 'required',
            'email' => 'nullable|email|unique:contacts,email,'.$request->input("id"),
        ]);

        $data = $request->all();

        if ($request->has("id") && $request->input("id") != null)
        {
            $contact = $this->contactService->findContactById($request->input("id"));
            $results = $this->contactService->updateContact($data, $contact);
        }else{
            $results = $this->contactService->createContact($data);
        }

        if(isset($results->data))
        {
            $results->data = new ContactResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|unique:contacts,phone_number,'.$request->input("id"),
            'contact_group_id' => 'required',
            'first_name' => 'required',
            'email' => 'nullable|email|unique:contacts,email,'.$request->input("id"),
        ]);

        $data = $request->all();

        if ($request->has("id") && $request->input("id") != null)
        {
            $contact = $this->contactService->findContactById($request->input("id"));
            $results = $this->contactService->updateContact($data, $contact);
        }else{
            $results = $this->contactService->createContact($data);
        }

        if(isset($results->data))
        {
            $results->data = new ContactResource($results->data);
        }

        return $this->apiResponseJson($results);
    }


    public function groupStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $contact_group = ContactGroup::firstOrCreate(['name' => $request->input("name")]);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, new ContactGroupResource($contact_group));

    }

    public function destroy(int $id)
    {
        $contact = $this->contactService->findContactById($id);
        $results = $this->contactService->deleteContact($contact);

        return $this->apiResponseJson($results);
    }
}
