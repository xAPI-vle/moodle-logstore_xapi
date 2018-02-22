<?php

namespace loader;

function load_to_lrs(array $config, array $events) {
    $endpoint = $config['endpoint'];
    $username = $config['username'];
    $password = $config['password'];

    $url = $endpoint.'/statements';
    $auth = base64_encode($username.':'.$password);
    $events_data = json_encode($events);

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_POSTFIELDS, $events_data);
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