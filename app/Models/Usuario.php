<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *   schema="Usuario",
 *   type="object",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     description="ID do Usuário"
 *   ),
 *   @OA\Property(
 *     property="nome",
 *     type="string",
 *     description="Nome do Usuário"
 *   ),
 *   @OA\Property(
 *     property="cpf",
 *     type="string",
 *     description="Nome do Usuário"
 *   ),
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     description="Email do Usuário"
 *   ),
 *   @OA\Property(
 *     property="created_at",
 *     type="string",
 *     format="date-time",
 *     description="Data de Criação do Usuário"
 *   ),
 *   @OA\Property(
 *     property="updated_at",
 *     type="string",
 *     format="date-time",
 *     description="Data da Última Atualização do Usuário"
 *   )
 * )
 */
class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;


    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'password',
        'cpf'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = [

    ];

    public function desaparecimentos()
    {
        return $this->hasMany(Desaparecimento::class, 'responsavel_id');
    }

    public function resgates()
    {
        return $this->hasMany(Resgate::class, 'responsavel_id');
    }

    public function vaquinhas()
    {
        return $this->hasMany(Vaquinha::class, 'responsavel_id');
    }


}
