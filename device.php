<?php

include __DIR__ . '/vendor/autoload.php';

class Device {
    /** @var string */
    public $id;
    /** @var string */
    public $name;
    /** @var bool */
    public $online;
    /** @var string */
    public $platform;
    /** @var bool */
    public $screen_capable;
    /** @var bool */
    public $screen_present;

    public static function createByArray(array $items): self
    {
        $obj = new static();
        foreach ($items as $field => $value) {
            $obj->{$field} = $value;
        }

        return $obj;
    }
}

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
$cookiesArray = json_decode(file_get_contents('cookies.json'), true);
$cookiesData = create_cookies($cookiesArray);


$jar = new \GuzzleHttp\Cookie\CookieJar;
$client = new \GuzzleHttp\Client([
    'cookies' => $jar,
]);

$headers = [
    'cookie' => $cookiesData,
];

$request = new \GuzzleHttp\Psr7\Request('GET', 'https://quasar.yandex.ru/devices_online_stats', $headers);
$response = $client->send($request);
$arrayResponse = json_decode($response->getBody(), true);
$devices = [];

foreach ($arrayResponse['items'] ?? [] as $item) {
    $devices[] = Device::createByArray($item);
}


var_dump($devices);
