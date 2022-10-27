<?php

namespace BirthFramework\response;

class HtmlResponse extends Response
{
    public function __construct(string $content, $statusCode = 200)
    {
        parent::__construct($content, $statusCode);
    }

    protected function buildContent()
    {
        return $this->content;
    }
    
}
