<?php

namespace Napoleon\Crawler;

use DOMXpath;
use DOMDocument;
use Napoleon\Crawler\Exceptions\DomException;

class Crawler
{
    protected $path;

    protected $www;

    protected $document;

    public function __construct($path)
    {
        libxml_use_internal_errors(true);

        $this->path = $path;

        $this->document = new DOMDocument;
    }

    public static function make($path)
    {
        return new self($path);
    }

    public function to($www)
    {
        $this->www = $www;

        return $this;
    }

    /** TODO:: Refactor */
    public function getContents()
    {
        $this->document->loadHTML(
            file_get_contents($this->www)
        );

        $xpath = new DOMXpath($this->document);
        $tags = $xpath->evaluate($this->path);

        if (!$tags) {
            throw new DomException("No tag found");
        }

        /** TODO:: Refactor response */
        $data = [];
        foreach ($tags as $tag) {
            $data[] = [
                'Namespace Uri' => $tag->namespaceURI,
                'Prefix' => $tag->prefix,
                'Local Name' => $tag->localName,
                'Base Uri' => $tag->baseURI,
                'Text Content' => $tag->textContent,
                'Node' => $tag->nodeValue,
                'href' => $tag->getAttribute('href')
            ];
        }

        if (empty($data)) {
            return [
                'message' => 'No data found'
            ];
        }

        return $data;
    }
}