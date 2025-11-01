<?php

$query = $_GET;
$headerList = getallheaders();
$body = file_get_contents('php://input');

$data = empty($body) ? [] : json_decode($body, true);

$ret = [
    "date" => date('Y-m-d H:i:s'),
    "query" => $query,
    "headerList" => $headerList,
    "body" => $data,
];

file_put_contents("./log/request_log.txt", json_encode($ret, JSON_PRETTY_PRINT));

echo "<pre>" . json_encode($ret, JSON_PRETTY_PRINT) . "</pre>";
