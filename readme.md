### Quick example

#### this returns the entire DOM and returns an array
```
use Napoleon\Crawler\DOMDocument;

$url = 'www.example.com';
$document = new DOMDocument($url);

print_r($document->html()->get());
```

#### Search by specific class name of a tag
Consider this to be `www.example.com`
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

$url = 'www.example.com';
$document = new DOMDocument($url);
print_r($document->findByClass('anchor')->get());

```
The result above
```
[
  0 => array:2 [
    "class" => "anchor -success"
    "href" => "/redirect/now/1"
  ]
  1 => array:2 [
    "class" => "anchor"
    "href" => "/redirect/now/2"
  ]
]
```