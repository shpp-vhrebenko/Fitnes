<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function all();
    public function find($id);
    public function findWithParams($params);
    public function whereNotNull($param);
    public function paginate($count);
    public function create($data);
    public function update($id, $data);
    public function isActive();
    public function count();
    public function latest($count);
    public function order($column, $sort);
    public function whereCreatedAt($date);
    public function whereBetweenDatesCreatedAt($date, $date2);
    public function join($table, $table_column, $parent_table);
    public function destroy($id);
}