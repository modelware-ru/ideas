<?php
require_once "./db.php";

function write_req($method, $url, $query, $headers, $body)
{

    $headers = json_encode($headers);
    $body = json_encode($body);

    $db = DB::GetConnection();
    $stmt = 'INSERT INTO req (method, url, query, headers, body) VALUES (:method, :url, :query, :headers, :body)';
    $ids = $db->insert($stmt, [0 => ['method' => $method, 'url' => $url, 'query' => $query, 'headers' => $headers, 'body' => $body]]);
    return $ids[0];
}

function write_resp($req_id, $data, $error, $status)
{
    $data = json_encode($data);
    $status = json_encode($status);

    $db = DB::GetConnection();
    $stmt = 'INSERT INTO resp (req_id, data, success, stutus) VALUES (:reg_id, :data, :success, :status)';
    $db->insert($stmt, [0 => ['reg_id' => $req_id, 'data' => $data, 'success' => $error ? 'NO' : 'YES', 'status' => $status]]);
}
