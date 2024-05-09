<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResgateRequest;
use App\Http\Requests\UpdateResgateRequest;
use App\Http\Requests\FiltrarResgateRequest;
use App\Models\Resgate;
use App\Services\ResgateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class ResgateController extends Controller
{
    protected ResgateService $resgateService;

    public function __construct(
        ResgateService $resgateService
    ) {
        $this->resgateService = $resgateService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os resgate");
        $allResgate = Resgate::all();
        return \response()->json($allResgate, Response::HTTP_OK);
    }

    public function index(FiltrarResgateRequest $request)
    {
        Log::debug("Listando resgate de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando resgate através dos parâmetros", compact('params'));
        $user = auth()->user();

        // Se 'meus' estiver nos parâmetros e o usuário estiver autenticado
        if (isset($params['meus']) && $user) {
            if ($params['meus'] == 'true') {
                $params['responsavel_id'] = $user->id;
            }
        }
        $resgates = $this->resgateService->filtrar($params);
        Log::debug("resgate encontradas", compact('resgates'));

        return response()->json($resgates, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Resgate $resgate
     * @return JsonResponse
     */
    public function show(Resgate $resgate): JsonResponse
    {
        return response()->json($resgate, Response::HTTP_OK);
    }

    public function store(StoreResgateRequest $request){
        Log::info("Cadastrar resgate de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $data['responsavel_id'] = auth()->user()->id;
            $data['status'] = "Aguardando Ajuda";
            $resgate = $this->resgateService->create($data);
            Log::debug("resgate inserida com sucesso", compact('resgate'));

            return \response()->json($resgate, Response::HTTP_CREATED);
        });
    }

    public function update(UpdateResgateRequest $request, Resgate $resgate)
    {
        Log::info("Iniciando o processo de atualização de um resgate", [
            'data' =>  $request->all(),
            'id' => $resgate->id
        ]);
        $data = $request->validated();

        $loggedUser = auth()->user();

        if ($loggedUser->id !== $resgate->responsavel_id) {
            throw new UnauthorizedException();
        }

        return DB::transaction(function () use ($data, $resgate) {
            $resgate = $this->resgateService->update(
                $resgate->id,
                $data
            );

            Log::debug("resgate atualizada com sucesso", compact('resgate'));

            return \response()->json($resgate, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um resgate", [
            'id' => $id
        ]);
        $resgate = Resgate::find($id);
        $resgate->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
