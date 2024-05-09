<?php

namespace App\Repositories;

use App\Models\Estado;
use App\Repositories\Abstracts\BaseRepository;

class EstadoRepository extends BaseRepository
{
    public function __construct(Estado $estado)
    {
        parent::__construct($estado);
    }
}