<?php

namespace App\Http\Controllers\Memo;

use App\Abstracts\Http\Controller;
use App\Models\Memo\PopupBuilder;
use App\Traits\UploadableTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PopUpController extends Controller
{
    use UploadableTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $popups = PopupBuilder::all();
        return view('memo.popups.index', compact("popups"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Http\Response|View
     */
    public function create()
    {
        $popup = New PopUpBuilder();
        return view('memo.popups.create', compact("popup"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->except('_token', 'id');
        $popup = New PopUpBuilder();

        $this->validate($request,[
            'name' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'title' => 'nullable|string',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'offer_time_end' => 'nullable|string',
            'btn_status' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'background_image' => 'nullable',
            'image' => 'nullable',
        ]);

        try {
            $bg_image = $request->file('background_image');
            $image = $request->file('image');

            if (isset($params['favicon']) && $params['favicon'] instanceof UploadedFile) {
                $params['favicon'] = $this->uploadPublic($params['favicon'], 'favicon', 'logo');
            }

            if (isset($params['logo']) && $params['logo'] instanceof UploadedFile) {
                $params['logo'] = $this->uploadPublic($params['logo'], 'logo', 'logo');
            }

            if($request->hasfile('background_image'))
            {
                $data['cover_image'] = $this->uploadPublic($bg_image, Str::slug($data['name']).time(), 'popups/background');
            }

            if($request->hasfile('image'))
            {
                $data['only_image'] = $this->uploadPublic($image, Str::slug($data['name']).time(), 'popups/images');
            }

            if ($request->has("id") && $request->input("id") != "")
            {
                $popup= PopUpBuilder::findOrFail($request->input('id'));
                $popup->update($data);
                request()->session()->flash('message',"Popup successfully updated.");
                return redirect()->route('memos.popups.index');
            }else{
                $popup->create($data);
                request()->session()->flash('message',"Popup successfully created.");
                return redirect()->route('memos.popups.index');
            }

        }catch (\Exception $ex){
            request()->session()->flash('error', "An error occurred. Try again later. ".$ex->getMessage());
        }


        return redirect()->back()->withInput($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|\Illuminate\Http\Response|View
     */
    public function edit($id)
    {
        $popup= PopUpBuilder::findOrFail($id);
        return view('memo.popups.create', compact("popup"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $popup= PopUpBuilder::findOrFail($id);
        $data = $request->except('_token', 'id');
        $this->validate($request,[
            'name' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'title' => 'nullable|string',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'offer_time_end' => 'nullable|string',
            'btn_status' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'background_image' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $bg_image = $request->file('background_image');
        $image = $request->file('image');

        if($request->hasfile('background_image'))
        {
            $data['cover_image'] = $this->uploadPublic($bg_image, Str::slug($data['name']).time(), 'popups/background');
        }

        if($request->hasfile('image'))
        {
            $data['only_image'] = $this->uploadPublic($image, Str::slug($data['name']).time(), 'popups/images');
        }
        $popup->update($data);
        request()->session()->flash('message',"Popup successfully updated.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function getPopups(){
        $to = date('Y-m-d');

        return [
            'popups' => PopUpBuilder::where('is_active', 1)->where('start_date', '>=', date('Y-m-d'))->where('end_date', '<=', date('Y-m-d')),
            'popup' => PopUpBuilder::where('is_active', 1)->whereDate('start_date', '<=', $to)
                ->whereDate('end_date', '>=', $to)->first(),
        ];
    }
}
