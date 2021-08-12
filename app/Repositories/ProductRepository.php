<?php


namespace App\Repositories;


use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository extends  AbstractRepository
{
    /**
     * ProductRepository constructor.
     * @param Product $model
     */
    function __construct(Product $model)
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
    private function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner')
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
    public function search(array $filters = [], $count = false)
    {
        $query = $this->model
            ->distinct()
            ->select('products.*');

        $joins = collect();

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if (isset($filters['price'])) {
            $query->ofPrice($filters['price']);
        }

        if ($count) {
            return $query->count('products.id');
        }

        return $query->orderBy('products.id');
    }
}
