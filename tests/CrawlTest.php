<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Napoleon\Crawler\DOMDocument;

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

        $this->assertEquals(
            $document->html()->get(),
            $this->dummyHtml()
        );
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
            'children' => [
                [
                    'tagName' => 'title',
                    'attributes' => null,
                    'children' => null
                ]
            ]
        ];

        $body = [
            'tagName' => 'body',
            'attributes' => null,
            'children' => [
                [
                    'tagName' => 'section',
                    'attributes' => null,
                    'children' => [
                        [
                            'tagName' => 'h3',
                            'attributes' => null,
                            'children' => null
                        ],
                        [
                            'tagName' => 'ul',
                            'attributes' => null,
                            'children' => [
                                [
                                    'tagName' => 'li',
                                    'attributes' => null,
                                    'children' => [
                                        [
                                            'tagName' => 'a',
                                            'attributes' => [
                                                'class' => 'anchor -success',
                                                'href' => '/redirect/now/1'
                                            ],
                                            'children' => null
                                        ]
                                    ]
                                ],
                                [
                                    'tagName' => 'li',
                                    'attributes' => null,
                                    'children' => [
                                        [
                                            'tagName' => 'a',
                                            'attributes' => [
                                                'class' => 'anchor',
                                                'href' => '/redirect/now/2'
                                            ],
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
                'children' => [
                    $head,
                    $body
                ]
            ]
        ];

        return $html;
    }
}