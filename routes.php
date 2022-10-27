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
