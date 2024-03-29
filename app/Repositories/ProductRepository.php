<?php


namespace App\Repositories;


use App\Models\Product;
use App\Repositories\RepositoryInterface\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository extends  AbstractRepository implements ProductRepositoryInterface
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
        /** @var Builder $query */
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
