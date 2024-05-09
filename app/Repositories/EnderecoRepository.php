<?php

namespace App\Repositories;

use App\Models\Endereco;
use App\Repositories\Abstracts\BaseRepository;

class EnderecoRepository extends BaseRepository
{
    public function __construct(Endereco $endereco)
    {
        parent::__construct($endereco);
    }
}