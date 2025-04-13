<?php

namespace App\Http\Controllers\Report;

use App\Abstracts\Http\Controller;
use App\Exports\PropertyExport;
use App\Models\Billing\Payment;
use App\Services\Billing\Interfaces\IBookingService;
use App\Services\Billing\Interfaces\IPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class BillingReportController extends Controller
{
    private IPaymentService $loanService;
    private IBookingService $bookingService;

    public function __construct(IPaymentService $loanService, IBookingService $bookingService)
    {
        $this->loanService = $loanService;
        $this->bookingService = $bookingService;
    }

    public function payments(Request $request)
    {
        $data = $request->all();
        $data["report_title"] =  "PAYMENTS REPORT";
        $data = $this->formatPaymentData($data);

        return view('report.billing.payments', compact('data'));
    }

    public function exportPayments(Request $request)
    {
        $title = "PAYMENT REPORT";
        $data = $request->all();
        $data = $this->formatPaymentData($data);

        $data["report_title"] = $title;

        $filename = Str::slug(settings('app_name') .'-'.$title);
        $content = view('report.billing.partials.payments-report', compact("data"));

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

    private function formatPaymentData($data)
    {
        $now = Carbon::now();
        $dateFormat = env('Date_Format', 'd-m-Y'); // fallback if not defined
        $yearStart = $now->copy()->startOfYear();

        $filterStart = $data['filter_start_date'] ?? $yearStart->format('Y-m-d');
        $filterEnd = $data['filter_end_date'] ?? $now->format('Y-m-d');

        $data['filter_start_date'] = $filterStart;
        $data['filter_end_date'] = $filterEnd;

        $data['start_date'] = Carbon::parse($filterStart)->format($dateFormat);
        $data['end_date'] = Carbon::parse($filterEnd)->format($dateFormat);

        $data['logo'] = ($data['report_type'] ?? null) === 'pdf'
            ? public_path(settings("logo"))
            : asset(settings("logo"));

        $result = Payment::query()
            ->when($filterStart, fn($q) => $q->whereDate('created_at', '>=', $filterStart))
            ->when($filterEnd, fn($q) => $q->whereDate('created_at', '<=', $filterEnd))
            ->when($data['filter_status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($data['filter_client'] ?? null, fn($q, $clientId) => $q->where('client_id', $clientId))
            ->when($data['filter_property'] ?? null, function ($q, $propertyId) {
                $q->whereHas('invoice.booking.property', fn($query) => $query->where('id', $propertyId));
            });

        $data['payments'] = $result->orderBy('created_at', 'desc')->get();

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
