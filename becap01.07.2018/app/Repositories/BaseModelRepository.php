<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseModelRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findWithParams($params)
    {
        return $this->model->where($params);
    }

    public function whereNotNull($param)
    {
        return $this->model->whereNotNull($param);
    }

    public function paginate($count)
    {
        return $this->model->paginate($count);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function isActive()
    {
        return $this->model->where('is_active', true);
    }

    public function count()
    {
        return $this->model->count();
    }

    public function latest($count)
    {
        return $this->model->orderBy('id', 'desc')->take($count);
    }

    public function order($column, $sort)
    {
        return $this->model->orderBy($column, $sort);
    }

    public function whereCreatedAt($date)
    {
        return $this->model->whereDate('created_at', $date);
    }

    public function whereBetweenDatesCreatedAt($date, $date2)
    {
        return $this->model->whereBetween('created_at', array($date, $date2));
    }

    public function join($table, $table_column, $parent_table)
    {
        return $this->model->join($table, $table_column, '=', $parent_table);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}