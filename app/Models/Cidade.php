<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Cidade",
 *     description="Modelo de Cidade",
 *     type="object",
 *     title="Cidade",
 *     @OA\Property(property="id", type="integer", description="ID da cidade", example=1),
 *     @OA\Property(property="nome", type="string", description="Nome da cidade", example="São Paulo"),
 *     @OA\Property(property="estado_id", type="integer", description="ID do estado", example=1),
 *     @OA\Property(ref="#/components/schemas/Estado")
 * )
 */
class Cidade extends Model
{
    use HasFactory;

    protected $table = 'cidades';

    protected $fillable = [
        'nome',
        'estado_id'
    ];

    protected $with = [
    'estado'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'cidade_id');
    }


}
