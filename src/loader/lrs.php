<?php

namespace loader\lrs;

function send_batch_to_lrs(array $config, array $statements) {
    $endpoint = $config['endpoint'];
    $username = $config['username'];
    $password = $config['password'];

    $url = $endpoint.'/statements';
    $auth = base64_encode($username.':'.$password);
    $post_data = json_encode($statements);

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($request, CURLOPT_HTTPHEADER, [
        'Authorization: Basic '.$auth,
        'X-Experience-API-Version: 1.0.0',
        'Content-Type: application/json',
    ]);

    $response_text = curl_exec($request);
    $response_code = curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);
    
    if ($response_code !== 200) {
        throw new \Exception($response_text);
    }
}

function get_statement_batches(array $config, array $statements) {
    $max_batch_size = $config['max_batch_size'];
    if (!empty($max_batch_size) && $max_batch_size < count($statements)) {
        return array_chunk($statements, $max_batch_size);
    }
    return [$statements];
}

function load(array $config, array $statements) {
    $batches = get_statement_batches($config, $statements);
    foreach ($batches as $batch) {
        send_batch_to_lrs($config, $batch);
    }
}