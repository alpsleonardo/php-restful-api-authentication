<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
 */


/**
 * Validates client_id passed in header
 *
 * @return bool
 */
function validate_client_id()
{
    $client_id = isset($_SERVER["HTTP_CLIENT_ID"]) ? $_SERVER["HTTP_CLIENT_ID"] : null;
    if (!$client_id) {
        return false;
    }

    return ($client_id === CLIENT_SECRET);
}


/**
 * Validates auth_token passed in header
 *
 * @param string        $secret_key     The secret key for validating the signature
 *
 * @return mixed
 */
function validate_token($secret_key)
{
    $auth_token = isset($_SERVER["HTTP_AUTH_TOKEN"]) ? $_SERVER["HTTP_AUTH_TOKEN"] : null;

    if (!$auth_token) {
        return false;
    }

    $authService = new AuthService();
    $verification = $authService->verify_jwt($auth_token, $secret_key);

    if (!$verification) {
        return false;
    }

    $decoded = json_decode(json_encode($verification), true);
    return $custom_data = $decoded['data'];
}


/**
 * Echoes out the responses
 *
 * @param bool          $error_status   The error status: true for failure and false for success
 * @param mixed         $output         The output data: object or string message
 *
 * @return void
 */
function echo_response($error_status, $output)
{
    $resp = array();

    if ($error_status) {
        $resp['error'] = true;
        $resp['message'] = $output;
    } else {
        $resp['error'] = false;
        $resp['resp'] = $output;
    }

    echo json_encode($resp);
}


/**
 * Selects the query string from the url
 *
 * @return string
 */
function get_current_url() {
    $protocol = "http";
    if($_SERVER["SERVER_PORT"]==443 || (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]=="on")) {
        $protocol .= "s";
        $protocol_port = $_SERVER["SERVER_PORT"];
    } else {
        $protocol_port = 80;
    }
    $host = $_SERVER["HTTP_HOST"];
    $port = $_SERVER["SERVER_PORT"];
    $request_path = $_SERVER["PHP_SELF"];
    $query_str = $_SERVER["QUERY_STRING"];
    $url = $protocol ."://" . $host . (($port != $protocol_port && strpos($host,":")==-1) ? ":" . $port : "") . $request_path . (empty($query_str) ? "" : "?". $query_str);
    return $query_str;
}


/**
 * Returns the url query as associative array
 *
 * @param string        $query          The query string that will be put into an array
 *
 * @return array
 */
function convert_url_query($query) {
    $query_parts = explode('&', $query);

    $params = array();
    foreach ($query_parts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }

    return $params;
}