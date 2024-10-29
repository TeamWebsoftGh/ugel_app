<?php

namespace App\Services;


use App\Enums\ApplicationStatusType;
use App\Enums\BookingStatus;
use App\Enums\ReportTypes;
use App\Mail\Customer\BookingSubmittedMail;
use App\Mail\Customer\CustomerRegistrationMail;
use App\Mail\Customer\SendRefereeMail;
use App\Models\Applicant;
use App\Models\ApplicantReferee;
use App\Models\Booking;
use App\Models\Division;
use App\Models\Payment;
use App\Models\RefereeLogin;
use App\Models\Resource\Category;
use App\Models\Traits\ReportTrait;
use App\Repositories\Interfaces\IApplicantRepository;
use App\Services\Interfaces\IComplaintService;
use App\Services\Interfaces\IProgrammeService;
use App\Services\Interfaces\IReportService;
use App\Services\Interfaces\ISectionService;
use App\Services\Interfaces\ISemesterService;
use App\Services\Interfaces\ITerminationService;
use Illuminate\Support\Facades\DB;

class ReportService extends ServiceBase implements IReportService
{
    use ReportTrait;

    private $facilitatorService;
    private $semesterService;
    private $sectionService;
    private $categoryService;
    private $programmeService;

    public function __construct(
        ISemesterService $semester,
        ISectionService $section,
        ITerminationService $facilitator,
        IComplaintService $category,
        IProgrammeService $programme
    ){
        $this->facilitatorService = $facilitator;
        $this->semesterService = $semester;
        $this->sectionService = $section;
        $this->categoryService = $category;
        $this->programmeService = $programme;
    }

    public function listBookingByCategory(Category $division)
    {
        // TODO: Implement listBookingByCategory() method.
    }

    public function listBookingByStaff()
    {
        // TODO: Implement listBookingByStaff() method.
    }

    public function statBooking(array $param)
    {
        $bookings = Booking::with('section', 'customer')->select(DB::raw($param['group_by'].',
            sum(case when booking_status_id = '.BookingStatus::CONFIRMED.' then 1 else 0 end) AS Booked,
            sum(case when booking_status_id != '.BookingStatus::CONFIRMED.' then 1 else 0 end) AS Inquiry,
             count(*) as Total'))
            ->whereBetween('created_at', [$param['start_date'], $param['end_date']])
            ->groupBy($param['group_by'])
            ->get();

       // $data = ['contents' => $bookings, 'title' => $param['title']];

       // $content = view('admin.simple-reports.templates.booking.by-sales-rep')->with($data);

        return $bookings;
    }

    public function getBookingSummary(array $param)
    {
        $bookings = $this->sectionService->listSections(null, 'start_date', 'asc')
            ->whereBetween('start_date', [$param['start_date'], $param['end_date']])
            ->groupBy($param['group_by']);

        return $bookings;
    }

    public function prepareReport(array $param)
    {
        $result = $this->resolveViewFromReportType($param['reportType']);

        if (in_array($param['reportType'], ReportTypes::$simpleBookingStatReports2))
        {
            $param['group_by'] = $result['group_by'];
            $bookings = $this->statBooking($param);
            $view = 'admin.simple-reports.templates.';
        }

        else if ($param['reportType'] == ReportTypes::SIMPLE_SUMMARY_BOOKING_REPORT)
        {
            $param['group_by'] = $result['group_by'];
            $bookings = $this->getBookingSummary($param);
            $view = 'admin.simple-reports.templates.';
        }
        else{
            $param['group_by'] = $result['group_by'];
            $bookings = $this->statBooking($param);
        }


        $data = ['contents' => $bookings, 'title' => $param['reportType']];

        $content = view($result['view'])->with($data);

        return $content;
    }

    public function statBookingByProgram(array $data)
    {
        if (isset($data['']))
        $data = DB::table('bookings')
            ->select(DB::raw('section_id as Sales_Rep,
            sum(case when booking_status_id = 5 then 1 else 0 end) AS Booked,
            sum(case when booking_status_id != 5 then 1 else 0 end) AS Inquiry,
             count(*) as Total'))
            ->where('status', '<>', 1)
            ->groupBy('section_id')
            ->get();

        return $data;
    }

    public function findPaymentById(int $id)
    {
        // TODO: Implement findPaymentById() method.
    }

    public function findPayment(array $where)
    {
        // TODO: Implement findPayment() method.
    }

    public function updatePayment(array $params, Payment $payment)
    {
        // TODO: Implement updatePayment() method.
    }

    public function resolveViewFromReportType($reportType){
        switch($reportType){
            case ReportTypes::SIMPLE_BOOKING_REPORT_PROGRAM:
                $data['view'] = 'admin.simple-reports.templates.booking.by-programme';
                $data['group_by'] = 'section_id';
                break;
            case ReportTypes::SIMPLE_BOOKING_REPORT_ORG:
                $data['view'] = 'admin.simple-reports.templates.booking.by-organization';
                $data['group_by'] = 'institution_id';
                break;
            case ReportTypes::SIMPLE_SUMMARY_BOOKING_REPORT:
                $data['view'] = 'admin.simple-reports.templates.booking.summary';
                $data['group_by'] = 'semester_id';
                break;
            default:
                $data['view'] = 'admin.simple-reports.templates.booking.by-sales-rep';
                $data['group_by'] = 'sales_rep';
                break;
        }
        return $data;
    }

    public function statBookingByStaff(array $data)
    {
        // TODO: Implement statBookingByStaff() method.
    }
}
