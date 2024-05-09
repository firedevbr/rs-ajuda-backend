<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaquinha extends Model
{
    use HasFactory;

    protected $table = 'vaquinhas';

    protected $fillable = [
        'pessoa_id',
        'descricao_objetivo',
        'pix',
        'dados_bancarios',
        'endereco_itens_ajuda_id',
        'status',
        'responsavel_id',
        'aberto_desde'
    ];

    protected $with = [
    'pessoa',
        'enderecoItensAjuda',
        'responsavel'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

public function enderecoItensAjuda()
    {
        return $this->belongsTo(Endereco::class, 'endereco_itens_ajuda_id');
    }

public function responsavel()
    {
        return $this->belongsTo(Usuario::class, 'responsavel_id');
    }


}
