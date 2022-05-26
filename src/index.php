<?php

$target = "https://news.ycombinator.com";
$uri = $_SERVER['REQUEST_URI'];

$curl = curl_init();

$url = $target . $uri;

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result_curl = curl_exec($curl);

//REFACTOR
$pattern_src = '/src="/';
$result_curl = preg_replace($pattern_src, 'src="https://news.ycombinator.com/', $result_curl);
$pattern_css = '/css" href="/';
$result_curl = preg_replace($pattern_css, 'css" href="https://news.ycombinator.com/', $result_curl);


$dom = new DOMDocument();
@ $dom->loadHTML(mb_convert_encoding($result_curl, 'HTML-ENTITIES', 'UTF-8'));

$xp    = new DOMXPath($dom);
$nodes = $xp->query('/html/body//text()[
    not(ancestor::script) and
    not(ancestor::style) and
    not(normalize-space(.) = "")
]');

foreach($nodes as $node) {
    $node->textContent = preg_replace('/\b\w{6}\b/', '\0™', $node->textContent);
}

$result = $dom->saveHTML();

echo $result;

curl_close($curl);

?>
