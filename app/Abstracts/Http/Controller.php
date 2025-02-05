<?php

namespace App\Abstracts\Http;

use App\Abstracts\Http\Response;
use App\Constants\ResponseType;
use App\Traits\Jobs;
use App\Traits\JsonResponseTrait;
use App\Traits\Permissions;
use App\Traits\Relationships;
use App\Utilities\Import;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, Jobs, Permissions, Relationships, ValidatesRequests, JsonResponseTrait;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->assignPermissionsToController();
    }

    /**
     * Generate a pagination collection.
     *
     * @param array|Collection $items
     * @param int $perPage
     * @param int $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $perPage = $perPage ?: (int) request('limit', setting('default.list_limit', '25'));

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Generate a response based on request type like HTML, JSON, or anything else.
     *
     * @param string $view
     * @param array $data
     *
     * @return \Illuminate\Http\Response
     */
    public function response($view, $data = [])
    {
        $class_name = str_replace('Controllers', 'Responses', get_class($this));

        if (class_exists($class_name)) {
            $response = new $class_name($view, $data);
        } else {
            $response = new class($view, $data) extends Response {};
        }

        return $response;
    }

    /**
     * ImportRequest the excel file or catch errors
     *
     * @param $class
     * @param $request
     * @return \App\Services\Helpers\Response
     */
    public function importExcel($class, $request, $translation)
    {
        return Import::fromExcel($class, $request, $translation);
    }

    /**
     * Export the excel file or catch errors
     *
     * @param $class
     * @param $translation
     * @param $extension
     *
     * @return mixed
     */
    public function exportExcel($class, $translation, $extension = 'xlsx')
    {
        return Export::toExcel($class, $translation, $extension);
    }

    /**
     * Handle a redirect response based on operation result.
     *
     * @param $result
     * @param string $route
     * @return RedirectResponse
     */
    public function handleRedirect($result, string $route): RedirectResponse
    {
        if ($result->status !== ResponseType::SUCCESS) {
            return redirect()->back()->with('error', $result->message);
        }

        session()->flash('message', $result->message);

        return redirect()->route($route);
    }

    /**
     * Generate action buttons for datatables.
     *
     * @param $data
     * @return string
     */
    public function getActionButtons($data, $model): string
    {
        $buttons = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" title="Show"><i class="las la-eye"></i></button>';
        $buttons .= '&nbsp;';
        if (user()->can('update-'.$model)) {
            $buttons .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" title="Edit"><i class="las la-edit"></i></button>';
            $buttons .= '&nbsp;';
        }
        if (user()->can('delete-'.$model)) {
            $buttons .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" title="Delete"><i class="las la-trash"></i></button>';
        }

        return $buttons;
    }
}
