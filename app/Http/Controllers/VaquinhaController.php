<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaquinhaRequest;
use App\Http\Requests\UpdateVaquinhaRequest;
use App\Http\Requests\FiltrarVaquinhaRequest;
use App\Models\Vaquinha;
use App\Services\VaquinhaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class VaquinhaController extends Controller
{
    protected VaquinhaService $vaquinhaService;

    public function __construct(
        VaquinhaService $vaquinhaService
    ) {
        $this->vaquinhaService = $vaquinhaService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os vaquinha");
        $allVaquinha = Vaquinha::all();
        return \response()->json($allVaquinha, Response::HTTP_OK);
    }

    public function index(FiltrarVaquinhaRequest $request)
    {
        Log::debug("Listando vaquinha de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando vaquinha através dos parâmetros", compact('params'));
        // Chame o serviço para buscar as vaquinhas filtradas
        $user = auth()->user();

        // Se 'meus' estiver nos parâmetros e o usuário estiver autenticado
        if (isset($params['meus']) && $user) {
            if ($params['meus'] == 'true') {
                $params['responsavel_id'] = $user->id;
            }
        }
        $vaquinhas = $this->vaquinhaService->filtrar($params);
        Log::debug("vaquinha encontradas", compact('vaquinhas'));

        return response()->json($vaquinhas, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Vaquinha $vaquinha
     * @return JsonResponse
     */
    public function show(Vaquinha $vaquinha): JsonResponse
    {
        return response()->json($vaquinha, Response::HTTP_OK);
    }

    public function store(StoreVaquinhaRequest $request){
        Log::info("Cadastrar vaquinha de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $data['responsavel_id'] = auth()->user()->id;
            $data['status'] = "Em andamento";
            $vaquinha = $this->vaquinhaService->create($data);
            Log::debug("vaquinha inserida com sucesso", compact('vaquinha'));

            return \response()->json($vaquinha, Response::HTTP_CREATED);
        });
    }

    public function update(UpdateVaquinhaRequest $request, Vaquinha $vaquinha)
    {
        Log::info("Iniciando o processo de atualização de um vaquinha", [
            'data' =>  $request->all(),
            'id' => $vaquinha->id
        ]);
        $data = $request->validated();

        $loggedUser = auth()->user();

        if ($loggedUser->id !== $vaquinha->responsavel_id) {
            throw new UnauthorizedException();
        }

        return DB::transaction(function () use ($data, $vaquinha) {
            $vaquinha = $this->vaquinhaService->update(
                $vaquinha->id,
                $data
            );

            Log::debug("vaquinha atualizada com sucesso", compact('vaquinha'));

            return \response()->json($vaquinha, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um vaquinha", [
            'id' => $id
        ]);
        $vaquinha = Vaquinha::find($id);
        $vaquinha->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
