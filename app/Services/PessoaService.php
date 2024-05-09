<?php

namespace App\Services;

use App\Repositories\PessoaRepository;
use App\Services\Abstracts\BaseService;

class PessoaService extends BaseService
{
    protected $repository;

    public function __construct(PessoaRepository $pessoaRepository)
    {
        $this->repository = $pessoaRepository;
    }

    protected function repository()
    {
        return $this->repository;
    }
}