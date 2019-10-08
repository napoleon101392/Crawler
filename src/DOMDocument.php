<?php

namespace Napoleon\Crawler;

class DOMDocument implements \Countable
{
    protected $www;

    protected $document;

    protected $data;

    protected $tag;

    public function __construct($www = '')
    {
        libxml_use_internal_errors(true);

        $this->www = $www;

        $this->setSearch();
    }

    /** move to base */
    public function count()
    {
        return count($this->data);
    }

    public function html()
    {
        return $this->getInfo();
    }

    public function setSearch($tag = '/html')
    {
        if ($tag[0] != '/') {
            $tag = '/' . $tag;
        }

        $this->tag = $tag;

        return $this;
    }

    public function getTag()
    {
        return $this->tag;
    }

    protected function getInfo()
    {
        $document = new \DOMDocument;
        $document->loadHTML(file_get_contents($this->www));

        $xpath = new \DOMXpath($document);
        $tags = $xpath->evaluate($this->tag);

        foreach ($tags as $tag) {
            $this->data[] = [
                'tagName' => $tag->tagName,
                'attributes' => $this->getAttribute($tag),
                'children' => $this->getChildren($tag)
            ];
        }

        return $this;
    }

    public function findByClass($class)
    {
        $document = new \DOMDocument;
        $document->loadHTML(file_get_contents($this->www));

        $xpath = new \DOMXpath($document);
        $tags = $xpath->query("//*[contains(@class, '$class')]");

        foreach ($tags as $tag) {
            $this->data[] = [
                'tagName' => $tag->tagName,
                'attributes' => $this->getAttribute($tag),
                'children' => ! empty($this->getChildren($tag)) ? $this->getChildren($tag) : null
            ];
        }

        return $this;
    }

    public function get()
    {
        return $this->data;
    }

    private function getAttribute($tag)
    {
        if ( ! $tag->hasAttributes()) {
            return null;
        }

        $return = [];
        foreach ($tag->attributes as $attribute) {
            foreach ($attribute->childNodes as $attribute_data) {
                if (count($attribute_data) > 1) {
                    $return[$attribute->localName][] = $attribute_data->data;
                }

                $return[$attribute->localName] = $attribute_data->data;
            }
        }

        return $return;
    }

    private function getChildren($tag)
    {
        if ( ! $tag->hasChildNodes()) {
            return null;
        }

        $data = [];
        foreach ($tag->childNodes as $child) {
            if (isset($child->tagName)) {
                $data[] = [
                    'tagName' => $child->tagName,
                    'attributes' => $this->getAttribute($child),
                    'children' => ! empty($this->getChildren($child)) ? $this->getChildren($child) : null
                ];
            }

            continue;
        }

        return $data;
    }
}
