<img src="https://scrutinizer-ci.com/g/napoleon101392/Crawler/badges/quality-score.png?b=master" title="Scrutinizer Code Quality">

### Quick example

Consider this HTML to be the `$url`:
``` html
<!DOCTYPE html>
<html>
<head>
    <title>I am a Page Title</title>
</head>
<body>
    <section>
        <h3> I am a row title </h3>

        <ul>
            <li> <a class="anchor -success" href="/redirect/now/1"> Link 1 </a> </li>
            <li> <a class="anchor" href="/redirect/now/2"> Link 2 </a> </li>
        </ul>
    </section>
</body>
</html>
```

``` php
use Napoleon\Crawler\DOMDocument;

$url = 'https://www.example.com';
$document = new DOMDocument($url);

print_r($document->html()->get());
```

Above code result:
``` php
array:1 [
  0 => array:4 [
    "tagName" => "html"
    "attributes" => null
    "content" => ""
    "children" => array:2 [
      0 => array:4 [
        "tagName" => "head"
        "attributes" => null
        "content" => ""
        "children" => array:1 [
          0 => array:4 [
            "tagName" => "title"
            "attributes" => null
            "content" => "I am a Page Title"
            "children" => null
          ]
        ]
      ]
      1 => array:4 [
        "tagName" => "body"
        "attributes" => null
        "content" => ""
        "children" => array:1 [
          0 => array:4 [
            "tagName" => "section"
            "attributes" => null
            "content" => ""
            "children" => array:2 [
              0 => array:4 [
                "tagName" => "h3"
                "attributes" => null
                "content" => "I am a row title"
                "children" => null
              ]
              1 => array:4 [
                "tagName" => "ul"
                "attributes" => null
                "content" => ""
                "children" => array:2 [
                  0 => array:4 [
                    "tagName" => "li"
                    "attributes" => null
                    "content" => ""
                    "children" => array:1 [
                      0 => array:4 [
                        "tagName" => "a"
                        "attributes" => array:2 [
                          "class" => "anchor -success"
                          "href" => "/redirect/now/1"
                        ]
                        "content" => "Link 1"
                        "children" => null
                      ]
                    ]
                  ]
                  1 => array:4 [
                    "tagName" => "li"
                    "attributes" => null
                    "content" => ""
                    "children" => array:1 [
                      0 => array:4 [
                        "tagName" => "a"
                        "attributes" => array:2 [
                          "class" => "anchor"
                          "href" => "/redirect/now/2"
                        ]
                        "content" => "Link 2"
                        "children" => null
                      ]
                    ]
                  ]
                ]
              ]
            ]
          ]
        ]
      ]
    ]
  ]
]
...
```

#### Search by specific class name of a tag
Consider this to be `https://www.example.com`:
``` html
<!DOCTYPE html>
<html>
<head>
    <title>I am a Page Title</title>
</head>
<body>
    <section>
        <h3> I am a row title </h3>

        <ul>
            <li> <a class="anchor -success" href="/redirect/now/1"> Link 1 </a> </li>
            <li> <a class="anchor" href="/redirect/now/2"> Link 2 </a> </li>
        </ul>
    </section>
</body>
</html>
```
``` php
use Napoleon\Crawler\DOMDocument;

$url = 'https://www.example.com';
$document = new DOMDocument($url);
print_r($document->findByClass('anchor')->get());

```


The result above:
``` php
.array:2 [
  0 => array:3 [
    "tagName" => "a"
    "attributes" => array:2 [
      "class" => "anchor -success"
      "href" => "/redirect/now/1"
    ],
    "content" => "Link 1",
    "children" => null
  ]
  1 => array:3 [
    "tagName" => "a"
    "attributes" => array:2 [
      "class" => "anchor"
      "href" => "/redirect/now/2"
    ],
    "content" => "Link 2",
    "children" => null
  ]
]
```
