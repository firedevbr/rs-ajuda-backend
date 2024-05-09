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
        Schema::create('resgates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pessoa_id');
            $table->unsignedBigInteger('endereco_id');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('responsavel_id');
            $table->timestamp('ultimo_visto_em')->nullable();
            $table->timestamps();
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->foreign('endereco_id')->references('id')->on('enderecos');
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
        Schema::dropIfExists('resgates');
    }
};
