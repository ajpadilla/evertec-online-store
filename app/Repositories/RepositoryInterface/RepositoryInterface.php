<?php


namespace App\Repositories\RepositoryInterface;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(): Collection;

    public function create(array  $input): Model;

    public function save(Model $model): bool;

    public function update(Model $model, array $attributes): bool;

    public function delete(Model $model): ?bool;

    public function find($id): ?Model;

    function getById($id): ?Model;
}
