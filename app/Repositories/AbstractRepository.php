<?php


namespace App\Repositories;


use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AbstractRepository
{
    /**
     * @var Model $model
     */
    protected $model;

    /**
     * @return Collection|static[]
     */
    function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param $id
     *
     * @return Model
     */
    function getById($id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $id
     * @param string[] $columns
     * @return Model
     */
    function find($id, $columns = array('*')): ?Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @return Model
     */
    function first(): Model
    {
        return $this->model->first();
    }

    /**
     * @param array $input
     *
     * @return Model
     */
    function create(array $input): Model
    {
        return $this->model->create($input);
    }

    /**
     * @throws Exception
     */
    public function deleteAll(): ?bool
    {
        $this->model->delete();
    }

    /**
     * @param Model $model
     *
     * @return bool
     */
    public function save(Model $model): bool
    {
        return $model->save();
    }

    public function update(Model $model, array $attributes): bool
    {
        return $model->update($attributes);
    }

    /**
     * Delete an Eloquent Model from database
     *
     * @param Model $model
     *
     * @return bool|null
     * @throws Exception
     */
    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }
}
