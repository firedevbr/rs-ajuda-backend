<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'cpf'
    ];

    protected $with = [
    
    ];

    public function desaparecimentos()
    {
        return $this->hasMany(Desaparecimento::class, 'pessoa_id');
    }

public function resgates()
    {
        return $this->hasMany(Resgate::class, 'pessoa_id');
    }

public function vaquinhas()
    {
        return $this->hasMany(Vaquinha::class, 'pessoa_id');
    }


}
