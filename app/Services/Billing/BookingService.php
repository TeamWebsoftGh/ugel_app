<?php

namespace App\Services\Billing;

use App\Constants\ResponseType;
use App\Models\Billing\Booking;
use App\Models\Billing\BookingPeriod;
use App\Models\Billing\Invoice;
use App\Models\Common\NumberGenerator;
use App\Repositories\Billing\Interfaces\IBookingRepository;
use App\Repositories\Billing\Interfaces\IInvoiceRepository;
use App\Services\Billing\Interfaces\IBookingService;
use App\Services\Helpers\PropertyHelper;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;
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
    public function createBooking(array $data): Response
    {
        try {
            // Check if booking already exists for the client and booking period
            if ($this->bookingExists($data['client_id'], $data['booking_period_id'])) {
                return $this->errorResponse("You have already booked this property.");
            }

            DB::beginTransaction();

            // Generate Booking Number & Prepare Data
            $data['booking_number'] = NumberGenerator::gen(Booking::class);
            $data = $this->prepareBookingData($data);

            // Create Booking
            $booking = $this->bookingRepo->createBooking($data);

            if (!$booking) {
                DB::rollBack();
                return $this->errorResponse("Failed to create booking. Please try again.");
            }

            // Prepare & Create Invoice
            $invoiceData = $this->prepareInvoiceData($booking);
            $invoice = $this->invoiceRepo->create($invoiceData);

            if (!$invoice) {
                DB::rollBack();
                return $this->errorResponse("Failed to create invoice. Please try again.");
            }

            DB::commit();
            return $this->buildCreateResponse($booking);

        } catch (\Exception $ex) {
            DB::rollBack();
            log_error(format_exception($ex), new Booking(), 'create-booking-failed');
            return $this->errorResponse("An error occurred while creating the booking.");
        }
    }


    /**
     * Update an existing Booking
     */
    public function updateBooking(array $data, Booking $booking): Response
    {
        try {
            DB::beginTransaction();

            $data = $this->prepareBookingData($data, $booking);
            $result = $this->bookingRepo->updateBooking($data, $booking);

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
            $data['lease_start_date'] = $period?->lease_start_date;
            $data['lease_end_date'] = $period?->lease_end_date;
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
            'company_id' => $booking->company_id,
        ];
    }

    /**
     * Build error response
     */
    private function errorResponse(string $message): Response
    {
        $this->response->status = ResponseType::ERROR;
        $this->response->message = $message;

        return $this->response;
    }
}
