<?php

namespace App\Http\Controllers\Report;

use App\Exports\PropertyExport;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PropertyReportController extends Controller
{
    use JsonResponseTrait;

    private ILoanService $loanService;

    public function __construct(ILoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function loanRepayment(Request $request)
    {
        $data = $request->all();
        $data["report_title"] =  "LOAN REPAYMENT REPORT";
        $data = $this->formatLoanRepaymentData($data);

        return view('admin.report.loans.repayments', compact('data'));
    }

    public function exportLoanRepayments(Request $request)
    {
        $title = "LOAN REPAYMENT REPORT";
        $data = $request->all();
        $data = $this->formatLoanRepaymentData($data);

        $data["report_title"] = $title;

        $filename = Str::slug(settings('app_name') .'-'.$title);
        $content = view('admin.report.partials.loan-repayments-report', compact("data"));

        return $this->exportData($content, $filename);
    }

    public function loanTransactions(Request $request)
    {
        $data = $request->all();
        $data["report_title"] =  "LOAN TRANSACTIONS REPORT";
        $data = $this->formatLoanTransactionData($data);

        return view('admin.report.loans.transactions', compact('data'));
    }

    public function exportloanTransactions(Request $request)
    {
        $title = "LOAN TRANSACTIONS REPORT";
        $data = $request->all();
        $data = $this->formatLoanTransactionData($data);

        $data["report_title"] = $title;

        $filename = Str::slug(settings('app_name') .'-'.$title);
        $content = view('admin.report.partials.loan-transactions-report', compact("data"));

        return $this->exportData($content, $filename);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function getLoanTypesChart()
    {
        $data = [];
        $loan_types = LoanProduct::all();
        foreach ($loan_types as $loan_type)
        {
            $count = $loan_type->loans()->count();

            $data['labels'][] = ($loan_type->name);
            $data['values'][] = ($count);
        }

        return $data;
    }

    private function formatLoanRepaymentData($data)
    {
        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->addDays(7)->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->addDays(7)->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

        if(isset($data['report_type']) && $data['report_type'] == "pdf")
        {
            $data["logo"] = public_path(settings("logo"));
        }else{
            $data["logo"] = asset(settings("logo"));
        }

        $result = LoanRepaymentSchedule::query();

        if (!empty($data['filter_start_date']))
        {
            $result = $result->where('due_date', '>=', $data['filter_start_date']);
        }

        if (!empty($data['filter_end_date']))
        {
            $result = $result->where('due_date', '<=', $data['filter_end_date']);
        }

        if (!empty($data['filter_status']))
        {
            $result = $result->where('status', $data['filter_status']);
        }

        if (!empty($params['filter_client']))
        {
            $result = $result->whereHas('loan', function ($query) use($params) {
                return $query->where('client_id', '=', $params['filter_client']);
            });
        }


        $data['loan_repayments'] = $result->orderBy("due_date", "desc")->get();


        return $data;
    }

    private function formatLoanTransactionData($data)
    {
        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->subMonth(1)->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->subMonth(1)->format('Y-m-d');
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

        if(isset($data['report_type']) && $data['report_type'] == "pdf")
        {
            $data["logo"] = public_path(settings("logo"));
        }else{
            $data["logo"] = asset(settings("logo"));
        }

        $result = LoanTransaction::query();

        if (!empty($data['filter_start_date']))
        {
            $result = $result->whereDate('submitted_on', '>=', $data['filter_start_date']);
        }

        if (!empty($data['filter_end_date']))
        {
            $result = $result->whereDate('submitted_on', '<=', $data['filter_end_date']);
        }

        if (!empty($data['filter_status']))
        {
            $result = $result->where('status', $data['filter_status']);
        }

        if (!empty($params['filter_client']))
        {
            $result = $result->whereHas('loan', function ($query) use($params) {
                return $query->where('client_id', '=', $params['filter_client']);
            });
        }

        $data['loan_transactions'] = $result->orderBy("submitted_on", "desc")->get();

        return $data;
    }

    private function exportData($content, $filename)
    {
        if(isset(request()->report_type) && request()->report_type =="excel"){
            return Excel::download(new PropertyExport($content), $filename.'.xlsx');
        }

        if(isset(request()->report_type) && request()->report_type =="html"){
            return $content;
        }

        return $this->loanService->print($content, $filename.".pdf", "A4-l");
    }
}
