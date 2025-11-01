<?php

require_once "./init.php";

global $G_SETTING;

$domain = $G_SETTING["domain"];
$token = $G_SETTING["token"];


$antId = "a77a6fc5-9d6b-4104-a444-bba0899bcd2d";
$q_start = -40;
$r_start = 0;

do {

    $method = "post";
    $url = "{$domain}/api/move";
    $query = "";
    $body = [
        "moves" => [
            [
                "ant" => $antId,
                "path" => [
                    [
                        "q" => $q_start,
                        "r" => $r_start,
                    ],
                ]
            ]
        ]
    ];

    $t = json_encode($body, JSON_PRETTY_PRINT);

    // {
    //   "moves": [
    //     {
    //       "ant": "4c0d5c50-c1e7-403b-90ae-8c621b215946",
    //       "path": [
    //         {
    //           "q": 10,
    //           "r": 20
    //         }
    //       ]
    //     }
    //   ]
    // }

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
    $isQ = rand(0, 1) === 0;
    $is1 = rand(0, 1) === 0;

    if ($isQ) {
        $q_start = $q_start + ($is1 ? 1 : -1);
    } else {
        $r_start = $r_start + ($is1 ? 1 : -1);
    }

    echo "(q,r) = ({$q_start},{$r_start})" . PHP_EOL;
    sleep(5);
} while (true);


// var_dump($error);
// var_dump($status);
// var_dump($request);
