<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Requests\FiltrarUsuarioRequest;
use App\Models\Usuario;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller
{
    protected UsuarioService $usuarioService;

    public function __construct(
        UsuarioService $usuarioService
    ) {
        $this->usuarioService = $usuarioService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os usuario");
        $allUsuario = Usuario::all();
        return \response()->json($allUsuario, Response::HTTP_OK);
    }

    public function index(FiltrarUsuarioRequest $request)
    {
        Log::debug("Listando usuario de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando usuario através dos parâmetros", compact('params'));
        $usuarios = $this->usuarioService->getAll($params);
        Log::debug("usuario encontradas", compact('usuarios'));

        return response()->json($usuarios, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Usuario $usuario
     * @return JsonResponse
     */
    public function show(Usuario $usuario): JsonResponse
    {
        return response()->json($usuario, Response::HTTP_OK);
    }

    public function store(StoreUsuarioRequest $request){
        Log::info("Cadastrar usuario de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $usuario = $this->usuarioService->create($data);
            Log::debug("usuario inserida com sucesso", compact('usuario'));

            return \response()->json($usuario, Response::HTTP_CREATED);
        });
    }

    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        Log::info("Iniciando o processo de atualização de um usuario", [
            'data' =>  $request->all(),
            'id' => $usuario->id
        ]);
        $data = $request->validated();

        return DB::transaction(function () use ($data, $usuario) {
            $usuario = $this->usuarioService->update(
                $usuario->id,
                $data
            );

            Log::debug("usuario atualizada com sucesso", compact('usuario'));

            return \response()->json($usuario, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um usuario", [
            'id' => $id
        ]);
        $usuario = Usuario::find($id);
        $usuario->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
