# Birth Framework

Este é um micro framework desenvolvido para estudo do protocolo HTTP e Arquitetura de Software.

## Requisitos
PHP 8.1+, Composer, MySQL.
## Instalação

Para utilizar este framework você deve seguir os seguintes passos:

Executar o comando para clonar o repositório para sua máquina.

```sh
git clone https://github.com/willstori/birthFramework.git
```
Executar o comando para entrar na pasta do framework.
```sh
cd birthFramework
```
Instalar as dependencias do projeto.
```sh
composer install
```

Criar uma base de dados mysql e configurar os dados de conexão através do arquivo **config.php**. Abaixo é demonstrada uma configuração de exemplo.
```php
<?php 
/* Arquivo para definição de constantes */
define('APP_PATH', __DIR__);
define('APP_URL', "http://localhost:8000/");

define('DB_HOST', "localhost");
define('DB_NAME', "birth");
define('DB_USER', "root");
define('DB_PASS', "");
```
Importa o script **birth.sql** para seu banco de dados criado anteriormente.

Navegar até a pasta Public do framework

```sh
cd Public
```

Por fim executar o comando que inicializa o servidor local.

```sh
php -S localhost:8000
```

Agora é só acessar o link: http://localhost:8000

## Rotas

A configuração das rotas é feita pelo arquivo **routes.php**. Logo abaixo é mostrado o arquivo preenchido com rotas de exemplo. 

```php
<?php

use Source\Controllers\HomeController;
use Source\Controllers\ArticleController;


/* Neste arquivo são configuradas as rotas da sua aplicação */

$router->get('/', [HomeController::class, "index"]);

$router->get('/articles', [ArticleController::class, "index"]);
$router->get('/articles/criar', [ArticleController::class, "create"]);
$router->post('/articles', [ArticleController::class, "store"]);
$router->get('/articles/{id}', [ArticleController::class, "edit"]);
$router->post('/articles/{id}', [ArticleController::class, "update"]);
$router->get('/articles/remover/{id}', [ArticleController::class, "destroy"]);

$router->notFound();
```
O objeto **$router** possui 4 métodos para declaração das rotas, sendo eles: 
- **$route->get(string $url, array $controller = [])**;
- **$route->post(string $url, array $controller = [])**;
- **$route->put(string $url, array $controller = [])**;
- **$route->delete(string $url, array $controller = [])**;

Onde **$url** representa o segmento da uri que deve ser apontado para o método do controlador especificado. Já o array **$controller** é onde são declarados respectivamente o nome do controlador que será instanciado pela rota e o nome do método do controlador que será executado.

## Controladores

Os controladores devem ser criados dentro da pasta **Source/Controllers**. Logo abaixo é mostrado um codigo de exemplo de um controlador.

```php
<?php

namespace Source\Controllers;

use BirthFramework\Controller;
use BirthFramework\Response\RedirectResponse;
use BirthFramework\Response\Response;
use BirthFramework\View;
use Source\Repositories\ArticleRepository;

class ArticleController extends Controller
{
    private $articleRepository;

    public function __construct()
    {
        parent::__construct();
        
        $this->articleRepository = new ArticleRepository();
    }

    public function index() : Response
    {        
        $articles = $this->articleRepository->all();

        return View::render('articles/list.php', ['articles' => $articles]);
    }

    public function create() : Response
    {   
        return View::render('articles/create.php');
    }    

    public function edit(int $id) : Response
    {
        $article = $this->articleRepository->find($id);

        if($article == null){
            return View::render('errors/notFound.php', ['mensagem' => "Página não encontrada."], 404);            
        }
        
        return View::render('articles/edit.php', ['article' => $article]);
    }

    public function store() : Response
    {                 
        $this->articleRepository->store($this->request);

        return new RedirectResponse(APP_URL . 'articles');
    }

    public function update(int $id) : Response
    {
        $article = $this->articleRepository->find($id);

        if($article == null){
            return View::render('errors/notFound.php', ['mensagem' => "Página não encontrada."], 404);            
        }

        $article = $this->articleRepository->update($this->request, $article);

        return new RedirectResponse(APP_URL . 'articles/' . $article->id);
    }

    public function destroy(int $id) : Response
    {
        $article = $this->articleRepository->find($id);

        if($article == null){
            return View::render('errors/notFound.php', ['mensagem' => "Página não encontrada."], 404);            
        }
        
        $this->articleRepository->destroy($article);

        return new RedirectResponse(APP_URL . 'articles');
    }
   
}
```
Todos os controladores devem extender da classe abstrata **Controller**, pois com a propriedade **$this->request** desta classe é possível ter acesso aos dados da requisição. Além disso, todos os métodos dos controladores devem ter o tipo de retorno **Response**, porque assim o container de execução consegue renderizar a resposta corretamente para o navegador.

## Views

As Views devem ser criadas dentro da pasta **Source/Views**. Logo abaixo é mostrado um código de exemplo de uma view.

```php
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <base href="<?= APP_URL ?>" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Artigos</title>
</head>

<body>
    <h1>Lista de Artigos</h1>
    <table border="1">
        <thead>
            <tr>
                <th><button onclick="window.location.href='articles/criar'">Novo Artigo</button></th>
                <th>Título</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article) { ?>
                <tr>
                    <td>
                        <button onclick="window.location.href='articles/<?= $article->id ?>'">Editar</button>
                        <button onclick="window.location.href='articles/remover/<?= $article->id ?>'">Remover</button>
                    </td>
                    <td><?= $article->title ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <a href="articles">Voltar</a>
</body>

</html>
```
As views são carregadas através do método estático **View::render(string $path, array $data = [], int $status = 200)**, no qual retorna um objeto do tipo **HtmlResponse**. O parametro **$path** representa o caminho e nome da view que será carregada. Já o parametro **$data** é um array que representa os dados que serão enviados para view. Por fim, a sintaxe das views é composta por tags html e php.

## Modelos

Os Modelos devem ser criados dentro da pasta **Source/Models**. Logo abaixo é mostrado um codigo de exemplo de um modelo.

```php
<?php

namespace Source\Models;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
#[Table(name:'articles')]
class Article
{
    #[Id]
    #[Column(type: Types::INTEGER)]    
    #[GeneratedValue(strategy: 'AUTO')]
    public $id;

    #[Column(length: 150)]
    public $title;

    #[Column(type: Types::TEXT)]
    public $text;    
}
```

O BirthFramwork utiliza DoctrineORM para realizar a persistência dos dados. Portanto, seus modelos devem ser mapeados seguindo as especificações de entidades do Doctrine.

## Repositórios

Os Repositórios devem ser criados dentro da pasta **Source/Repositories**. Logo abaixo é mostrado um codigo de exemplo de um repositório.

```php
<?php

namespace Source\repositories;

use BirthFramework\Repository;
use BirthFramework\Request;
use Source\models\Article;

class ArticleRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        return $this->entityManager->getRepository(Article::class)->findAll();
    }

    public function find($id)
    {
        return $this->entityManager->find(Article::class, $id);
    }

    public function store(Request $request)
    {
        $article = new Article();

        $article->title = $request->getParamn('title');
        $article->text = $request->getParamn('text');

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    public function update(Request $request, Article $article)
    {   
        $article->title = $request->getParamn('title');
        $article->text = $request->getParamn('text');

        $this->entityManager->flush();

        return $article;
    }

    public function destroy(Article $article)
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }
}
```

Existe uma classe abstrata chamada **Repository** que possuí a finalidade de auxiliar as suas classes de repositórios. Nesta classe é disponilibilizada a propriedade **$this->entityManager** que é responsável por gerenciar as entidades do banco de dados. Sendo assim, dentro de suas classes de repositório você fica livre para implementar a regra de persistência dos dados da melhor forma possível.

## Melhorias

Este framework é apenas uma estrutura básica, portanto em trabalhos futuros é sugerida à implementação de algumas funcionalionalidades importantes no desenvolvimento de aplicaçõe web. Sento elas, carregamento de arquivos, validação de dados, sessões, cookies, cache, classe para manipulação de imagens, etc. Além disso, pode ser implementado um novo container de execução, no qual permitirá a injeção de dependências nas classes. Isso faria com que o acoplamento diminuíesse consideravelmente e aumentasse a escalabilidade das aplicações desenvolvidas por esta ferramenta.  
