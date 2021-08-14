<?php


namespace App\Repositories\RepositoryInterface;


use Illuminate\Support\Collection;

interface PaymentAttemptRepositoryInterface extends RepositoryInterface
{
    public function addJoin(Collection &$joins, $table, $first, $second, $join_type = 'inner');

    public function search(array $filters = [], $count = false);
}
