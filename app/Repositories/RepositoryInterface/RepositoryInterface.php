<?php


namespace App\Repositories\RepositoryInterface;


use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all();

    public function create(array  $input);

    public function save(Model $model);

    public function update(Model $model, array $attributes);

    public function delete(Model $model);

    public function find($id);

    function getById($id);
}
