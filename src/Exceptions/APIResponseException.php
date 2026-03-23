<?php

namespace Zai\Exceptions;

class APIResponseException extends ZaiException
{
    private $response;

    public function __construct(string $message, int $code = 0, $response = null, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
