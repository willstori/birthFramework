<?php

namespace BirthFramework;

use Source\controllers\error\Error;
use BirthFramework\Container;
use BirthFramework\Singleton;

class Router implements Singleton
{
    private const CONTROLLER_NAME = 0;
    private const CONTROLLER_METHOD = 1;

    public static $instance;

    private $uri;
    private $url;
    private $controller;
    private $method;
    private $paramns;

    private function __construct()
    {
        //
    }

    public static function getInstance(): Router
    {
        if (!isset(self::$instance)) {
            self::$instance = new Router();
            self::$instance->controller = "";
            self::$instance->method = "";
            self::$instance->paramns = [];
            self::$instance->uri = !isset($_SERVER['QUERY_STRING'])
                ? $_SERVER['REQUEST_URI'] :
                str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        }

        return self::$instance;
    }

    public function get(string $url, array $controller = []): void
    {
        $this->url = $url;

        if ($_SERVER['REQUEST_METHOD'] != 'GET')
            return;

        $pattern = $this->buildUrlCheckPattern();

        if (!preg_match($pattern, $this->uri, $matches))
            return;

        $this->controller = $controller[Router::CONTROLLER_NAME];
        $this->method = $controller[Router::CONTROLLER_METHOD];
        $this->paramns = $this->getParamns($matches);
    }

    public function post(string $url, array $controller = []): void
    {
        $this->url = $url;

        if ($_SERVER['REQUEST_METHOD'] != 'POST')
            return;

        $pattern = $this->buildUrlCheckPattern();

        if (!preg_match($pattern, $this->uri, $matches))
            return;

        $this->controller = $controller[Router::CONTROLLER_NAME];
        $this->method = $controller[Router::CONTROLLER_METHOD];
        $this->paramns = $this->getParamns($matches);
    }

    public function put(string $url, array $controller = []): void
    {
        $this->url = $url;

        if ($_SERVER['REQUEST_METHOD'] != 'PUT')
            return;

        $pattern = $this->buildUrlCheckPattern();

        if (!preg_match($pattern, $this->uri, $matches))
            return;

        $this->controller = $controller[Router::CONTROLLER_NAME];
        $this->method = $controller[Router::CONTROLLER_METHOD];
        $this->paramns = $this->getParamns($matches);
    }

    public function delete(string $url, array $controller = []): void
    {
        $this->url = $url;

        if ($_SERVER['REQUEST_METHOD'] != 'DELETE')
            return;

        $pattern = $this->buildUrlCheckPattern();

        if (!preg_match($pattern, $this->uri, $matches))
            return;

        $this->controller = $controller[Router::CONTROLLER_NAME];
        $this->method = $controller[Router::CONTROLLER_METHOD];
        $this->paramns = $this->getParamns($matches);
    }

    public function notFound(): void
    {
        if (!empty($this->controller)) {
            return;
        }

        $this->controller = Error::class;
        $this->method = "notFound";
    }

    public function exec(): Container
    {
        return new Container($this->controller, $this->method, $this->paramns);
    }

    private function buildUrlCheckPattern(): string
    {
        $patterns[0] = "#{([A-Za-z0-9]+)}#";
        $patterns[1] = "#{([A-Za-z0-9]+:any)}#";

        $replacements[0] = "([0-9]+)";
        $replacements[1] = "([A-Za-z0-9-]+)";

        return '#^' . preg_replace($patterns, $replacements, $this->url) . '$#';
    }

    private function findLabels(): array
    {
        preg_match_all("#{([A-Za-z0-9:]+)}#", $this->url, $matches);

        return $matches[1];
    }

    private function getParamns(array $matches): array
    {
        $paramns = [];

        $labels = $this->findLabels();

        if ($labels != null) {

            foreach ($labels as $i => $label) {

                $label = preg_replace("#:any#", "", $label);

                $paramns[$label] = $matches[$i + 1];
            }
        }

        return $paramns;
    }
}
