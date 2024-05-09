<?php

namespace App\Services;

use App\Repositories\CidadeRepository;
use App\Services\Abstracts\BaseService;

class CidadeService extends BaseService
{
    protected $repository;

    public function __construct(CidadeRepository $cidadeRepository)
    {
        $this->repository = $cidadeRepository;
    }

    protected function repository()
    {
        return $this->repository;
    }
}