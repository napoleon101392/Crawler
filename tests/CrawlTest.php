<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Napoleon\Crawler\DOMDocument;
use Napoleon\Crawler\Exceptions\DOMNotFoundException;

class CrawlTest extends TestCase
{
    protected $url;

    public function setUp()
    {
        parent::setUp();

        $this->url = './Page.html';
    }

    /** @test */
    public function return_all_tags_and_its_attributes()
    {
        $document = new DOMDocument($this->url);

        $comparison = $document->html()->get();

        dump($comparison);

        $this->assertEquals($comparison, $this->dummyHtml());
    }

    /** @test */
    public function valid_file()
    {
        $url = './Page.html';

        $document = new DOMDocument($url);

        $comparison = $document->html()->get();

        $this->assertTrue(is_array($comparison));
        $this->assertEquals($comparison, $this->dummyHtml());
    }

    /** @test */
    public function valid_url()
    {
        $url = 'https://www.google.com';

        $document = new DOMDocument($url);

        $comparison = $document->html()->get();

        $this->assertTrue(is_array($comparison));
        $this->assertNotEquals($comparison, $this->dummyHtml());
    }

    /** @test */
    public function not_a_valid_url_and_file()
    {
        $this->expectException(DOMNotFoundException::class);

        $url = 'www.google.com';

        $document = new DOMDocument($url);
        $document->html()->get();
    }

    /** @test */
    public function search_by_tag_and_returns_array()
    {
        $document = new DOMDocument($this->url);

        $response = count($document->findByClass('anchor')->get());

        $this->assertTrue($response == 2);
    }

    /** @test */
    public function tag_path_resolver_test()
    {
        $document = new DOMDocument($this->url);

        $this->assertEquals('/h3', $document->setSearch('h3')->getTag());
    }

    private function dummyHtml()
    {
        $head = [
            'tagName' => 'head',
            'attributes' => null,
            'content' => '',
            'children' => [
                [
                    'tagName' => 'title',
                    'attributes' => null,
                    'content' => 'I am a Page Title',
                    'children' => null
                ]
            ]
        ];

        $body = [
            'tagName' => 'body',
            'attributes' => null,
            'content' => '',
            'children' => [
                [
                    'tagName' => 'section',
                    'attributes' => null,
                    'content' => '',
                    'children' => [
                        [
                            'tagName' => 'h3',
                            'attributes' => null,
                            'content' => 'I am a row title',
                            'children' => null
                        ],
                        [
                            'tagName' => 'ul',
                            'attributes' => null,
                            'content' => '',
                            'children' => [
                                [
                                    'tagName' => 'li',
                                    'attributes' => null,
                                    'content' => '',
                                    'children' => [
                                        [
                                            'tagName' => 'a',
                                            'attributes' => [
                                                'class' => 'anchor -success',
                                                'href' => '/redirect/now/1'
                                            ],
                                            'content' => 'Link 1',
                                            'children' => null
                                        ]
                                    ]
                                ],
                                [
                                    'tagName' => 'li',
                                    'attributes' => null,
                                    'content' => '',
                                    'children' => [
                                        [
                                            'tagName' => 'a',
                                            'attributes' => [
                                                'class' => 'anchor',
                                                'href' => '/redirect/now/2'
                                            ],
                                            'content' => 'Link 2',
                                            'children' => null
                                        ]
                                    ]
                                ],
                            ]
                        ],
                    ]
                ]
            ]
        ];

        $html = [
            [
                'tagName' => 'html',
                'attributes' => null,
                'content' => '',
                'children' => [
                    $head,
                    $body
                ]
            ]
        ];

        return $html;
    }
}