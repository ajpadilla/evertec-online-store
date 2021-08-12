<?php


namespace App\Repositories;


use App\Models\PaymentAttempt;
use Illuminate\Support\Collection;

class PaymentAttemptRepository extends AbstractRepository
{
    /**
     * ProductRepository constructor.
     * @param PaymentAttempt $model
     */
    function __construct(PaymentAttempt $model)
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
            ->select('payment_attempts.*');

        $joins = collect();

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });

        if ($count) {
            return $query->count('payment_attempts.id');
        }

        return $query->orderBy('payment_attempts.id');
    }
}
