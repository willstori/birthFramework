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
