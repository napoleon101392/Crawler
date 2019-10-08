<?php

namespace Napoleon\Crawler\Exceptions;

class DOMException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}