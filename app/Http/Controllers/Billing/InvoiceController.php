<?php

namespace App\Http\Controllers\Billing;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Billing\Invoice;
use App\Services\Billing\Interfaces\IInvoiceService;
use App\Services\Helpers\PropertyHelper;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private IInvoiceService $invoiceService;

    public function __construct(IInvoiceService $invoiceService)
    {
        parent::__construct();
        $this->invoiceService = $invoiceService;
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
            $amenities = $this->invoiceService->listInvoices($request->all(), 'updated_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('client_name', fn($row) => $row->client->fullname)
                ->addColumn('client_number', fn($row) => $row->client->client_number)
                ->addColumn('property_name', fn($row) => $row->booking->property->property_name)
                ->addColumn('property_unit_name', fn($row) => $row->booking->propertyUnit->unit_name)
                ->addColumn('formatted_total', fn($row) => format_money($row->total_amount))
                ->addColumn('formatted_paid', fn($row) => format_money($row->total_paid))
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "invoices"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('billing.invoices.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new Invoice();
        $item->is_active = 1;
        $invoiceItemLookups = PropertyHelper::getAllInvoiceItems();

        return view('billing.invoices.edit', compact('item', 'invoiceItemLookups'));
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
            'items' => 'nullable|array',
            'items.*.lookup_id' => 'nullable|exists:invoice_item_lookups,id',
            'items.*.description' => 'nullable|string',
            'items.*.rate' => 'nullable|numeric',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.amount' => 'nullable|numeric',
        ]);

        $data = $request->except('_token', '_method');

        $result = $request->filled('id')
            ? $this->invoiceService->updateInvoice(
                $data,
                $this->invoiceService->findInvoiceById($request->input('id'))
            )
            : $this->invoiceService->createInvoice($data);

        return $request->ajax()
            ? $this->responseJson($result)
            : $this->handleRedirect($result, 'invoices.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->invoiceService->findInvoiceById($id);
        $invoiceItemLookups = PropertyHelper::getAllInvoiceItems();

        return request()->ajax()
            ? view('billing.invoices.edit', compact('item', "invoiceItemLookups"))
            : redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->invoiceService->findInvoiceById($id);
        $result = $this->invoiceService->deleteInvoice($amenity);

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
        $result = $this->invoiceService->deleteMultipleInvoices($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('billing.invoices.import');
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

        return $this->handleRedirect($result, 'invoices.index');
    }
}
