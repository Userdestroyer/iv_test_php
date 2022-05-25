<?php

$target = "physiqonomics.com";
echo "FOCKING TEST MATE";
$uri = $_SERVER['REQUEST_URI'];

$curl = curl_init();

$url = "https://" . $target . $uri;
echo $url;

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($curl);

//CHANGE TO DOM
$result = preg_replace('/Aadam/', 'COCKSUCKER', $result);
$pattern = '/href="https:\/\/physiqonomics\.com/';
$result = preg_replace($pattern, 'href="', $result);

$dom = new DOMDocument();
@ $dom->loadHTML($result, LIBXML_HTML_NODEFDTD);

foreach ($dom->getElementsByTagName('p') as $data) {
    $data->nodeValue = preg_replace('/\b\w{6}\b/', ':$1', $data->nodeValue);
}
$result = $dom->saveHTML();

echo $result;

curl_close($curl);

?>
