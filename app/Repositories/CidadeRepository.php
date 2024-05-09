<?php

namespace App\Repositories;

use App\Models\Cidade;
use App\Repositories\Abstracts\BaseRepository;

class CidadeRepository extends BaseRepository
{
    public function __construct(Cidade $cidade)
    {
        parent::__construct($cidade);
    }
}