<?php


namespace App\Repositories;


use App\Models\Order;
use App\Models\Product;
use App\Repositories\RepositoryInterface\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    /**
     * OrderRepository constructor.
     * @param Order $model
     */
    function __construct(Order $model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $joins
     * @param $table
     * @param $first
     * @param $second
     * @param string $join_type
     */
    public function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner'): void
    {
        if (!$joins->has($table)) {
            $joins->put($table, json_encode(compact('first', 'second', 'join_type')));
        }
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], $count = false): Builder
    {
        $query = $this->model
            ->distinct()
            ->select('orders.*');

        $joins = collect();

        if(isset($filters['user_id'])){
            $this->addJoin($joins, 'users', 'orders.user_id', 'users.id');
            $query->where('users.id', $filters['user_id']);
        }

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if ($count) {
            return $query->count('orders.id');
        }

        return $query->orderBy('orders.id');
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getByUserId($user_id): Model
    {
        return $this->search(['user_id' => $user_id])->get()->first();
    }

    /**
     * @param Order $order
     * @param Product $product
     * @return bool
     */
    public function associateProduct(Order $order, Product $product): bool
    {
        $order->amount = $product->price;
        $order->product()->associate($product);
        return $order->save();
    }

}
