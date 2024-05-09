<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rotas que requerem autenticação
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'user']);

    Route::post("/desaparecimentos", [\App\Http\Controllers\DesaparecimentoController::class, 'store']);
    Route::put("/desaparecimentos/{desaparecimento}", [\App\Http\Controllers\DesaparecimentoController::class, 'update']);
    Route::delete("/desaparecimentos/{desaparecimento}", [\App\Http\Controllers\DesaparecimentoController::class, 'destroy']);

    Route::post("/pessoas", [\App\Http\Controllers\PessoaController::class, 'store']);
    Route::put("/pessoas/{pessoa}", [\App\Http\Controllers\PessoaController::class, 'update']);
    Route::delete("/pessoas/{pessoa}", [\App\Http\Controllers\PessoaController::class, 'destroy']);

    Route::post("/resgates", [\App\Http\Controllers\ResgateController::class, 'store']);
    Route::put("/resgates/{resgate}", [\App\Http\Controllers\ResgateController::class, 'update']);
    Route::delete("/resgates/{resgate}", [\App\Http\Controllers\ResgateController::class, 'destroy']);

    Route::post("/vaquinhas", [\App\Http\Controllers\VaquinhaController::class, 'store']);
    Route::put("/vaquinhas/{vaquinha}", [\App\Http\Controllers\VaquinhaController::class, 'update']);
    Route::delete("/vaquinhas/{vaquinha}", [\App\Http\Controllers\VaquinhaController::class, 'destroy']);

    Route::post("/cidades", [\App\Http\Controllers\CidadeController::class, 'store']);
    Route::put("/cidades/{cidade}", [\App\Http\Controllers\CidadeController::class, 'update']);
    Route::delete("/cidades/{cidade}", [\App\Http\Controllers\CidadeController::class, 'destroy']);

    Route::post("/estados", [\App\Http\Controllers\EstadoController::class, 'store']);
    Route::put("/estados/{estado}", [\App\Http\Controllers\EstadoController::class, 'update']);
    Route::delete("/estados/{estado}", [\App\Http\Controllers\EstadoController::class, 'destroy']);

    Route::post("/enderecos", [\App\Http\Controllers\EnderecoController::class, 'store']);
    Route::put("/enderecos/{endereco}", [\App\Http\Controllers\EnderecoController::class, 'update']);
    Route::delete("/enderecos/{endereco}", [\App\Http\Controllers\EnderecoController::class, 'destroy']);

    Route::post("/usuarios", [\App\Http\Controllers\UsuarioController::class, 'store']);
    Route::put("/usuarios/{usuario}", [\App\Http\Controllers\UsuarioController::class, 'update']);
    Route::delete("/usuarios/{usuario}", [\App\Http\Controllers\UsuarioController::class, 'destroy']);
});

//Rotas públicas

Route::get("/pessoas/all", [\App\Http\Controllers\PessoaController::class, 'all']);
Route::get("/pessoas", [\App\Http\Controllers\PessoaController::class, 'index']);
Route::get("/pessoas/{pessoa}", [\App\Http\Controllers\PessoaController::class, 'show']);

Route::get("/desaparecimentos/all", [\App\Http\Controllers\DesaparecimentoController::class, 'all']);
Route::get("/desaparecimentos", [\App\Http\Controllers\DesaparecimentoController::class, 'index'])->middleware('auth.optional');
Route::get("/desaparecimentos/{desaparecimento}", [\App\Http\Controllers\DesaparecimentoController::class, 'show']);

Route::get("/resgates/all", [\App\Http\Controllers\ResgateController::class, 'all']);
Route::get("/resgates", [\App\Http\Controllers\ResgateController::class, 'index'])->middleware('auth.optional');
Route::get("/resgates/{resgate}", [\App\Http\Controllers\ResgateController::class, 'show']);

Route::get("/vaquinhas/all", [\App\Http\Controllers\VaquinhaController::class, 'all']);
Route::get("/vaquinhas", [\App\Http\Controllers\VaquinhaController::class, 'index'])->middleware('auth.optional');
Route::get("/vaquinhas/{vaquinha}", [\App\Http\Controllers\VaquinhaController::class, 'show']);

Route::get("/cidades/all", [\App\Http\Controllers\CidadeController::class, 'all']);
Route::get("/cidades", [\App\Http\Controllers\CidadeController::class, 'index']);
Route::get("/cidades/{cidade}", [\App\Http\Controllers\CidadeController::class, 'show']);

Route::get("/estados/all", [\App\Http\Controllers\EstadoController::class, 'all']);
Route::get("/estados", [\App\Http\Controllers\EstadoController::class, 'index']);
Route::get("/estados/{estado}", [\App\Http\Controllers\EstadoController::class, 'show']);

Route::get("/enderecos/all", [\App\Http\Controllers\EnderecoController::class, 'all']);
Route::get("/enderecos", [\App\Http\Controllers\EnderecoController::class, 'index']);
Route::get("/enderecos/{endereco}", [\App\Http\Controllers\EnderecoController::class, 'show']);
