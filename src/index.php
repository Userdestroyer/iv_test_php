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
    
    $result_curl = curl_exec($curl);
    
    $html = str_get_html($result_curl);

    //$result_curl = preg_replace('/>([^><)]*)</', '>\0AAA<', $result_curl);
    //$re = '/>([^><)]*)</';
    //preg_match_all($re, $result_curl, $matches);
    //var_dump($matches);

    echo $result_curl;

    curl_close($curl);
}

function dom($url){
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTMLFile(mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
    //@ $dom->loadHTMLFile($url, LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);

    // //GET PATH TO TEXT
    // $xp    = new DOMXPath($dom);
    // $nodes = $xp->query('/html/body//text()[
    //     not(ancestor::script) and
    //     not(ancestor::style) and
    //     not(normalize-space(.) = "")
    // ]');

    // //ITERATE THROUGH TEXT NODES
    // foreach($nodes as $node) {
    //     $node->textContent = preg_replace('/\b\w{6}\b/', '\0™', $node->textContent);
    // }
    
    $result = $dom->saveHTML($dom->documentElement);
    echo substr($result, 3, -3);
    //echo $result;
}

?>
