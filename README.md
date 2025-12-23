# router

**Roteador PHP Simples e Eficiente**

O `router` é uma biblioteca PHP leve e descomplicada, projetada para facilitar o roteamento de requisições HTTP em suas aplicações. Ele oferece uma maneira intuitiva de mapear URLs para classes de Controller e métodos específicos, suportando parâmetros de URL e geração de URLs nomeadas.

## Funcionalidades Principais

*   **Roteamento HTTP:** Suporte nativo para métodos `GET` e `POST`.
*   **Parâmetros de URL:** Captura de parâmetros dinâmicos diretamente na URL (ex: `/produto/{id}`).
*   **Despacho de Controller:** Despacha a requisição para um método específico dentro de uma classe de Controller.
*   **Geração de URL:** Capacidade de gerar URLs com base em um nome de rota definido.
*   **Estrutura Simples:** Código conciso e fácil de entender, ideal para projetos que buscam baixo acoplamento e alta performance.

## Instalação

Como uma biblioteca PHP, a instalação é feita via [Composer](https://getcomposer.org/):

```bash
composer require everton3x/router
```

## Uso

### 1. Definição de Rotas

Todas as rotas devem ser definidas antes de chamar o método `Route::routing()`. No exemplo do repositório, isso é feito no arquivo `routes.php`.

| Método | URL Padrão | Controller | Método do Controller | Descrição |
| :---: | :--- | :--- | :--- | :--- |
| `GET` | `/` | `RootController::class` | `index` (padrão) | Rota raiz. |
| `POST` | `/module1` | `Module1Controller::class` | `save` | Rota POST para salvar dados. |
| `GET` | `/module1/action1/{param1}` | `Module1Controller::class` | `action1` | Rota com um parâmetro dinâmico. |
| `GET` | `/module2/{param1}/param2/{param2}` | `Module2Controller::class` | `index` (padrão) | Rota com múltiplos parâmetros e nomeada. |

**Exemplo de `routes.php`:**

```php
use App\Controller\Module1Controller;
use App\Controller\Module2Controller;
use App\Controller\RootController;
use Router\Route;

// Rota GET simples
Route::get('/', RootController::class);

// Rota POST com método específico
Route::post('/module1', Module1Controller::class, 'save');

// Rota GET com parâmetro de URL
Route::get('/module1/action1/{param1}', Module1Controller::class, 'action1');

// Rota nomeada com múltiplos parâmetros
Route::get('/module2/{param1}/param2/{param2}', Module2Controller::class)->name('m2');
```

### 2. Execução do Roteamento

Após a definição das rotas, o roteador deve ser iniciado para processar a requisição atual.

**Exemplo de `public/index.php`:**

```php
// Inclua o autoloader do Composer
require __DIR__ . '/../vendor/autoload.php';

// Inclua o arquivo de definição de rotas
require __DIR__ . '/../routes.php';

// Inicia o roteamento
Router\Route::routing();
```

### 3. Controllers

Os parâmetros de URL capturados são passados como argumentos para o método do Controller.

**Exemplo de `Module2Controller.php` (para a rota `/module2/{param1}/param2/{param2}`):**

```php
namespace App\Controller;

class Module2Controller
{
    // Este método será chamado se a URL for /module2/valor1/param2/valor2
    public function index(string $param1, string $param2)
    {
        echo "Parâmetro 1: " . $param1; // Saída: Parâmetro 1: valor1
        echo "Parâmetro 2: " . $param2; // Saída: Parâmetro 2: valor2
    }
}
```

### 4. Geração de URL Nomeada

Você pode gerar URLs dinamicamente usando o nome da rota e passando os parâmetros necessários.

```php
// Definição da rota
Route::get('/module2/{param1}/param2/{param2}', Module2Controller::class)->name('m2');

// Geração da URL em qualquer parte da aplicação
$url = Route::url('m2', ['param1' => 'dados', 'param2' => 'teste']);

// $url será '/module2/dados/param2/teste'
```

## Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---
*README.md gerado por Manus AI*
