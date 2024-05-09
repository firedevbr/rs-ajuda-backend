<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Estado",
 *     description="Modelo de Estado",
 *     type="object",
 *     title="Estado",
 *     @OA\Property(property="id", type="integer", description="ID do estado", example=1),
 *     @OA\Property(property="nome", type="string", description="Nome do estado", example="São Paulo")
 * )
 */
class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';

    protected $fillable = [
        'nome'
    ];

    protected $with = [

    ];

    public function cidades()
    {
        return $this->hasMany(Cidade::class, 'estado_id');
    }


}
