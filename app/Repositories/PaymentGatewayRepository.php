<?php

namespace App\Repositories;

use App\Models\Payment\PaymentGateway;
use App\Repositories\Interfaces\IPaymentGatewayRepository;
use Illuminate\Support\Collection;

class PaymentGatewayRepository extends BaseRepository implements IPaymentGatewayRepository
{
    /**
     * PaymentGatewayRepository constructor.
     * @param PaymentGateway $paymentGateway
     */
    public function __construct(PaymentGateway $paymentGateway)
    {
        parent::__construct($paymentGateway);
        $this->model = $paymentGateway;
    }


    /**
     * List all PaymentGateways
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPaymentGateways(string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return PaymentGateway
     */
    public function createPaymentGateway(array $data): PaymentGateway
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return PaymentGateway
     */
    public function findPaymentGatewayById(int $id): PaymentGateway
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $data
     * @param PaymentGateway $paymentGateway
     * @return bool
     */
    public function updatePaymentGateway(array $data, PaymentGateway $paymentGateway): bool
    {
        return $this->update($data, $paymentGateway->id);
    }


    /**
     * @param PaymentGateway $paymentGateway
     * @return bool
     */
    public function deletePaymentGateway(PaymentGateway $paymentGateway)
    {
        return $this->delete($paymentGateway->id);
    }
}
