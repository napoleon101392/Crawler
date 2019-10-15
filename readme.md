<p align="center">
  <img src="https://travis-ci.org/napoleon101392/Crawler.svg?branch=master" alt="build:passed">
  <img src="https://scrutinizer-ci.com/g/napoleon101392/Crawler/badges/quality-score.png?b=master" title="Scrutinizer Code Quality">
</p>

### Quick example

#### this returns the entire DOM

Consider this HTML to be the `$url`:
```
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

```
use Napoleon\Crawler\DOMDocument;

$url = 'https://www.example.com';
$document = new DOMDocument($url);

print_r($document->html()->get());
```

Above code result:
```
array:1 [
  0 => array:3 [
    "tagName" => "html"
    "attributes" => null
    "children" => array:2 [
      0 => array:3 [
        "tagName" => "head"
        "attributes" => null
        "children" => array:1 [
          0 => array:3 [
            "tagName" => "title"
            "attributes" => null
            "children" => null
          ]
        ]
      ]
      1 => array:3 [
        "tagName" => "body"
        "attributes" => null
        "children" => array:1 [
          0 => array:3 [
            "tagName" => "section"
            "attributes" => null
            "children" => array:2 [
              0 => array:3 [
                "tagName" => "h3"
                "attributes" => null
                "children" => null
              ]
              1 => array:3 [
                "tagName" => "ul"
                "attributes" => null
                "children" => array:2 [
                  0 => array:3 [
                    "tagName" => "li"
                    "attributes" => null
                    "children" => array:1 [
                      0 => array:3 [
                        "tagName" => "a"
                        "attributes" => array:2 [
                          "class" => "anchor -success"
                          "href" => "/redirect/now/1"
                        ]
                        "children" => null
                      ]
                    ]
                  ]
                  1 => array:3 [
                    "tagName" => "li"
                    "attributes" => null
                    "children" => array:1 [
                      0 => array:3 [
                        "tagName" => "a"
                        "attributes" => array:2 [
                          "class" => "anchor"
                          "href" => "/redirect/now/2"
                        ]
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
```
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
```
use Napoleon\Crawler\DOMDocument;

$url = 'https://www.example.com';
$document = new DOMDocument($url);
print_r($document->findByClass('anchor')->get());

```


The result above:
```
.array:2 [
  0 => array:3 [
    "tagName" => "a"
    "attributes" => array:2 [
      "class" => "anchor -success"
      "href" => "/redirect/now/1"
    ]
    "children" => null
  ]
  1 => array:3 [
    "tagName" => "a"
    "attributes" => array:2 [
      "class" => "anchor"
      "href" => "/redirect/now/2"
    ]
    "children" => null
  ]
]
```