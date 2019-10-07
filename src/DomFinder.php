<?php

namespace Napoleon\Crawler;

use Napoleon\Crawler\Exceptions\ValidationException;

class DomFinder
{
    protected $path;

    public function byPath($path)
    {
        $this->path = $path;

        if (is_null($path) || $path = "" || empty($path)) {
            throw new ValidationException('Path cant be null or empty');
        }

        return $this;
    }

    public function byClass($path)
    {
        $this->path = $path;

        return $this;
    }

    public function path()
    {
        return $this->path;
    }
}