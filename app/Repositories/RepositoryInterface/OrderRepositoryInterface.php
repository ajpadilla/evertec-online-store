<?php


namespace App\Repositories\RepositoryInterface;


use Illuminate\Support\Collection;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner');

    public function search(array $filters = [], $count = false);
}
