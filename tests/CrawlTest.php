<?php

namespace Tests;

use DOMXpath;
use DOMDocument;
use PHPUnit\Framework\TestCase;
use Napoleon\Crawler\DomFinder;
use Napoleon\Crawler\Crawler;

class CrawlTest extends TestCase
{
    protected $url;

    public function __construct()
    {
        $this->url = './Page.html';

        $this->byPathResult = (new DomFinder)->byPath('/html/body/section/ul/li/a');

        $this->byClassResult = (new DomFinder)->byClass('anchor'); # " //*[@class='anchor'] "
    }

    /** @test */
    public function find_anchor_tag()
    {
        $dom = Crawler::make($this->byPathResult->path())->to($this->url);

        $this->assertEquals($dom->getContents(), [
            [
                'tag' => 'a',
                'attribute' => [
                    'class' => 'anchor',
                    'href' => '/redirect/now/1'
                ]
            ],
            [
                'tag' => 'a',
                'attribute' => [
                    'class' => 'anchor',
                    'href' => '/redirect/now/2'
                ]
            ],
        ]);
    }
}