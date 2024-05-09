# API - Guideline

## Pré-requisitos

Antes de iniciar o desenvolvimento ou a execução do ambiente de desenvolvimento da API, certifique-se de ter instalado em sua máquina as seguintes ferramentas:

- **Docker**: Essencial para criar e gerenciar os containers do ambiente de desenvolvimento. A instalação pode ser realizada através do site oficial [Docker](https://www.docker.com/get-started).
- **Docker Compose**: Usado para definir e rodar aplicações Docker multi-container. Geralmente, vem instalado com o Docker Desktop para Windows e Mac. Para usuários de Linux, pode ser necessário instalar separadamente. Consulte a documentação oficial [Docker Compose](https://docs.docker.com/compose/install/) para mais detalhes sobre a instalação.

## Subir ambiente de Dev

Para inicializar o ambiente de desenvolvimento completo da API, utilize os arquivos de composição do Docker com o comando abaixo:

 ```shell
 docker-compose up -d
 ```

Este comando irá subir todos os containers necessários para o desenvolvimento, que incluem:

- **PHP 8.3**: Container configurado para executar a aplicação usando o PHP8.3 com Laravel 11.
- **MySQL**: Container do banco de dados MySQL para armazenamento dos dados da aplicação.
- **PHPMyAdmin**: Container da interface do PHPMyAdmin para gerenciamento do banco de dados MySQL para manipulação dos dados da aplicação.

A API estará disponível em `http://localhost:9086` por padrão, a menos que a porta seja alterada no arquivo `.env` ou no `docker-compose.yml`.

### Instalar dependências

As dependências devem ser instaladas via composer após a subida do containers da aplicação.
Com os containers em funcionamento, entre no container da aplicação php com o comando: 

```shell
docker-compose exec php bash
```
Isso fará com que você assuma o terminal do container responsável por rodar o serviço php.
Dentro deste container, execute o comando 
```shell
composer install
```
que será responsável por instalar todas as dependências do projeto, para funcionamento correto da API.
### Subir as Migrations

Para configurar o banco de dados com as estruturas necessárias, as migrations devem ser executadas
**dentro do container php** através do comando: 

```shell
php artisan migrate
```

**Após a instalação das dependências do projeto.**

### Acessar a Aplicação

Com os containers rodando, a API estará acessível através do endereço `http://localhost:9086`.

### Documentação da API

A API utiliza o Swagger para documentação dos endpoints. Para acessar a documentação da API e testar os endpoints disponíveis, navegue até `http://localhost:9086/api/documentation`.

### Gerenciamento do Banco de Dados

Para gerenciar o banco de dados MySQL, você pode utilizar ferramentas como phpmyadmin ou acessar diretamente via linha de comando, dependendo de como configurou o serviço no `docker-compose.yml`. Se optar por incluir um container para o phpmyadmin no seu `docker-compose.yml`, o acesso pode ser feito através do navegador em uma URL específica, como `http://localhost:9099`.

 ---

Seguindo estes passos, você terá um ambiente de desenvolvimento configurado para a API, incluindo a aplicação, o banco de dados e, opcionalmente, uma interface para gerenciamento do banco de dados, prontos para desenvolvimento e testes.

## Código

### Estrutura de Desenvolvimento

Neste projeto, seguimos uma abordagem simplificada, focando em Controllers e Services para a lógica de negócios. Isso mantém o projeto ágil e direto, facilitando a manutenção e expansão.

### Passo a Passo para Adicionar Novas Funcionalidades

#### 1. Definir a Rota

Primeiro, defina a rota para a nova funcionalidade no arquivo `routes/api.php`. Isso especifica o endpoint que será exposto pela API.

 ```php
 // routes/api.php

 use App\Http\Controllers\PedidoReembolsoController;

 Route::post('/pedidos-reembolso', [PedidoReembolsoController::class, 'store']);
 ```

#### 2. Criar o Controller

Crie um novo controller para lidar com as requisições para o endpoint definido. Utilize o comando Artisan para gerar o controller:

 ```bash
 php artisan make:controller PedidoReembolsoController
 ```

No controller, injete a service responsável pela lógica de negócios:

 ```php
 // app/Http/Controllers/PedidoReembolsoController.php

 namespace App\Http\Controllers;

 use Illuminate\Http\Request;
 use App\Services\PedidoReembolsoService;
 use Illuminate\Support\Facades\Log;

 class PedidoReembolsoController extends Controller
 {
     protected PedidoReembolsoService $service;

     public function __construct(PedidoReembolsoService $pedidoReembolsoService)
     {
         $this->service = $pedidoReembolsoService;
     }

     public function store(Request $request)
     {
         // Implementação do método store
     }
 }
 ```

#### 3. Criar a Service

A service contém a lógica de negócios da aplicação. Crie uma nova service manualmente no diretório `app/Services`.

 ```php
 // app/Services/PedidoReembolsoService.php

 namespace App\Services;

 use App\Models\Empregado;
 use App\Models\PedidoReembolso;
 use Illuminate\Support\Facades\Log;
 use Carbon\Carbon;

 class PedidoReembolsoService
 {
     public function create(Empregado $empregado, string $dataDespesa, float $valor, string $descricao): PedidoReembolso
     {
         // Implementação do método create
     }
 }
 ```

### Documentação com Swagger

Não esqueça de documentar cada novo endpoint com annotations do Swagger, garantindo que a documentação da API seja atualizada automaticamente. Isso é crucial para manter a documentação alinhada com o código e facilitar o uso da API por outros desenvolvedores e ferramentas.

 ```php
 /**
  * @OA\Post(
  *     path="/api/pedidos-reembolso",
  *     tags={"Pedidos de Reembolso"},
  *     summary="Cria um novo pedido de reembolso",
  *     description="Cria um novo pedido de reembolso com os dados fornecidos.",
  *     operationId="store",
  *     @OA\RequestBody(
  *         required=true,
  *         description="Dados do pedido de reembolso",
  *         @OA\JsonContent(
  *             required={"empregado_id","dataDespesa","descricao","valor"},
  *             @OA\Property(property="empregado_id", type="integer", format="id", example=1),
  *             @OA\Property(property="dataDespesa", type="string", format="date", example="YYYY-MM-DD"),
  *             @OA\Property(property="descricao", type="string", example="Despesas com viagem"),
  *             @OA\Property(property="valor", type="number", format="float", example=123.45),
  *         ),
  *     ),
  *     @OA\Response(
  *         response=200,
  *         description="Pedido de reembolso criado com sucesso."
  *     ),
  *     @OA\Response(
  *         response=400,
  *         description="Dados inválidos."
  *     )
  * )
  */
 ```

Este guia fornece uma visão clara do padrão de desenvolvimento adotado no projeto, facilitando a criação de novas funcionalidades de forma consistente e documentada.

### Atualizar Documentação da API com Swagger

Após realizar as alterações no código e adicionar as annotations do Swagger aos seus controllers, é essencial atualizar a documentação da API para refletir as mudanças. Para isso, execute o seguinte comando no terminal:

 ```bash
 php artisan l5-swagger:generate
 ```

Este comando irá processar as annotations do Swagger em seus controllers e serviços, gerando a documentação da API atualizada. O resultado será acessível através da URL definida para a documentação do Swagger, geralmente `http://localhost:9086/api/documentation`.

Certifique-se de executar este comando sempre que fizer alterações na API que necessitem de atualização na documentação. Isso garantirá que a documentação esteja sempre sincronizada com o estado atual da sua API, facilitando o teste, a integração e o uso por outros desenvolvedores.
