<?php

namespace App\Services\Abstracts;

use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseService
{
    abstract protected function repository();

    public function getAll(array $params = ['page' => 1, 'perPage' => 10, 'sort' => 'id', 'order' => 'asc'])
    {
        return $this->repository()->all($params);
    }

    public function find($conditions)
    {
        return $this->repository()->find($conditions);
    }
    
    public function getById($id)
    {
        $entity = $this->repository()->find($id);
        if (!$entity) {
            throw new ModelNotFoundException('ContaEntidade não encontrada');
        }
        return $entity;
    }

    public function create(array $data)
    {
        return $this->repository()->create($data);
    }

    public function update($id, array $data)
    {
        $entity = $this->getById($id);

        if (!$entity) {
            throw new ModelNotFoundException('Entidade não encontrada');
        }

        return $this->repository()->update($entity, $data);
    }

    public function delete($id)
    {
        $entity = $this->getById($id);

        if (!$entity) {
            throw new ModelNotFoundException('ContaEntidade não encontrada');
        }

        return $this->repository()->delete($entity);
    }

    public function deleteMany($conditions)
    {
        return $this->repository()->deleteMany($conditions);
    }
}
