<?php

namespace BirthFramework;

use BirthFramework\Response\Response;
use ReflectionClass;
use ReflectionMethod;

class Container
{
    private $controller;
    private $method;
    private $paramns;

    public function __construct(string $controller, string $method, array $paramns = [])
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->paramns = $paramns;
    }

    public function exec(): Response
    {
        $reflaction = $this->createReflactionClass();

        $instance = $this->createInstance($reflaction);

        $reflactionMethod = $this->getReflactionMethod($reflaction);

        $args = $this->getReflectionArgs($reflactionMethod);

        return $reflactionMethod->invokeArgs($instance, $args);
        
    }

    private function createReflactionClass(): ReflectionClass
    {
        try {

            return new ReflectionClass($this->controller);

        } catch (\Throwable $e) {

            throw new \InvalidArgumentException("O controlador " . $this->controller . " não existe.", 1);
        }
    }

    private function createInstance(ReflectionClass $reflection)
    {
        if(!$reflection->isInstantiable())
        {
            throw new \ReflectionException("O controlador ". $this->controller . " não pode ser instanciado.", 1);
        }

        return $reflection->newInstance();
    }

    private function getReflactionMethod($reflection): ReflectionMethod
    {
        if (!$reflection->hasMethod($this->method)) {

            throw new \InvalidArgumentException("O método " . $this->method . " não existe.", 1);
        }

        return $reflection->getMethod($this->method);
    }

    private function getReflectionArgs($reflectionMethod): array
    {
        $parameters = $reflectionMethod->getParameters();

        $args = [];

        foreach ($parameters as $parameter) {

            if (!isset($this->paramns[$parameter->getName()]) || empty($this->paramns[$parameter->getName()])) {

                throw new \InvalidArgumentException("O parametro " . $parameter->getName() . " não foi informado.", 1);
            }

            $args[] = $this->paramns[$parameter->getName()];
        }

        return $args;
    }
}
