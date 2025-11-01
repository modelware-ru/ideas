<?php

require_once "./init.php";

global $G_SETTING;

$domain = $G_SETTING["domain"];
$token = $G_SETTING["token"];

do {

    $method = "get";
    $url = "{$domain}/api/arena";
    $query = "";
    $body = [];
    $headers = ["X-Auth-Token: {$token}", "Accept: application/json"];

    $settingList = [
        "query" => $query,
        "headers" => $headers,
        "body" => json_encode($body),
    ];

    $req_id = write_req($method, $url, $query, $headers, $body);

    list($response, $error, $status, $request) = fetch($method, $url, $settingList);

    $error = $status['http_code'] >= 400;
    write_resp($req_id, $response, $error, $status);

    // DB::Commit();

    if ($error) {
        $response = json_decode($response, true);
        if (is_array($response) && array_key_exists('errCode', $response) && $response['errCode'] === 23) {
            echo "there is no active game" . PHP_EOL;
            exit;
        }

        echo "unknown error" . PHP_EOL;
        exit;
    }
    echo "Sleeping..." . PHP_EOL;
    sleep(10);
} while (true);


// var_dump($error);
// var_dump($status);
// var_dump($request);
