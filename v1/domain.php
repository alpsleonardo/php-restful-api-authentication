<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
 */


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Credentials: true");

include_once '../includes/Config.php';
include_once '../includes/Database.php';
include_once '../includes/DomainService.php';
include_once '../includes/AuthService.php';
include_once '../includes/Functions.php';


if (!validate_client_id()) {
    return;
}

// get the method type from the query passed
$method = convertUrlQuery(getCurrentURL())["method"];

// get the token and validate it
$validity = validate_token(ACCESS_SECRET_KEY);

// select the API method to execute functions accordingly
switch ($method) {
    case "getDomainInfo":
        $validity ? get_domain_info($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "createDomain":
        $validity ? create_domain($validity['id']) : echo_response(true, "Invalid token");
        break;

    case "renewDomain":
        $validity ? renew_domain() : echo_response(true, "Invalid token");
        break;

    case "deleteDomain":
        $validity ? delete_domain($validity['id']) : echo_response(true, "Invalid token");
        break;

    default:
        echo_response(true, "Invalid request");
        break;
}


/**
 * Gets all the domain information associated with the user_id (encrypted in the token)
 *
 * @param integer       $user_id        The user id to select the table row
 *
 * @return void
 */
function get_domain_info($user_id)
{
    $domainService = new DomainService();
    $domain = $domainService->find_by_user_id($user_id);
    $domain ? echo_response(false, $domain) : echo_response(true, "Domain not found");
}


/**
 * Creates a new domain with the user_id passed by the token
 *
 * @param integer       $user_id        The user id to select the table row
 *
 * @return void
 */
function create_domain($user_id)
{
    $domain_name = isset($_POST["domain_name"]) ? $_POST["domain_name"] : null;
    if (!$domain_name) {
        echo_response(true, "Missing a parameter");
        return;
    }

    $domainService = new DomainService();
    $new_user = $domainService->create_domain($user_id, $domain_name);
    $new_user ? echo_response(false, $domainService->find_by_domain_id($new_user)) : echo_response(true, "Failed to create a domain");
}


/**
 * Updates the expiry timestamp of a domain associated with the domain_id passed as a param
 *
 * @return void
 */
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
    $domain ? echo_response(false, $domainService->find_by_domain_id($domain_id)) : echo_response(true, "Failed to renew the domain");
}


/**
 * Deletes a domain associated with the domain_id
 *
 * @param integer       $user_id        The user id to select the table row
 *
 * @return void
 */
function delete_domain($user_id)
{
    $domain_id = isset($_POST["domain_id"]) ? $_POST["domain_id"] : null;
    if (!$domain_id) {
        echo_response(true, "Missing a parameter");
        return;
    }

    $domainService = new DomainService();
    $domain = $domainService->delete($domain_id);
    $domain ? echo_response(false, $domainService->find_by_user_id($user_id)) : echo_response(true, "Failed to renew the domain");
}