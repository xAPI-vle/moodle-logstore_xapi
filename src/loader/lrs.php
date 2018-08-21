<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace src\loader\lrs;
defined('MOODLE_INTERNAL') || die();

use src\loader\utils as utils;

function correct_endpoint($endpoint) {
    $endswithstatements = substr($endpoint, -11) === "/statements";
    if ($endswithstatements) {
        return substr($endpoint, 0, -11);
    }
    return rtrim($endpoint, '/');
}

function send_http_statements(array $config, array $statements) {
    $endpoint = $config['lrs_endpoint'];
    $username = $config['lrs_username'];
    $password = $config['lrs_password'];

    $url = correct_endpoint($endpoint).'/statements';
    $auth = base64_encode($username.':'.$password);
    $postdata = json_encode($statements);

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($request, CURLOPT_HTTPHEADER, [
        'Authorization: Basic '.$auth,
        'X-Experience-API-Version: 1.0.0',
        'Content-Type: application/json',
    ]);

    $responsetext = curl_exec($request);
    $responsecode = curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);

    if ($responsecode !== 200) {
        throw new \Exception($responsetext);
    }
}

function load_transormed_events_to_lrs(array $config, array $transformedevents) {
    try {
        $statements = array_reduce($transformedevents, function ($result, $transformedevent) {
            $eventstatements = $transformedevent['statements'];
            return array_merge($result, $eventstatements);
        }, []);
        send_http_statements($config, $statements);
        $loadedevents = utils\construct_loaded_events($transformedevents, true);
        return $loadedevents;
    } catch (\Exception $e) {
        $logerror = $config['log_error'];
        $logerror("Failed load for event id #" . $eventobj->id . ": " .  $e->getMessage());
        $logerror($e->getTraceAsString());
        $loadedevents = utils\construct_loaded_events($transformedevents, false);
        return $loadedevents;
    }
}

function get_event_batches(array $config, array $transformedevents) {
    $maxbatchsize = $config['lrs_max_batch_size'];
    if (!empty($maxbatchsize) && $maxbatchsize < count($transformedevents)) {
        return array_chunk($transformedevents, $maxbatchsize);
    }
    return [$transformedevents];
}

function load(array $config, array $events) {
    // Attempts to load events that were transformed successfully in batches.
    $successfultransformevents = utils\filter_transformed_events($events, true);
    $batches = get_event_batches($config, $successfultransformevents);
    $loadedevents = array_reduce($batches, function ($result, $batch) use ($config) {
        $loadedbatchevents = load_transormed_events_to_lrs($config, $batch);
        return array_merge($result, $loadedbatchevents);
    }, []);
    
    // Flags events that weren't transformed successfully as events that didn't load.
    $failedtransformevents = utils\filter_transformed_events($events, false);
    $nonloadedevents = utils\construct_loaded_events($failedtransformevents, false);

    // Returns loaded and non-loaded events to avoid re-processing.
    return array_merge($loadedevents, $nonloadedevents);
}