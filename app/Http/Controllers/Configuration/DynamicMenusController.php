<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function redirect;
use function view;

class DynamicMenusController extends Controller
{
    /**
     * @var Menu
     */
    private $menu;
    /**
     * @var Menu
     */
    private $subMenu;

    /**
     * DynamicMenusController constructor.
     * @param Menu $menu
     * @param Menu $subMenu
     */
    public function __construct(Menu $menu, Menu $subMenu)
    {
        $this->menu = $menu;
        $this->subMenu = $subMenu;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function createMainMenu ()
    {
      return view('tasks.configuration.menus.main')->with($this->getPageMenus());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSubMenu ()
    {
        return view('cms.dynamic-pages.menus.sub')->with($this->getPageMenus('main-navigation')) ;
        // return view('cms.dynamic-pages.menus.sub')->with($this->getPageMenus()) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMainMenu(Request $request)
    {
        if($request->has('id') && ($id = $request->get('id')) != null){
            $m = $this->menu->find($id);
            if($m!=null){
                $u = $request->get('page') ;
                $request->merge(['url' => ($u?$u.'/':'').\Illuminate\Support\Str::slug($request->get('name'))]) ;
                $m->update($request->only($this->menu->getFillable()));
                return redirect()->back()->with("message","Menu successfully updated.");
            }

        }else{
            $ext = $this->menu->where(['name' => $request->get('name')])->first();
            if($ext != null){
                $ext->update($request->only($this->menu->getFillable())) ;
                return redirect()->back()->with("message","Menu successfully updated.");
            }else{
                if(! $request->has('url') || ($url = $request->get('url')) == null ){
                    $u = $request->get('page') ;
                    $request->merge(['url' => ($u?$u.'/':''). \Illuminate\Support\Str::slug($request->get('name'))]) ;
                }
                $this->menu->create($request->only($this->menu->getFillable())) ;
                return redirect()->back()->with("message","Menu successfully created.");
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSubMenu(Request $request)
    {
        if($request->has('id') && ($id = $request->get('id')) != null){
            $m = $this->subMenu->find($id);
            if($m!=null){
                $m->update($request->only($this->subMenu->getFillable()));
                return redirect()->back()->with("message","Sub menu successfully updated.");
            }

        }else{
            $ext = $this->subMenu->where(['name' => $request->get('name')])->first();
            if($ext != null){
                $ext->update($request->only($this->subMenu->getFillable())) ;
                return redirect()->back()->with("message","Sub menu successfully updated.");
            }else{
                if(! $request->has('url') || ($url = $request->get('url')) == null ){
                    $request->merge(['url' => \Illuminate\Support\Str::slug($request->get('name'))]) ;
                }
                $this->subMenu->create($request->only($this->subMenu->getFillable())) ;
                return redirect()->back()->with("message","Sub menu successfully created.");
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return void
     */
    public function removeMainMenu(Request $request)
    {
        $rec = $this->menu->findOrFail($request->get('id')) ;
        if($rec != null){

            $sub_menus = $rec->sub_menus->count();
            $page_counts = $rec->pages->count();
            if($sub_menus > 0 || $page_counts > 0){
                return redirect()->back()->with(['error' => "Unable to delete item, because it contains other related records."]);
            }
            $rec->delete() ;
            return redirect()->back()->with(['message' => "Record successfully deleted"]);
        }
        return redirect()->back()->with(['error' => "Unable to delete record"]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return void
     */
    public function removeSubMenu(Request $request)
    {
        $rec = $this->subMenu->findOrFail($request->get('id')) ;
        if($rec != null){
            $rec->delete() ;
            return redirect()->back()->with(['message' => "Record successfully deleted"]);
        }
        return redirect()->back()->with(['error' => "Unable to delete record"]);
    }


    /**
     * @param null $position
     * @return array
     *
     * // This is not the best practice though. But it would be an over-kill to user something more advanced, like Repositories.
     */
    public function getPageMenus($position = null){
        if($position != null){
            $menu = $this->menu->where(['position' => $position, 'is_active' => 1])->orderBy('sort_order', 'asc')->get();
        }else{
            $menu = $this->menu->orderBy('sort_order', 'asc')->get();
        }
        return [
            'menus' => $menu,
            'menus_for_create' => $this->menu->where(['position' => 'main-navigation'])->orderBy('sort_order', 'asc')->get(),
            'sub_menus' => $this->subMenu->orderBy('sort_order', 'asc')->get()
        ];
    }
}
