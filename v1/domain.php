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
include_once '../includes/Functions.php';

$client_id = isset($_SERVER["HTTP_CLIENT_ID"]) ? $_SERVER["HTTP_CLIENT_ID"] : null;
if (!validate_client_id()) {
    return;
}

$method = isset($_POST["method"]) ? $_POST["method"] : null;
$validity = validate_token(ACCESS_SECRET_KEY);

switch ($method) {
    case "get_domains":
        $validity ?
            get_domain_info($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "create_domain":
        $validity ?
            create_domain($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "renew_domain":
        $validity ?
            renew_domain() : echo_response(true, "Invalid token");
        break;

    case "delete_domain":
        $validity ?
            delete_domain($validity['id']) : echo_response(true, "Invalid token");
        break;

    default:
        echo_response(true, "Invalid request");
        break;
}

function get_domain_info($user_id)
{
    $domainService = new DomainService();
    $domain = $domainService->find_by_user_id($user_id);
    $domain ?
        echo_response(false, $domain) : echo_response(true, "Domain not found");
}

function create_domain($user_id)
{
    $domain_name = isset($_POST["domain_name"]) ? $_POST["domain_name"] : null;
    if (!$domain_name) {
        echo_response(true, "Missing a parameter");
        return;
    }

    $domainService = new DomainService();
    $new_user = $domainService->create_domain($user_id, $domain_name);
    $new_user ?
        echo_response(false, $domainService->find_by_domain_id($new_user)) : echo_response(true, "Failed to create a user");
}

function renew_domain()
{
    $domain_id = isset($_POST["domain_id"]) ? $_POST["domain_id"] : null;
    $year = isset($_POST["year"]) ? $_POST["year"] : null;
    if (!$domain_id || !$year) {
        echo_response(true, "Missing a parameter");
        return;
    }

    $domainService = new DomainService();
    $domain = $domainService->renew_expiry($year, $domain_id);
    $domain ?
        echo_response(false, $domainService->find_by_domain_id($domain_id)) : echo_response(true, "Failed to renew the domain");
}

function delete_domain($user_id)
{
    $domain_id = isset($_POST["domain_id"]) ? $_POST["domain_id"] : null;
    if (!$domain_id) {
        echo_response(true, "Missing a parameter");
        return;
    }

    $domainService = new DomainService();
    $domain = $domainService->delete($domain_id);
    $domain ?
        echo_response(false, $domainService->find_by_user_id($user_id)) : echo_response(true, "Failed to renew the domain");
}