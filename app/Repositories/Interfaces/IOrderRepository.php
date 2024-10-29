<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Support\Collection;

interface IOrderRepository extends IBaseRepository
{
    public function listOrders(string $order = 'id', string $sort = 'desc'): Collection;

    public function createOrder(array $params) : Order;

    public function findOrderById(int $id) : Order;

    public function updateOrder(array $params, Order $order) : bool;

    public function deleteOrder(Order $order);

    public function saveImages(Collection $collection, Order $order, $type);
}
