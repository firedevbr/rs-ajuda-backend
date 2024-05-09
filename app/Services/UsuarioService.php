<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;
use App\Services\Abstracts\BaseService;

class UsuarioService extends BaseService
{
    protected $repository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->repository = $usuarioRepository;
    }

    protected function repository()
    {
        return $this->repository;
    }
}