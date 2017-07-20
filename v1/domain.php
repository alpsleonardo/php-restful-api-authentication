<?php
/**
 * Created by PhpStorm.
 * User: m1nk1m
 * Date: 2017-07-19
 * Time: 12:28 PM
 */


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");

include_once '../includes/Config.php';
include_once '../includes/Database.php';
include_once '../includes/DomainService.php';
include_once '../includes/AuthService.php';


$method = isset($_POST["method"]) ? $_POST["method"] : null;
$validity = validate_token();

switch ($method) {
    case "get_domains":
        $validity ? get_domain_info($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "create_domain":
        $validity ? create_domain($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "update_domain":
        $validity ? renew_domain() : echo_response(true, "Invalid token");
        break;

    case "delete_domain":
        $validity ? delete_domain() : echo_response(true, "Invalid token");
        break;

    default:
        echo_response(true, "Invalid request");
        break;
}

function get_domain_info($user_id)
{
    $dmainService = new DomainService();
    $domain = $dmainService->find_by_user_id($user_id);
    $domain ? echo_response(false, $domain) : echo_response(true, "Domain not found");
}

function create_domain($user_id)
{

}

function renew_domain()
{

}

function delete_domain()
{

}