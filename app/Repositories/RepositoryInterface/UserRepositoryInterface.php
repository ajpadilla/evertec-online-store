<?php


namespace App\Repositories\RepositoryInterface;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner'): void;

    public function search(array $filters = [], $count = false): Builder;
}
