<?php

namespace App\Repositories;

use App\Models\Pessoa;
use App\Repositories\Abstracts\BaseRepository;

class PessoaRepository extends BaseRepository
{
    public function __construct(Pessoa $pessoa)
    {
        parent::__construct($pessoa);
    }
}