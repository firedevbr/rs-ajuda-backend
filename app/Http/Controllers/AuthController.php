<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *    title="Nome da Sua API",
 *    version="1.0.0",
 *    description="Uma breve descrição da sua API",
 *    @OA\Contact(
 *        email="seuemail@exemplo.com"
 *    ),
 *    @OA\License(
 *        name="Apache 2.0",
 *        url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *    )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * // Definições de segurança globais (se aplicável)
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Log in a user and return authentication token and user data",
     *     description="Log in a user with the provided credentials and return authentication token along with user data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", description="User's email address"),
     *                 @OA\Property(property="password", type="string", description="User's account password"),
     *                 example={"email": "test@example.com", "password": "teste123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Login bem-sucedido"),
     *             @OA\Property(property="access_token", type="string", description="Bearer token for authentication", example="1|j4P3gjdkkmFQ..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Usuário X"),
     *                 @OA\Property(property="cpf", type="string", example="99999999999"),
     *                 @OA\Property(property="email", type="string", example="test@example.com"),
     *                 @OA\Property(property="created_at", type="string", example="2024-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        // Validação das credenciais fornecidas
        $credentials = $request->only('email', 'password');

        // Verificação das credenciais usando `Auth::attempt`
        if (Auth::attempt($credentials)) {
            // Obtenção do usuário autenticado
            $user = Auth::user();

            // Criação do token para autenticação
            $token = $user->createToken('auth_token')->plainTextToken;

            // Retorno do token e dos dados do usuário
            return response()->json([
                'message' => 'Login bem-sucedido',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);
        } else {
            // Retorno de erro caso as credenciais sejam inválidas
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nome",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="cpf",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"nome": "Usuário X", "cpf": "99999999999","email": "test@example.com", "password": "teste123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuário registrado com sucesso"),
     *             @OA\Property(property="access_token", type="string", description="Bearer token for authentication", example="1|l9fwFjeFJ38..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Usuário X"),
     *                 @OA\Property(property="cpf", type="string", example="99999999999"),
     *                 @OA\Property(property="email", type="string", example="test@example.com"),
     *                 @OA\Property(property="created_at", type="string", example="2024-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8',
            'cpf' => 'sometimes|nullable|string|size:11|unique:usuarios'
        ]);

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
        ]);

        // Criação de um token para autenticação
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // Retorno da resposta com o token
        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $usuario
        ], 201);
    }

}
