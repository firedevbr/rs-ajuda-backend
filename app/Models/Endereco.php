<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Endereco",
 *     description="Modelo de Endereco",
 *     type="object",
 *     title="Endereco",
 *     @OA\Property(property="id", type="integer", description="ID do endereço", example=1),
 *     @OA\Property(property="logradouro", type="string", description="Nome da rua", example="Rua Exemplo"),
 *     @OA\Property(property="bairro", type="string", description="Nome do bairro", example="Centro"),
 *     @OA\Property(property="regiao", type="string", description="Nome da região", example="Norte"),
 *     @OA\Property(property="numero", type="string", description="Número do endereço", example="123"),
 *     @OA\Property(property="cep", type="string", description="CEP do endereço", example="12345-678"),
 *     @OA\Property(property="cidade_id", type="integer", description="ID da cidade", example=1),
 *     @OA\Property(property="ponto_de_referencia", type="string", description="Ponto de referência", example="Perto do mercado"),
 *     @OA\Property(property="latitude", type="string", description="Latitude", example="-23.5505"),
 *     @OA\Property(property="longitude", type="integer", description="Longitude", example="-46.6333"),
 *     @OA\Property(ref="#/components/schemas/Cidade")
 * )
 */
class Endereco extends Model
{
    use HasFactory;

    protected $table = 'enderecos';

    protected $fillable = [
        'logradouro',
        'bairro',
        'regiao',
        'numero',
        'cep',
        'cidade_id',
        'ponto_de_referencia',
        'latitude',
        'longitude'
    ];

    protected $with = [
    'cidade'
    ];

    public function cidade()
    {
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }

public function desaparecimentos()
    {
        return $this->hasMany(Desaparecimento::class, 'endereco_id');
    }

public function resgates()
    {
        return $this->hasMany(Resgate::class, 'endereco_id');
    }

public function vaquinhas()
    {
        return $this->hasMany(Vaquinha::class, 'endereco_itens_ajuda_id');
    }


}
