<?php

namespace App\Repositories;

use App\Models\Usuario;
use App\Repositories\Abstracts\BaseRepository;

class UsuarioRepository extends BaseRepository
{
    public function __construct(Usuario $usuario)
    {
        parent::__construct($usuario);
    }
}