<?php

namespace BirthFramework;

class Request implements Singleton
{
    public static $instance;

    private $paramns;

    private function __construct()
    {
        //
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Request();
            self::$instance->method = $_SERVER['REQUEST_METHOD'];
            self::$instance->paramns = $_REQUEST;
        }

        return self::$instance;
    }

    public function all()
    {
        return $this->paramns;
    }

    public function getParamn($name)
    {
        if (isset($this->paramns[$name])) {
            return $this->paramns[$name];
        }

        return null;
    }

    public function hasParamn($name)
    {
        return isset($this->paramns[$name]);
    }

    public function filledParamn($name)
    {
        return isset($this->paramns[$name]) && !empty($this->paramns[$name]);
    }

    public function setParamn($paramnName, $paramnValue)
    {
        foreach ($this->paramns as $name => $value) {
            if ($paramnName == $name) {
                $this->paramns[$paramnName] = $paramnValue;
                return;
            }
        }

        return $this->paramns[$paramnName] = $paramnValue;
    }
}
