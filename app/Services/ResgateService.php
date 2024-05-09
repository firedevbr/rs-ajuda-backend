<?php

namespace App\Services;

use App\Repositories\ResgateRepository;
use App\Services\Abstracts\BaseService;

class ResgateService extends BaseService
{
    protected $repository;

    public function __construct(ResgateRepository $resgateRepository)
    {
        $this->repository = $resgateRepository;
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
