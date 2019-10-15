<?php

namespace Napoleon\Crawler;

use Napoleon\Crawler\Exceptions\DOMNotFoundException;

class DOMDocument
{
    protected $www;

    protected $data;

    protected $tag;

    public function __construct($www = '')
    {
        libxml_use_internal_errors(true);

        $this->validate($www);

        $this->setSearch();
    }

    /**
     * Validate and parse the passing url
     *
     * @param  string $www File path or Url should be pass
     *
     * @return void
     */
    protected function validate($www)
    {
        if (filter_var($www, FILTER_VALIDATE_URL)) {
            $this->www = file_get_contents($www);

            return;
        }

        if (file_exists($www)) {
            $this->www = file_get_contents($www);

            return;
        }

        throw new DOMNotFoundException;
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
        $document->loadHTML($this->www);

        $xpath = new \DOMXpath($document);
        $tags = $xpath->evaluate($this->tag);

        foreach ($tags as $tag) {
            $this->data[] = [
                'tagName' => $tag->tagName,
                'attributes' => $this->getAttribute($tag),
                'content' => $this->getContent($tag),
                'children' => ! empty($this->getChildren($tag)) ? $this->getChildren($tag) : null
            ];
        }

        return $this;
    }

    public function findByClass($class)
    {
        $document = new \DOMDocument;
        $document->loadHTML($this->www);

        $xpath = new \DOMXpath($document);
        $tags = $xpath->query("//*[contains(@class, '$class')]");

        foreach ($tags as $tag) {
            $this->data[] = [
                'tagName' => $tag->tagName,
                'attributes' => $this->getAttribute($tag),
                'content' => $this->getContent($tag),
                'children' => ! empty($this->getChildren($tag)) ? $this->getChildren($tag) : null
            ];
        }

        return $this;
    }

    /**
     * get content for specific tag only
     *
     * @return [type] [description]
     */
    protected function getContent($tag)
    {
        try {
            $content = preg_replace('/\ +/', ' ', $tag->childNodes->item(0)->textContent);

            return trim(preg_replace('/\n/', '', $content));
        } catch (\Exception $e) {

            return null;
        }
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
                if (is_array($attribute_data) && count($attribute_data) > 1) {
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
                    'content' => $this->getContent($child),
                    'children' => ! empty($this->getChildren($child)) ? $this->getChildren($child) : null
                ];
            }

            continue;
        }

        return $data;
    }
}
