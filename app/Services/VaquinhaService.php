<?php

namespace App\Services;

use App\Repositories\VaquinhaRepository;
use App\Services\Abstracts\BaseService;

class VaquinhaService extends BaseService
{
    protected $repository;

    public function __construct(VaquinhaRepository $vaquinhaRepository)
    {
        $this->repository = $vaquinhaRepository;
    }

    protected function repository()
    {
        return $this->repository;
    }

    public function filtrar(array $params)
    {
        return $this->repository->getAll($params);
    }
}
