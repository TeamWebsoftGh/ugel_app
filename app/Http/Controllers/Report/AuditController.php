<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Audit\ErrorLog;
use App\Models\Audit\LogActivity;
use App\Models\Auth\User;
use App\Services\Auth\Interfaces\IUserService;
use App\Services\Interfaces\IAuditService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AuditController extends Controller
{
    private $userService;
    private $auditService;

    /**
     * @param IUserService $userService
     * @param IAuditService $auditService
     */
    public function __construct(
        IUserService $userService,
        IAuditService $auditService
    ){
        $this->userService = $userService;
        $this->auditService = $auditService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $activities = LogActivity::getLatest();
        return view('report.audit.index', compact('activities'));
    }

    public function userActivity()
    {
        $activities = $this->auditService->listLogs()
            ->where('user_model', '==', User::class);

        return view('report.audit.index', compact('activities'));
    }

    public function errorLogs()
    {
        $activities = ErrorLog::all();

        $title = "Error Logs";

        return view('report.audit.index', compact('activities', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function customerActivity()
    {
        $activities = $this->auditService->listLogs()
            ->where('subject_type', '==', 'App\Models\Customer');

        return view('report.audit.index', compact('activities'));
    }

    public function loginActivity()
    {
        $activities1 = $this->auditService->listLogsByType(2);

        $activities = $activities1->where('subject_type', '==', User::class);
        $title = "Login Activities";

        return view('report.audit.index', compact('activities', 'title'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
