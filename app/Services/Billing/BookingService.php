<?php

namespace App\Services\Billing;

use App\Constants\ResponseType;
use App\Models\Billing\Booking;
use App\Models\Billing\BookingPeriod;
use App\Models\Billing\PropertyUnitPrice;
use App\Repositories\Billing\Interfaces\IBookingRepository;
use App\Services\Billing\Interfaces\IBookingService;
use App\Services\Helpers\PropertyHelper;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingService extends ServiceBase implements IBookingService
{
    private IBookingRepository $bookingRepo;

    /**
     * BookingService constructor.
     * @param IBookingRepository $booking
     */
    public function __construct(IBookingRepository $booking)
    {
        parent::__construct();
        $this->bookingRepo = $booking;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listBookings(array $filters =[], string $orderBy = 'updated_at', string $sortBy = 'desc')
    {
        return $this->bookingRepo->listBookings($filters, $orderBy, $sortBy);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createBooking(array $data): Response
    {
        $exists = Booking::where(['property_id' => $data['property_id'], 'booking_period_id' => $data['booking_period_id']])->exists();
        if($exists)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = "You have already booked this property.";

            return $this->response;
        }
        DB::beginTransaction();

        if(isset($data['booking_period_id']))
        {
            $period = BookingPeriod::find($data['booking_period_id']);
            $data['lease_start_date'] = $period?->lease_start_date;
            $data['lease_end_date'] = $period?->lease_end_date;
            $data['sub_total'] = PropertyHelper::getPropertyUnitPrice($data['property_unit_id'], $data['booking_period_id']);
            $data['total_price'] = $data['sub_total'];
        }

        if(isset($data['status']))
        {
            $data['status'] = 'pending';
        }

        $booking = $this->bookingRepo->createBooking($data);

        DB::commit();
        return $this->buildCreateResponse($booking);
    }


    /**
     * @param array $data
     * @param Booking $booking
     * @return Response
     */
    public function updateBooking(array $data, Booking $booking): Response
    {
        //Declaration
        DB::beginTransaction();
        $result = $this->bookingRepo->updateBooking($data, $booking);
        DB::commit();
        return $this->buildUpdateResponse($booking, $result);
    }

    /**
     * @param int $id
     * @return Booking|null
     */
    public function findBookingById($id): ?Booking
    {
        return $this->bookingRepo->findBookingById($id);
    }

    /**
     * @param Booking $booking
     * @return Response
     */
    public function deleteBooking(Booking $booking)
    {
        //Declaration
        $result = $this->bookingRepo->deleteBooking($booking);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleBookings(array $ids)
    {
        //Declaration
        $result = $this->bookingRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
