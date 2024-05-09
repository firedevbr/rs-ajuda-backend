<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desaparecimento extends Model
{
    use HasFactory;

    protected $table = 'desaparecimentos';

    protected $fillable = [
        'pessoa_id',
        'endereco_id',
        'status',
        'observacoes',
        'responsavel_id',
        'ultimo_visto_em'
    ];

    protected $with = [
    'pessoa',
        'endereco',
        'responsavel'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }

public function responsavel()
    {
        return $this->belongsTo(Usuario::class, 'responsavel_id');
    }


}
