<?php

namespace BirthFramework;

abstract class Controller{

    protected $request;

    public function __construct()
    {
        $this->request = Request::getInstance();
    }
    
}