<?php

namespace Source\Controllers;

use BirthFramework\Controller;
use BirthFramework\Response\Response;
use BirthFramework\View;

class HomeController extends Controller
{
    public function index() : Response
    {        
        return View::render("home.php");
    }
}
