<?php

//SET TARGET
$target = "https://news.ycombinator.com";
$uri = $_SERVER['REQUEST_URI'];
$url = $target . $uri;

//curl($url);
dom($url);

function curl($url){
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $result_curl = curl_exec($curl);

    //$result_curl = preg_replace('/>([^><)]*)</m', '\0AAA', $result_curl);
    // $re = '/>([^><)]*)</';
    // preg_match_all($re, $result_curl, $matches);
    // print_r($matches);

    echo $result_curl;

    curl_close($curl);
}

function dom($url){

    $dom = new DOMDocument();
    //$dom->loadHTML(mb_convert_encoding($result_curl, 'HTML-ENTITIES', 'UTF-8'));
    @ $dom->loadHTMLFile($url, LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
    libxml_use_internal_errors(true);
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

    $result = $dom->saveHTML();
    echo $result;
}

?>
