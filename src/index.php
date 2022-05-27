<?php

//SET TARGET
$target = "https://news.ycombinator.com";
$uri = $_SERVER['REQUEST_URI'];
$url = $target . $uri;

curl($url);

function curl($url){
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    
    $result_curl = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    http_response_code($httpcode);
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($result_curl, 0, $header_size);
    $content = substr($result_curl, $header_size);

    //echo $content;
    dom($content);
    curl_close($curl);
}

function dom($content){

    $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');

    $dom = new DOMDocument('1.0', 'utf-8');

    libxml_use_internal_errors(true);

    @ $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $dom->encoding = 'utf-8';

    //GET PATH TO TEXT
    $xp    = new DOMXPath($dom);
    $nodes = $xp->query('/html/body//text()[
        not(ancestor::script) and
        not(ancestor::style) and
        not(normalize-space(.) = "")
    ]');

    //ITERATE THROUGH TEXT NODES
    foreach($nodes as $node) {
        //$re = '/(?<!\S)\pL{6}(?!\S)/u';
        $re = '/(*UCP)\b\pL{6}(?!™)\b/u';
        $node->textContent = preg_replace($re, '\0™', $node->textContent);
    }

    $result = $dom->saveHTML();
    //Проблема тут
    $result = preg_replace('/css" href="/', 'css" href="https://news.ycombinator.com/', $result);
    //$result = preg_replace('/href="https:\/\/news.ycombinator.com/u', 'a href=""', $result);
    $result = preg_replace('/src="/', 'src="https://news.ycombinator.com/', $result);

    $result = html_entity_decode($result, ENT_COMPAT, 'UTF-8');

    if (str_starts_with($result,'<p>')){
        $result = substr($result, 3, -5);
    }

    echo $result;
}

?>
