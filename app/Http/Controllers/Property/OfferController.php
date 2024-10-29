<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Imports\AssetsImport;
use App\Models\Common\AssetCategory;
use App\Models\Property\Asset;
use App\Models\Offer;
use App\Services\Interfaces\IOfferService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class OfferController extends Controller
{
    private IOfferService $offerService;

    public function __construct(IOfferService $offerService)
    {
        $this->offerService = $offerService;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Application|Factory|View
     */
	public function index(Request $request)
	{
        $data = $request->all();
        $offers = $this->offerService->listOffers($data);

        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->startOfYear()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->startOfYear()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

		return view('property.offers.index', compact("offers", "data"));
	}


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function active(Request $request)
    {
        $data = $request->all();
        $offers = $this->offerService->listOffers($data);

        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->startOfYear()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->startOfYear()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

        return view('property.offers.index', compact("offers", "data"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function queued(Request $request)
    {
        $data = $request->all();
        $offers = $this->offerService->listOffers($data);

        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->startOfYear()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->startOfYear()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

        return view('property.offers.queued', compact("offers", "data"));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function create()
    {
        $offer = new Offer();
        $offer->is_active = 1;

        return view('property.offers.create', compact('offer'));
    }

    public function kyc()
    {
        $offer = new Offer();
        $offer->is_active = 1;

        return view('property.offers.kyc', compact('offer'));
    }

    public function payment()
    {
        $offer = new Offer();
        $offer->is_active = 1;

        return view('property.offers.payment', compact('offer'));
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
            'employee_id' => 'required',
            'assets_category_id' => 'required',
            'asset_name' => 'required',
            'asset_code' => 'required|unique:offers,asset_code,'. request()->input("id"),
            'serial_number' => 'required|unique:offers,serial_number,' . request()->input("id"),
            'status' => 'required',
            'attachment' => 'nullable|image|max:5048|mimes:jpeg,png,jpg,gif'
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $asset = $this->offerService->findAssetById($request->input("id"));
            $results = $this->offerService->updateAsset($data, $asset);
        }else{
            $results = $this->offerService->createAsset($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property.offers.index');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|\Illuminate\Contracts\Foundation\Application|View
     */
	public function edit($id)
	{
        $asset = $this->offerService->findAssetById($id);
        $asset_categories = AssetCategory::select('id', 'category_name')->get();

        if (request()->ajax()){
            return view('property.offers.edit', compact('asset', 'asset_categories'));
        }

        return redirect()->route("property.awards.index");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy($id)
	{
        $asset = $this->offerService->findAssetById($id);
        $result = $this->offerService->deleteAsset($asset);

        return $this->responseJson($result);
	}


	public function bulkDelete(Request $request)
	{
		$asset_id = $request['assetIdArray'];
		$assets = Asset::whereIn('id', $asset_id)->get();

		foreach ($assets as $asset)
		{
			$file_path = $asset->asset_image;

			if ($file_path)
			{
				$file_path = public_path('uploads/asset_file/' . $file_path);
				if (file_exists($file_path))
				{
					unlink($file_path);
				}
			}
			$asset->delete();
		}

		return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Travel')])]);
	}


	public function download($id)
	{
		$asset = Asset::findOrFail($id);
		$file_path = $asset->asset_image;
		$file_path = public_path('uploads/asset_file/' . $file_path);

		return response()->download($file_path);
	}

    public function import()
    {
        return view('property.offers.import');
    }

    public function importPost()
    {
        try
        {
            Excel::queueImport(new AssetsImport(), request()->file('file'));
        } catch (ValidationException $e)
        {
            $failures = $e->failures();
            return view('shared.importError', compact('failures'));
        }catch (\Exception $ex){
            return redirect()->back()->with('error', $ex->getMessage());
        }
        request()->session()->flash('message', "Employee Assets Imported Successfully");

        return redirect()->route('property.offers.index');
    }
}
