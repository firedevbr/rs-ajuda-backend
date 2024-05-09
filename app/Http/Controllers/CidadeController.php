<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCidadeRequest;
use App\Http\Requests\UpdateCidadeRequest;
use App\Http\Requests\FiltrarCidadeRequest;
use App\Models\Cidade;
use App\Services\CidadeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CidadeController extends Controller
{
    protected CidadeService $cidadeService;

    public function __construct(
        CidadeService $cidadeService
    ) {
        $this->cidadeService = $cidadeService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os cidade");
        $allCidade = Cidade::all();
        return \response()->json($allCidade, Response::HTTP_OK);
    }

    public function index(FiltrarCidadeRequest $request)
    {
        Log::debug("Listando cidade de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando cidade através dos parâmetros", compact('params'));
        $cidades = $this->cidadeService->getAll($params);
        Log::debug("cidade encontradas", compact('cidades'));

        return response()->json($cidades, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Cidade $cidade
     * @return JsonResponse
     */
    public function show(Cidade $cidade): JsonResponse
    {
        return response()->json($cidade, Response::HTTP_OK);
    }

    public function store(StoreCidadeRequest $request){
        Log::info("Cadastrar cidade de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $cidade = $this->cidadeService->create($data);
            Log::debug("cidade inserida com sucesso", compact('cidade'));

            return \response()->json($cidade, Response::HTTP_CREATED);
        });
    }

    public function update(UpdateCidadeRequest $request, Cidade $cidade)
    {
        Log::info("Iniciando o processo de atualização de um cidade", [
            'data' =>  $request->all(),
            'id' => $cidade->id
        ]);
        $data = $request->validated();

        return DB::transaction(function () use ($data, $cidade) {
            $cidade = $this->cidadeService->update(
                $cidade->id,
                $data
            );

            Log::debug("cidade atualizada com sucesso", compact('cidade'));

            return \response()->json($cidade, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um cidade", [
            'id' => $id
        ]);
        $cidade = Cidade::find($id);
        $cidade->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
