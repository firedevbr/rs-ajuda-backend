<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnderecoRequest;
use App\Http\Requests\UpdateEnderecoRequest;
use App\Http\Requests\FiltrarEnderecoRequest;
use App\Models\Endereco;
use App\Services\EnderecoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnderecoController extends Controller
{
    protected EnderecoService $enderecoService;

    public function __construct(
        EnderecoService $enderecoService
    ) {
        $this->enderecoService = $enderecoService;
    }

    public function all(): JsonResponse
    {
        Log::info("Iniciando o processo de listagem de todos os endereco");
        $allEndereco = Endereco::all();
        return \response()->json($allEndereco, Response::HTTP_OK);
    }

    public function index(FiltrarEnderecoRequest $request)
    {
        Log::debug("Listando endereco de acordo com parâmetros" , $request->all());

        $input = $request->all();
        $defaultParams = [
            'perPage' => 10,
            'page'    => 1,
            'sort'    => 'created_at',
            'order'   => 'desc',
        ];
        $params = array_merge($defaultParams, $input);
        Log::info("Buscando endereco através dos parâmetros", compact('params'));
        $enderecos = $this->enderecoService->getAll($params);
        Log::debug("endereco encontradas", compact('enderecos'));

        return response()->json($enderecos, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Endereco $endereco
     * @return JsonResponse
     */
    public function show(Endereco $endereco): JsonResponse
    {
        return response()->json($endereco, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/enderecos",
     *     summary="Create a new address",
     *     security={{"bearerAuth":{}}},
     *     description="Create a new address based on the provided data",
     *     tags={"Enderecos"},
     *     @OA\RequestBody(
     *         required=true,
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="logradouro", type="string", example="Rua Exemplo"),
     *                     @OA\Property(property="bairro", type="string", example="Centro"),
     *                     @OA\Property(property="regiao", type="string", example="Norte"),
     *                     @OA\Property(property="numero", type="string", example="123"),
     *                     @OA\Property(property="cep", type="string", example="12345-678"),
     *                     @OA\Property(property="cidade", type="string", example="São Paulo"),
     *                     @OA\Property(property="estado", type="string", example="SP"),
     *                     @OA\Property(property="ponto_de_referencia", type="string", example="Perto do mercado")
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Address created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Endereco")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function store(StoreEnderecoRequest $request){
        Log::info("Cadastrar endereco de acordo com parâmetros" , $request->all());
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $endereco = $this->enderecoService->create($data);
            Log::debug("endereco inserida com sucesso", compact('endereco'));

            return \response()->json($endereco, Response::HTTP_CREATED);
        });
    }

    public function update(UpdateEnderecoRequest $request, Endereco $endereco)
    {
        Log::info("Iniciando o processo de atualização de um endereco", [
            'data' =>  $request->all(),
            'id' => $endereco->id
        ]);
        $data = $request->validated();

        return DB::transaction(function () use ($data, $endereco) {
            $endereco = $this->enderecoService->update(
                $endereco->id,
                $data
            );

            Log::debug("endereco atualizada com sucesso", compact('endereco'));

            return \response()->json($endereco, Response::HTTP_OK);
        });
    }

    public function destroy(int $id){
        Log::info("Iniciando o processo de deletar um endereco", [
            'id' => $id
        ]);
        $endereco = Endereco::find($id);
        $endereco->delete($id);
        return \response()->json([], Response::HTTP_OK);
    }
}
