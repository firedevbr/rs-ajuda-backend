<?php

namespace App\Services;

use App\Repositories\DesaparecimentoRepository;
use App\Services\Abstracts\BaseService;

class DesaparecimentoService extends BaseService
{
    protected $repository;

    public function __construct(DesaparecimentoRepository $desaparecimentoRepository)
    {
        $this->repository = $desaparecimentoRepository;
    }

    protected function repository()
    {
        return $this->repository;
    }

    public function create(array $data)
    {
        $data['responsavel_id'] = auth()->user()->id;
        $data['status'] = "Aguardando Ajuda";
        return parent::create($data);
    }

    public function filtrar(array $params)
    {
        return $this->repository->getAll($params);
    }
}
