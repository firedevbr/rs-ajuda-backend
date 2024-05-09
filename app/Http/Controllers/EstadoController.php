<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstadoRequest;
use App\Http\Requests\UpdateEstadoRequest;
use App\Http\Requests\FiltrarEstadoRequest;
use App\Models\Estado;
use App\Services\EstadoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EstadoController extends Controller
{
    protected EstadoService $estadoService;

    public function __construct(
        EstadoService $estadoService
    ) {
        $this->estadoService = $estadoService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os estado");
        $allEstado = Estado::all();
        return \response()->json($allEstado, Response::HTTP_OK);
    }

    public function index(FiltrarEstadoRequest $request)
    {
        Log::debug("Listando estado de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando estado através dos parâmetros", compact('params'));
        $estados = $this->estadoService->getAll($params);
        Log::debug("estado encontradas", compact('estados'));

        return response()->json($estados, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Estado $estado
     * @return JsonResponse
     */
    public function show(Estado $estado): JsonResponse
    {
        return response()->json($estado, Response::HTTP_OK);
    }

    public function store(StoreEstadoRequest $request){
        Log::info("Cadastrar estado de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $estado = $this->estadoService->create($data);
            Log::debug("estado inserida com sucesso", compact('estado'));

            return \response()->json($estado, Response::HTTP_CREATED);
        });
    }

    public function update(UpdateEstadoRequest $request, Estado $estado)
    {
        Log::info("Iniciando o processo de atualização de um estado", [
            'data' =>  $request->all(),
            'id' => $estado->id
        ]);
        $data = $request->validated();

        return DB::transaction(function () use ($data, $estado) {
            $estado = $this->estadoService->update(
                $estado->id,
                $data
            );

            Log::debug("estado atualizada com sucesso", compact('estado'));

            return \response()->json($estado, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um estado", [
            'id' => $id
        ]);
        $estado = Estado::find($id);
        $estado->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
