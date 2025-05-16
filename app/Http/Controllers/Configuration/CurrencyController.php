<?php

namespace App\Http\Controllers\Configuration;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Settings\Currency;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\ICurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * @var CurrencyController
     */
    private ICurrencyService|CurrencyController $currencyService;

    /**
     * CategoryController constructor.
     *
     * @param ICurrencyService $currencyService
     */
    public function __construct(ICurrencyService $currencyService)
    {
        parent::__construct();
        $this->currencyService = $currencyService;
    }

    public function index()
    {
        if (request()->ajax())
        {
            $currencies = $this->currencyService->listCurrencies('updated_at');

            return datatables()->of($currencies)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })

                ->addColumn('formatted_currency', function ($row)
                {
                    return $row->currency .' '. $row->symbol?? '';
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-currencies'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-currencies'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('configuration.currencies.index');
    }

    public function edit(Request $request, $id)
    {
        $currency = $this->currencyService->findCurrencyById($id);

        if ($request->ajax()){
            return view('configuration.currencies.edit', compact('currency'));
        }

        return redirect()->route('configurations.currencies.index');
    }

    public function create(Request $request)
    {
        $currency = new Currency();
        if ($request->ajax()){
            return view('configuration.currencies.edit', compact('currency'));
        }
        return redirect()->route('configurations.currencies.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'currency' => 'required',
            'code' => 'required|unique:currencies,id',
            'symbol' => 'required',
            'precision' => 'required',
            'exchange_rate' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $currency = $this->currencyService->findCurrencyById($request->input("id"));
            $results = $this->currencyService->updateCurrency($data, $currency);
        }else{
            $results = $this->currencyService->createCurrency($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('configurations.currencies.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->currencyService->findCurrencyById($id);
        $result = $this->currencyService->deleteCurrency($writer);

        return $this->responseJson($result);
    }
}
