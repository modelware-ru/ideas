<?php

require_once "./init.php";

global $G_SETTING;

$method = "post";
$url = "http://localhost:8823/api/mirror.php";
$query = "a=1&b=2";
$body = [
    "one" => 1,
    "two" => "two",
];
$headers = ["MyHeader: value", "AnotherHeader: 12"];

$settingList = [
    "query" => $query,
    "headers" => $headers,
    "body" => json_encode($body),
];

$req_id = write_req($method, $url, $query, $headers, $body);

list($response, $error, $status, $request) = fetch("post", $url, $settingList);

write_resp($req_id, $response, $error, $status);

DB::Commit();
// var_dump($response);
// var_dump($error);
// var_dump($status);
// var_dump($request);
