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
    use JsonResponseTrait;
    /**
     * @var CurrencyController
     */
    private $currencyService;

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
        $currencies = $this->currencyService->listCurrencies('updated_at');
        $currency = new Currency();

        if (request()->ajax())
        {
            return datatables()->of($currencies)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->setRowAttr([
                    'data-target' => function($user) {
                        return '#main-content';
                    },
                ])
                ->addColumn('formatted_currency', function ($row)
                {
                    return $row->currency .' '. $row->symbol?? '';
                })
                ->make(true);
        }

        return view('configuration.currencies.create', compact('currency', 'currencies'));
    }

    public function edit(Request $request, $id)
    {
        $currencies = $this->currencyService->listCurrencies();
        $currency = $this->currencyService->findCurrencyById($id);

        if ($request->ajax()){
            return view('configuration.currencies.edit', compact('currency', 'currencies'));
        }

        return view('configuration.currencies.index', compact('currencies'));
    }

    public function create()
    {
        $currencies = $this->currencyService->listCurrencies('updated_at', 'desc');
        $currency = new Currency();
        return view('configuration.currencies.create', compact('currency', 'currencies'));
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
