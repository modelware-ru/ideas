<?php

require_once "./init.php";

global $G_SETTING;

$domain = $G_SETTING["domain"];
$token = $G_SETTING["token"];

$method = "post";
$url = "{$domain}/api/register";
$query = "";
$body = [];
$headers = ["X-Auth-Token: {$token}"];

$settingList = [
    "query" => $query,
    "headers" => $headers,
    "body" => json_encode($body),
];

$req_id = write_req($method, $url, $query, $headers, $body);

list($response, $error, $status, $request) = fetch("post", $url, $settingList);

$error = $status['http_code'] === 400;
write_resp($req_id, $response, $error, $status);

// DB::Commit();

if ($error) {
    $response = json_decode($response, true);
    if ($response['errCode'] === 23) {
        echo "there is no active game" . PHP_EOL;
        var_dump($response);
        exit;
    }
}

var_dump($response);
// var_dump($error);
// var_dump($status);
// var_dump($request);
