<?php 
namespace Source\controllers\error;

use BirthFramework\View;

class Error
{
    function notFound(){
        
        return View::render('errors/notFound.php', ['mensagem' => "Página não encontrada."], 404);
    }
}
