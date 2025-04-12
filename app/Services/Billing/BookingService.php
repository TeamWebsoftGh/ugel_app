<?php

namespace App\Services\Billing;

use App\Constants\ResponseType;
use App\Events\BookingEvent;
use App\Models\Billing\Booking;
use App\Models\Billing\BookingPeriod;
use App\Models\Billing\Invoice;
use App\Models\Common\NumberGenerator;
use App\Models\Property\PropertyUnit;
use App\Models\Property\Room;
use App\Repositories\Billing\Interfaces\IBookingRepository;
use App\Repositories\Billing\Interfaces\IInvoiceRepository;
use App\Services\Billing\Interfaces\IBookingService;
use App\Services\Helpers\PropertyHelper;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BookingService extends ServiceBase implements IBookingService
{
    private IBookingRepository $bookingRepo;
    private IInvoiceRepository $invoiceRepo;

    public function __construct(IBookingRepository $bookingRepo, IInvoiceRepository $invoiceRepo)
    {
        parent::__construct();
        $this->bookingRepo = $bookingRepo;
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * List Bookings with Filters
     */
    public function listBookings(array $filters =[], string $orderBy = 'updated_at', string $sortBy = 'desc')
    {
        return $this->bookingRepo->listBookings($filters, $orderBy, $sortBy);
    }

    /**
     * Create a Booking
     */
    public function createBooking(array $data)
    {
        $isHostel = isset($data['room_id']);
        $lockKey = $isHostel
            ? "booking:room:{$data['room_id']}"
            : "booking:unit:{$data['property_unit_id']}";

        $lock = Cache::lock($lockKey, 5); // 10-second lock

        if (!$lock->get()) {
            return $this->errorResponse("Another booking is being processed. Please try again.");
        }

        try {
            DB::beginTransaction();

            if ($isHostel) {
                $room = Room::where('id', $data['room_id'])
                    ->where('is_active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$room || !PropertyHelper::isRoomAvailable($room->id, $data['lease_start_date'], $data['lease_end_date'])) {
                    DB::rollBack();
                    return $this->errorResponse("Room is no longer available.");
                }
            } else {
                $unit = PropertyUnit::where('id', $data['property_unit_id'])
                   // ->where('status', 'active')
                    ->lockForUpdate()
                    ->first();

                if(count($unit->rooms) > 0)
                {
                    return $this->errorResponse("No rooms selected for this unit.");
                }

                if (!$unit || !PropertyHelper::isPropertyUnitAvailable($unit, $data['lease_start_date'], $data['lease_end_date'])) {
                    DB::rollBack();
                    return $this->errorResponse("Property unit is no longer available.");
                }
            }

            if ($this->bookingExists($data['client_id'], $data['booking_period_id'])) {
                DB::rollBack();
                return $this->errorResponse("You have already booked this property.");
            }

            $data['booking_number'] = NumberGenerator::gen(Booking::class);
            $data = $this->prepareBookingData($data);

            $booking = $this->bookingRepo->createBooking($data);
            if (!$booking) {
                DB::rollBack();
                return $this->errorResponse("Failed to create booking.");
            }

            $invoiceData = $this->prepareInvoiceData($booking);
            $invoice = $this->invoiceRepo->create($invoiceData);
            if (!$invoice) {
                DB::rollBack();
                return $this->errorResponse("Failed to create invoice.");
            }

            DB::commit();
            event(new BookingEvent($booking));
            return $this->buildCreateResponse($invoice);

        } catch (\Exception $ex) {
            DB::rollBack();
            log_error(format_exception($ex), new Booking(), 'create-booking-failed');
            return $this->errorResponse("An error occurred while creating the booking.");
        } finally {
            optional($lock)->release();
        }
    }


    /**
     * Update an existing Booking
     */
    public function updateBooking(array $data, Booking $booking): Response
    {
        $isHostel = isset($data['room_id']);
        $lockKey = $isHostel
            ? "booking:room:{$data['room_id']}"
            : "booking:unit:{$data['property_unit_id']}";

        $lock = Cache::lock($lockKey, 5); // 10-second lock

        if (!$lock->get()) {
            return $this->errorResponse("Another booking is being processed. Please try again.");
        }
        try {
            DB::beginTransaction();
            if ($isHostel && $data['room_id'] != $booking->room_id) {
                $room = Room::where('id', $data['room_id'])
                    ->where('is_active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$room || !PropertyHelper::isRoomAvailable($room->id, $data['lease_start_date'], $data['lease_end_date'])) {
                    DB::rollBack();
                    return $this->errorResponse("Room is no longer available.");
                }
            } else if($data['property_unit_id'] != $booking->property_unit_id)
            {
                $unit = PropertyUnit::where('id', $data['property_unit_id'])
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->first();

                if (!$unit || !PropertyHelper::isPropertyUnitAvailable($unit->id, $data['lease_start_date'], $data['lease_end_date'])) {
                    DB::rollBack();
                    return $this->errorResponse("Property unit is no longer available.");
                }
            }


            $data = $this->prepareBookingData($data, $booking);
            $result = $this->bookingRepo->updateBooking($data, $booking);

            $invoiceData = $this->prepareInvoiceData($booking);
            $booking->invoice()->updateOrCreate(['booking_id' => $booking->id], $invoiceData);

            DB::commit();
            return $this->buildUpdateResponse($booking, $result);

        } catch (\Exception $ex) {
            DB::rollBack();
            log_error(format_exception($ex), $booking, 'update-booking-failed');
            return $this->errorResponse("An error occurred while updating the booking.");
        }
    }

    /**
     * Find Booking By ID
     */
    public function findBookingById($id): ?Booking
    {
        return $this->bookingRepo->findBookingById($id);
    }

    /**
     * Delete a Booking
     */
    public function deleteBooking(Booking $booking): Response
    {
        $booking->invoice()->delete();
        $result = $this->bookingRepo->deleteBooking($booking);
        return $this->buildDeleteResponse($result);
    }

    /**
     * Delete Multiple Bookings
     */
    public function deleteMultipleBookings(array $ids): Response
    {
        $result = $this->bookingRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }

    /**
     * Check if a booking already exists
     */
    private function bookingExists(int $clientId, int $bookingPeriodId): bool
    {
        return Booking::where(['client_id' => $clientId, 'booking_period_id' => $bookingPeriodId])->exists();
    }

    /**
     * Prepare Booking Data before saving
     */
    private function prepareBookingData(array $data, Booking $booking = null): array
    {
        if (isset($data['booking_period_id'])) {
            $period = BookingPeriod::find($data['booking_period_id']);
            $data['lease_start_date'] = $data['lease_start_date']??$period?->lease_start_date;
            $data['lease_end_date'] = $data['lease_end_date']??$period?->lease_end_date;
            $data['sub_total'] = PropertyHelper::getPropertyUnitPrice($data['property_unit_id'], $data['booking_period_id']);
            $data['total_price'] = $data['sub_total'];
        }

        $data['status'] = $data['status'] ?? 'pending';
        return $data;
    }

    /**
     * Prepare Invoice Data for Booking
     */
    private function prepareInvoiceData(Booking $booking): array
    {
        return [
            'booking_id' => $booking->id,
            'invoice_number' => NumberGenerator::gen(Invoice::class),
            'client_id' => $booking->client_id,
            'sub_total_amount' => $booking->sub_total,
            'total_amount' => $booking->total_price,
            'created_by' => $booking->created_by,
            'invoice_date' => $booking->booking_date,
            'company_id' => $booking->company_id,
        ];
    }


}
