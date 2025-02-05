<?php

namespace App\Services\Interfaces;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Collection;

interface IOrderService extends IBaseService
{
    public function listOrders(string $order = 'id', string $sort = 'desc'): Collection;

    public function createOrder(array $params);

    public function findOrderById(int $id) : Order;

    public function updateOrder(array $params, Order $order);

    public function changeStatus(bool $status, Order $order);

    public function submitOrder(Order $order, $status = 2);

    public function deleteOrder(Order $order);

    public function getCreateOrder();
}
