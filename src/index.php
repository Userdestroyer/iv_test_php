<?php

//SET TARGET
$target = "https://news.ycombinator.com";
$uri = $_SERVER['REQUEST_URI'];
$url = $target . $uri;

curl($url);
//dom($url);

function curl($url){
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    
    $result_curl = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($result_curl, 0, $header_size);
    $content = substr($result_curl, $header_size);
    //header($header);

    //$html = str_get_html($result_curl);

    //$result_curl = preg_replace('/>([^><)]*)</', '>\0AAA<', $result_curl);
    //$re = '/>([^><)]*)</';
    //preg_match_all($re, $result_curl, $matches);
    //var_dump($matches);

    //In case if matching words will be in the array
    //$array_of_words = "/(?:|')(Trying|first|Проверочка|and|и|корень)(?:|')/g";
    //preg_replace($array_of_words, $result_curl, $matches);
    //echo $result_curl;

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
        $node->textContent = preg_replace('/\b\w{6}\b/', '\0™', $node->textContent);
    }

    $result = $dom->saveHTML();

    //$result = preg_replace('/href="/', 'href="https://news.ycombinator.com/', $result);
    $result = preg_replace('/src="/', 'src="https://news.ycombinator.com/', $result);

    $result = html_entity_decode($result, ENT_COMPAT, 'UTF-8');

    if (str_starts_with($result,'<p>')){
        $result = substr($result, 3, -5);
    }

    echo $result;
}

?>
