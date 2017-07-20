<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 3:13 PM
 */


function validate_client_id()
{
    $client_id = isset($_SERVER["HTTP_CLIENT_ID"]) ? $_SERVER["HTTP_CLIENT_ID"] : null;
    if (!$client_id) {
        return false;
    }

    return ($client_id === CLIENT_SECRET);
}

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

function getCurrentURL() {
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
    $querystr = $_SERVER["QUERY_STRING"];
    $url = $protocol ."://" . $host . (($port != $protocol_port && strpos($host,":")==-1)?":".$port:"").$request_path.(empty($querystr)?"":"?".$querystr);
    return $querystr;
}

/**
 * Returns the url query as associative array
 *
 * @param    string    query
 * @return    array    params
 */
function convertUrlQuery($query) {
    $queryParts = explode('&', $query);

    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }

    return $params;
}