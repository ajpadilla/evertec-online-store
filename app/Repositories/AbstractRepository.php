<?php


namespace App\Repositories;


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
    function all()
    {
        return $this->model->all();
    }

    /**
     * @param $id
     *
     * @return Model
     */
    function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    function first()
    {
        return $this->model->first();
    }

    /**
     * @param array $input
     *
     * @return Model
     */
    function create(array $input)
    {
        return $this->model->create($input);
    }

    /**
     * @throws \Exception
     */
    public function deleteAll()
    {
        $this->model->delete();
    }

    /**
     * @param Model $model
     *
     * @return bool
     */
    public function save(Model $model)
    {
        return $model->save();
    }

    public function update(Model $model, array $attributes)
    {
        return $model->update($attributes);
    }

    /**
     * Delete an Eloquent Model from database
     *
     * @param Model $model
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }
}
