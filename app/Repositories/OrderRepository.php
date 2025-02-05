<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDocument;
use App\Models\Traits\UploadableTrait;
use App\Repositories\Interfaces\IOrderRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class OrderRepository extends BaseRepository implements IOrderRepository
{
    use UploadableTrait;
    /**
     * OrderRepository constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
        $this->model = $order;
    }

    /**
     * List all the Orders
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $orders
     */
    public function listOrders(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * Create the Order
     *
     * @param array $data
     *
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        return $this->create($data);
    }

    /**
     * Find the Order by id
     *
     * @param int $id
     *
     * @return Order
     */
    public function findOrderById(int $id): Order
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Order
     *
     * @param array $params
     *
     * @param Order $order
     * @return bool
     */
    public function updateOrder(array $params, Order $order): bool
    {
        return $order->update($params);
    }

    /**
     * @param Order $order
     * @return bool|null
     * @throws \Exception
     */
    public function deleteOrder(Order $order)
    {
        return $order->delete();
    }

    /**
     * @param Collection $collection
     * @param Order $order
     * @param $type
     * @return void
     */
    public function saveImages(Collection $collection, Order $order, $type)
    {
        $count = 1;
        $collection->each(function (UploadedFile $file) use ($order, $type, $count) {
            $count ++;
            $filename = $order->title.'_'.$count.''.time();
            $src = $this->uploadOne($file, 'orders', $filename);
            $productImage = new OrderDocument([
                'name' => $filename,
                'order_id' => $order->id,
                'src' => $src,
                'type' => $type,
                'uploaded_by' => "system"
            ]);
            $order->orderDocuments()->save($productImage);
        });
    }

//    public function getOrdersForReports(){
//        $data = collect($this->listOrders())
//            ->whereBetween('created_at', $this->year_range)->unique('invigilator.bank_branch')
//            ->groupBy(['enrollment.academic_year', 'invigilator.bank.name','invigilator.bank_branch.branch_name']);
//        return $data ;
//    }
}
