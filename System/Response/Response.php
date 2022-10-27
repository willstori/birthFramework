<?php

namespace BirthFramework\response;

abstract class Response
{
    protected $statusCode;
    protected $headers;
    protected $content;

    public function __construct(string | array | object $content = null, int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    abstract protected function buildContent();

    public function send()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header($name . ": " . $value);
        }

        echo $this->buildContent();
    }
}
