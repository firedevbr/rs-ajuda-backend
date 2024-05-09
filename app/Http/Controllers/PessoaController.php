<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Http\Requests\FiltrarPessoaRequest;
use App\Models\Pessoa;
use App\Services\PessoaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PessoaController extends Controller
{
    protected PessoaService $pessoaService;

    public function __construct(
        PessoaService $pessoaService
    ) {
        $this->pessoaService = $pessoaService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os pessoa");
        $allPessoa = Pessoa::all();
        return \response()->json($allPessoa, Response::HTTP_OK);
    }

    public function index(FiltrarPessoaRequest $request)
    {
        Log::debug("Listando pessoa de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando pessoa através dos parâmetros", compact('params'));
        $pessoas = $this->pessoaService->getAll($params);
        Log::debug("pessoa encontradas", compact('pessoas'));

        return response()->json($pessoas, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Pessoa $pessoa
     * @return JsonResponse
     */
    public function show(Pessoa $pessoa): JsonResponse
    {
        return response()->json($pessoa, Response::HTTP_OK);
    }

    public function store(StorePessoaRequest $request){
        Log::info("Cadastrar pessoa de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $pessoa = $this->pessoaService->create($data);
            Log::debug("pessoa inserida com sucesso", compact('pessoa'));

            return \response()->json($pessoa, Response::HTTP_CREATED);
        });
    }

    public function update(UpdatePessoaRequest $request, Pessoa $pessoa)
    {
        Log::info("Iniciando o processo de atualização de um pessoa", [
            'data' =>  $request->all(),
            'id' => $pessoa->id
        ]);
        $data = $request->validated();

        return DB::transaction(function () use ($data, $pessoa) {
            $pessoa = $this->pessoaService->update(
                $pessoa->id,
                $data
            );

            Log::debug("pessoa atualizada com sucesso", compact('pessoa'));

            return \response()->json($pessoa, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um pessoa", [
            'id' => $id
        ]);
        $pessoa = Pessoa::find($id);
        $pessoa->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
