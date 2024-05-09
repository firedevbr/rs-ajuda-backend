<?php

namespace App\Repositories;

use App\Models\Desaparecimento;
use App\Repositories\Abstracts\BaseRepository;

class DesaparecimentoRepository extends BaseRepository
{
    public function __construct(Desaparecimento $desaparecimento)
    {
        parent::__construct($desaparecimento);
    }

    public function getAll(array $params)
    {
        $query = $this->model->newQuery();

        // Aplicar filtro por cidade e bairro se fornecidos
        if (!empty($params['cidade'])) {
            $query->whereHas('endereco.cidade', function ($q) use ($params) {
                $q->where('nome', 'LIKE', '%' . $params['cidade'] . '%');
            });
        }

        if (!empty($params['pessoa'])) {
            $query->whereHas('pessoa', function ($q) use ($params) {
                $q->where('nome', 'LIKE', '%' . $params['pessoa'] . '%');
            });
        }

        if (!empty($params['bairro'])) {
            $query->whereHas('endereco', function ($q) use ($params) {
                $q->where('bairro', 'LIKE', '%' . $params['bairro'] . '%');
            });
        }

        // Outros filtros e ordenação
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['responsavel_id'])) {
            $query->where('responsavel_id', $params['responsavel_id']);
        }

        $query->orderBy($params['sort'], $params['order']);

        // Paginação
        $perPage = $params['perPage'] ?? 10;
        $page = $params['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
