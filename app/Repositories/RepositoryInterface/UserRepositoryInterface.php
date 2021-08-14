<?php


namespace App\Repositories\RepositoryInterface;


use Illuminate\Support\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner');

    public function search(array $filters = [], $count = false);
}
