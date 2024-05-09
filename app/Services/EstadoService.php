<?php

namespace App\Services;

use App\Repositories\EstadoRepository;
use App\Services\Abstracts\BaseService;

class EstadoService extends BaseService
{
    protected $repository;

    public function __construct(EstadoRepository $estadoRepository)
    {
        $this->repository = $estadoRepository;
    }

    protected function repository()
    {
        return $this->repository;
    }
}