<?php

include __DIR__ . '/vendor/autoload.php';


function create_cookies(array $array)
{
    $items = [];
    foreach ($array as $key => $value) {
        $items[] = "$key=$value";
    }

    return implode('; ', $items);
}

/**
 * Session_id required
 */
$cookiesArray = json_decode(file_get_contents('cookies.json'));
$cookiesData = create_cookies($cookiesArray);


$jar = new \GuzzleHttp\Cookie\CookieJar;
$client = new \GuzzleHttp\Client([
    'cookies' => $jar,
]);

$youtubeVideoId = 'pTOPL1Ncsjc';

$data = [
    'msg' => [
        'player_id' => 'youtube',
        'provider_item_id' => "http://www.youtube.com/watch?v=$youtubeVideoId",
        'provider_name' => 'youtube.com',
        'source_host' => 'www.youtube.com',
        'type' => 'video',
        'visible_url' => "http://www.youtube.com/watch?v=$youtubeVideoId",
    ],
    'device' => '<deviceID>',
];

$headers = [
    'cookie' => $cookiesData,
    'Content-Type' => 'application/json; charset=UTF-8',
    'Accept' => 'application/json, text/javascript, */*; q=0.01',
    //'x-csrf-token' => '<token>',
];
$body = json_encode($data);

$request = new \GuzzleHttp\Psr7\Request('POST', 'https://yandex.ru/video/station', $headers, $body);

$response = $client->send($request);

var_dump($response->getReasonPhrase());
echo $response->getStatusCode(); # 200
echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'