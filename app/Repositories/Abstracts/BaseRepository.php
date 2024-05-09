<?php

namespace App\Repositories\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class  BaseRepository
{
    protected $model;

    public function __construct(Model $agencia)
    {
        $this->model = $agencia;
    }

    public function allNotPaged()
    {
        return $this->model::all();
    }

    public function all(array $params = ['page' => 1, 'perPage' => 10, 'sort' => 'id', 'order' => 'asc'])
    {
        $query = $this->model::query();

        $query = $this->applyFilters($query, $params);
        $query = $this->applySorting($query, $params);

        return $this->getPaginated($query, $params);
    }

    public function find($id)
    {
        $entity = $this->model->find($id);

        if (!$entity) {
            return null;
        }

        return $entity;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data)
    {
        $model->update($data);
        return $model;
    }

    public function delete(Model $model)
    {
        return $model->delete();
    }


    //Using a whereRaw inside a where, to make a case insensitive search in any SGDB and at the same time, prevent SQL injection.
    protected function applyFilters($query, array $params)
    {
        $notFilterable = ['perPage', 'page', 'sort', 'order'];
    
        foreach ($params as $key => $value) {
            if (!in_array($key, $notFilterable) && !is_null($value)) {
                if (is_numeric($value)) {
                    // Handles numeric values
                    $query->where($key, $value);
                } elseif (is_bool($value) || $value === 'true' || $value === 'false') {
                    // Handles boolean values
                    $query->where($key, filter_var($value, FILTER_VALIDATE_BOOLEAN));
                } else {
                    // handles string values
                    $query->where(function ($query) use ($key, $value) {
                        $query->whereRaw('LOWER(' . $key . ') LIKE ?', ['%' . strtolower($value) . '%']);
                    });
                }
            }
        }
    
        return $query;
    }

    protected function applySorting($query, array $params)
    {
        return $query->orderBy($params['sort'], $params['order']);
    }

    protected function getPaginated($query, array $params)
    {
        return $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
    }
}
