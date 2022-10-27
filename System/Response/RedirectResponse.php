<?php 
namespace BirthFramework\response;

class RedirectResponse extends Response{

    public function __construct($url, $statusCode = 301)
    {
        parent::__construct(null, $statusCode, ['location' => $url]);
    }

    protected function buildContent()
    {
        return $this->content;
    }
}