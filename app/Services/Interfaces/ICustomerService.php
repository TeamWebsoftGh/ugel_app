<?php

namespace App\Services\Interfaces;

use App\Models\Customer;
use Illuminate\Support\Collection;

interface ICustomerService extends IBaseService
{
    public function listCustomers(string $order = 'id', string $sort = 'desc'): Collection;

    public function createCustomer(array $params);

    public function findCustomerById(int $id) : Customer;

    public function updateCustomer(array $params, Customer $customer);

    public function changeStatus(bool $status, Customer $customer);

    public function resetPassword(Customer $customer);

    public function deleteCustomer(Customer $customer);

    public function getCreateCustomer();
}
