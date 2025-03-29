<?php

namespace App\Http\Controllers\Billing;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Billing\InvoiceItemLookup;
use App\Services\Billing\Interfaces\IInvoiceItemService;
use App\Services\Helpers\PropertyHelper;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    private IInvoiceItemService $invoiceItemService;

    public function __construct(IInvoiceItemService $invoiceItemService)
    {
        parent::__construct();
        $this->invoiceItemService = $invoiceItemService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|Factory|Application|JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $amenities = $this->invoiceItemService->listInvoiceItems($request->all(), 'updated_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('updated_at', fn($row) => Carbon::parse($row->updated_at)->format(env('Date_Format')))
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "amenities"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('billing.invoice-items.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new InvoiceItemLookup();
        $item->is_active = 1;

        return view('billing.invoice-items.edit', compact('item'));
    }

    /**
     * Store or update the resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'short_name' => 'nullable',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $result = $request->filled('id')
            ? $this->invoiceItemService->updateInvoiceItem($data, $this->invoiceItemService->findInvoiceItemById($request->input('id')))
            : $this->invoiceItemService->createInvoiceItem($data);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'invoice-items.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->invoiceItemService->findInvoiceItemById($id);

        return request()->ajax()
            ? view('billing.invoice-items.edit', compact('item'))
            : redirect()->route('invoice-items.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->invoiceItemService->findInvoiceItemById($id);
        $result = $this->invoiceItemService->deleteInvoiceItem($amenity);

        return $this->responseJson($result);
    }

    /**
     * Bulk delete resources from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $result = $this->invoiceItemService->deleteMultipleInvoiceItems($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('billing.invoice-items.import');
    }

    /**
     * Handle import of amenities.
     *
     * @return RedirectResponse
     */
    public function importPost(ImportRequest $request)
    {
        $result = $this->importExcel(new AmenitiesImport(), $request, "amenities");

        if(isset($result->data) && $result->status == ResponseType::ERROR)
        {
            return view('shared.importError', ['failures' => $result->data]);
        }

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'invoice-items.index');
    }
}
