<?php

namespace Napoleon\Crawler;

use Napoleon\Crawler\Exceptions\DOMNotFoundException;

class DOMDocument
{
    /** @var string $www URL | Path */
    protected $www;

    /** @var array $data Return the result */
    protected $data;

    /** @var string $tag Current tag to be search */
    protected $tag;

    /**
     * validates and execute the default values
     *
     * @param string $www file path or link
     */
    public function __construct($www = '')
    {
        libxml_use_internal_errors(true);

        $this->validate($www);

        /** by default: returns /html tag */
        $this->setSearch();
    }

    /**
     * Validate and parse the passing url
     *
     * @param  string $www File path or Url should be pass
     *
     * @return void | \Napoleon\Crawler\Exceptions\DOMNotFoundException
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

    /**
     * [html description]
     *
     * @param  \Closure $func Optional,
     *
     * @return self
     */
    public function html($func = null): self
    {
        if (is_callable($func)) {
            $this->www = call_user_func($func, $this->www);
        }

        return $this->getInfo();
    }

    /**
     * [setSearch description]
     *
     * @param string $tag [description]
     */
    public function setSearch($tag = '/html')
    {
        if ($tag[0] != '/') {
            $tag = '/' . $tag;
        }

        $this->tag = $tag;

        return $this;
    }

    /**
     * The tag to be search
     *
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Top level nodes
     *
     * @return this
     */
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
                'content' => $this->getContent($tag) ?? null,
                'children' => $this->getChildren($tag) ?? null
            ];
        }

        return $this;
    }

    /**
     * Set the crawl to search by class name
     *
     * @param  string $class Class name of a tag
     * @return this
     */
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
                'content' => $this->getContent($tag) ?? null,
                'children' => $this->getChildren($tag) ?? null
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
        if ($tag->hasChildNodes()) {
            $content = preg_replace('{ +}', ' ', $tag->childNodes->item(0)->textContent);

            return trim(preg_replace('/\n/', '', $content));
        }

        return null;
    }

    /**
     * Display the result to be array
     *
     * @return array
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * Helper to get the attribute of a tag
     *
     * @param  Object $tag
     * @return array
     */
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

    /**
     * Helper to get the child nodes of a tag
     *
     * @param  Object $tag
     * @return array
     */
    private function getChildren($tag)
    {
        if ( ! $tag->hasChildNodes()) {
            return null;
        }

        $data = [];
        foreach ($tag->childNodes as $tag) {
            if (isset($tag->tagName)) {
                $data[] = [
                    'tagName' => $tag->tagName,
                    'attributes' => $this->getAttribute($tag),
                    'content' => $this->getContent($tag) ?? null,
                    'children' => $this->getChildren($tag) ?? null
                ];
            }

            continue;
        }

        return $data;
    }
}
