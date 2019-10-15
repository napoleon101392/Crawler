<?php

namespace Napoleon\Crawler\Exceptions;

class DOMNotFoundException extends \Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message);
    }
}