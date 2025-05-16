<?php

namespace App\Services\Billing;

use App\Models\Billing\BookingPeriod;
use App\Models\Billing\PropertyUnitPrice;
use App\Repositories\Billing\Interfaces\IBookingPeriodRepository;
use App\Services\Billing\Interfaces\IBookingPeriodService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingPeriodService extends ServiceBase implements IBookingPeriodService
{
    private IBookingPeriodRepository $bookingPeriodRepo;

    /**
     * BookingPeriodService constructor.
     * @param IBookingPeriodRepository $bookingPeriod
     */
    public function __construct(IBookingPeriodRepository $bookingPeriod)
    {
        parent::__construct();
        $this->bookingPeriodRepo = $bookingPeriod;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listBookingPeriods(array $filters =[], string $orderBy = 'updated_at', string $sortBy = 'desc')
    {
        return $this->bookingPeriodRepo->listBookingPeriods($filters, $orderBy, $sortBy);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createBookingPeriod(array $data): Response
    {
        DB::beginTransaction();
        $bookingPeriod = $this->bookingPeriodRepo->createBookingPeriod($data);

        // Store Property Unit Prices
        $propertyUnitIds = $data['property_unit_ids'];
        $prices = $data['prices'];
        $rentTypes = $data['rent_types'];

        foreach ($propertyUnitIds as $index => $propertyUnitId) {
            PropertyUnitPrice::updateOrCreate(
                [
                    'property_unit_id' => $propertyUnitId,
                    'booking_period_id' => $bookingPeriod->id
                ],
                [
                    'price' => $prices[$index],
                    'rent_type' => $rentTypes[$index],
                ]
            );
        }

        DB::commit();
        return $this->buildCreateResponse($bookingPeriod);
    }


    /**
     * @param array $data
     * @param BookingPeriod $bookingPeriod
     * @return Response
     */
    public function updateBookingPeriod(array $data, BookingPeriod $bookingPeriod): Response
    {
        //Declaration
        DB::beginTransaction();
        $result = $this->bookingPeriodRepo->updateBookingPeriod($data, $bookingPeriod);

        // Store Property Unit Prices
        $propertyUnitIds = $data['property_unit_ids'];
        $prices = $data['prices'];
        $rentTypes = $data['rent_types'];

        foreach ($propertyUnitIds as $index => $propertyUnitId) {
            PropertyUnitPrice::updateOrCreate(
                [
                    'property_unit_id' => $propertyUnitId,
                    'booking_period_id' => $bookingPeriod->id
                ],
                [
                    'price' => $prices[$index],
                    'rent_type' => $rentTypes[$index],
                ]
            );
        }

        DB::commit();
        return $this->buildUpdateResponse($bookingPeriod, $result);
    }

    /**
     * @param int $id
     * @return BookingPeriod|null
     */
    public function findBookingPeriodById($id): ?BookingPeriod
    {
        return $this->bookingPeriodRepo->findBookingPeriodById($id);
    }

    /**
     * @param BookingPeriod $bookingPeriod
     * @return Response
     */
    public function deleteBookingPeriod(BookingPeriod $bookingPeriod)
    {
        //Declaration
        $result = $this->bookingPeriodRepo->deleteBookingPeriod($bookingPeriod);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleBookingPeriods(array $ids)
    {
        //Declaration
        $result = $this->bookingPeriodRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
