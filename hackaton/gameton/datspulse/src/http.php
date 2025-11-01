<?php

function fetch($method, $url, $settings = [])
{
    // defaults
    $settings['body'] = isset($settings['body']) ? $settings['body'] : '';

    $request = [];
    $request['method'] = $method;
    $request['body'] = $settings['body'];

    $ch = curl_init();

    if (isset($settings['query'])) {
        $url = $url . '?' . $settings['query'];
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    $request['url'] = $url;

    if ($method === 'post') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $settings['body']);
    }

    if ($method === 'put') {
        curl_setopt($ch, CURLOPT_PUT, 1);
    }

    if ($method === 'delete') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    if (isset($settings['ipResolve'])) {
        if ($settings['ipResolve'] === 'ipv4') {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }

        if ($settings['ipResolve'] === 'ipv6') {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
        }
    }

    if (isset($settings['headers'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $settings['headers']);
        $request['header'] = $settings['headers'];
    }

    if (isset($settings['userpwd'])) {
        curl_setopt($ch, CURLOPT_USERPWD, $settings['userpwd']);
    }

    if (isset($settings['upload'])) {
        curl_setopt($ch, CURLOPT_UPLOAD, true);
        curl_setopt($ch, CURLOPT_INFILESIZE, $settings['upload']['size']);
        curl_setopt($ch, CURLOPT_INFILE, $settings['upload']['file']);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $file = null;
    if (isset($settings['file'])) {
        $file = @fopen($settings['file'], 'w');
        curl_setopt($ch, CURLOPT_FILE, $file);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch);

    curl_close($ch);

    if (isset($settings['file'])) {
        fclose($file);
    }

    return [$response, (empty($error) ? false : $error), $status, $request];
}
