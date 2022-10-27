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