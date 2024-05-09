<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaquinhas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pessoa_id');
            $table->text('descricao_objetivo');
            $table->string('pix')->nullable();
            $table->string('dados_bancarios')->nullable();
            $table->unsignedBigInteger('endereco_itens_ajuda_id')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('responsavel_id');
            $table->date('aberto_desde')->nullable();
            $table->timestamps();
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->foreign('endereco_itens_ajuda_id')->references('id')->on('enderecos');
            $table->foreign('responsavel_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaquinhas');
    }
};
