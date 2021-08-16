<?php


namespace App\Repositories;

use App\Models\User;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{

    /**
     * UserRepository constructor.
     * @param User $model
     */
    function __construct(User $model)
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
            ->select('users.*');

        $joins = collect();

        $joins->each(function ($item, $key) use (&$query) {
            $item = json_decode($item);
            $query->join($key, $item->first, '=', $item->second, $item->join_type);
        });



        if ($count) {
            return $query->count('users.id');
        }

        return $query->orderBy('users.id');
    }

}
