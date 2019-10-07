<?php

namespace Napoleon\Crawler\Exceptions;

class DomException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}