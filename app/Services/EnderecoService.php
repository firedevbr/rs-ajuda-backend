<?php

namespace App\Services;

use App\Jobs\RegisterAddressGeoData;
use App\Models\Cidade;
use App\Models\Estado;
use App\Repositories\EnderecoRepository;
use App\Services\Abstracts\BaseService;

class EnderecoService extends BaseService
{
    protected $repository;

    public function __construct(EnderecoRepository $enderecoRepository)
    {
        $this->repository = $enderecoRepository;
    }

    protected function repository()
    {
        return $this->repository;
    }

    public function create(array $data)
    {
        $estado = Estado::firstOrCreate(
            ['nome' => $data['estado']]
        );

        // Verifique se a cidade existe, caso contrário, crie-a associada ao estado
        $cidade = Cidade::firstOrCreate(
            ['nome' => $data['cidade'], 'estado_id' => $estado->id]
        );

        // Atualize os dados do endereço com a cidade e o estado identificados
        $data['cidade_id'] = $cidade->id;
        unset($data['estado']); // Remova o campo que não é mais necessário

        $endereco = parent::create($data);
        RegisterAddressGeoData::dispatch($endereco);
        return $endereco;
    }
}
