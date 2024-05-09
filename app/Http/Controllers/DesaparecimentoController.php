<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDesaparecimentoRequest;
use App\Http\Requests\UpdateDesaparecimentoRequest;
use App\Http\Requests\FiltrarDesaparecimentoRequest;
use App\Models\Desaparecimento;
use App\Services\DesaparecimentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class DesaparecimentoController extends Controller
{
    protected DesaparecimentoService $desaparecimentoService;

    public function __construct(
        DesaparecimentoService $desaparecimentoService
    ) {
        $this->desaparecimentoService = $desaparecimentoService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os desaparecimento");
        $allDesaparecimento = Desaparecimento::all();
        return \response()->json($allDesaparecimento, Response::HTTP_OK);
    }

    public function index(FiltrarDesaparecimentoRequest $request)
    {
        Log::debug("Listando desaparecimento de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando desaparecimento através dos parâmetros", compact('params'));
        $user = auth()->user();

        // Se 'meus' estiver nos parâmetros e o usuário estiver autenticado
        if (isset($params['meus']) && $user) {
            if ($params['meus'] == 'true') {
                $params['responsavel_id'] = $user->id;
            }
        }
        $desaparecimentos = $this->desaparecimentoService->filtrar($params);
        Log::debug("desaparecimento encontradas", compact('desaparecimentos'));

        return response()->json($desaparecimentos, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Desaparecimento $desaparecimento
     * @return JsonResponse
     */
    public function show(Desaparecimento $desaparecimento): JsonResponse
    {
        return response()->json($desaparecimento, Response::HTTP_OK);
    }

    public function store(StoreDesaparecimentoRequest $request){
        Log::info("Cadastrar desaparecimento de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $desaparecimento = $this->desaparecimentoService->create($data);
            Log::debug("desaparecimento inserida com sucesso", compact('desaparecimento'));

            return \response()->json($desaparecimento, Response::HTTP_CREATED);
        });
    }

    public function update(UpdateDesaparecimentoRequest $request, Desaparecimento $desaparecimento)
    {
        Log::info("Iniciando o processo de atualização de um desaparecimento", [
            'data' =>  $request->all(),
            'id' => $desaparecimento->id
        ]);
        $data = $request->validated();

        $loggedUser = auth()->user();

        if ($loggedUser->id !== $desaparecimento->responsavel_id) {
            throw new UnauthorizedException();
        }

        return DB::transaction(function () use ($data, $desaparecimento) {
            $desaparecimento = $this->desaparecimentoService->update(
                $desaparecimento->id,
                $data
            );

            Log::debug("desaparecimento atualizada com sucesso", compact('desaparecimento'));

            return \response()->json($desaparecimento, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um desaparecimento", [
            'id' => $id
        ]);
        $desaparecimento = Desaparecimento::find($id);
        $desaparecimento->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
