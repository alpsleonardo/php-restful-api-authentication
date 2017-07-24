<?php
/**
 *
 * @author     Minseok Kim (m1nk1m)
 * @copyright  Copyright (c) 2017 Minseok Kim. All rights reserved.
 *
 */


require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;


/**
 * Defines an object to generate, verify JSON WEB TOKEN (JWT).
 */
class AuthService
{
    /**
     * Generates token package to be sent for authentication
     *
     * @param array         $custom_data    The data to be encrypted in the jwt (passed to 'create_jwt' method)
     *
     * @return array
     *
     * @uses create_jwt
     */
    public function generate_tokens($custom_data)
    {
        $issued_at   = time();
        $not_before  = $issued_at + 10;
        $expiry      = $not_before + 7200;

        $access_token = $this->create_jwt(ALGORITHM, ACCESS_SECRET_KEY,
            $custom_data, $issued_at,
            $not_before, $expiry);

        $refresh_token = $this->create_jwt(ALGORITHM, REFRESH_SECRET_KEY,
            $custom_data, $issued_at,
            $not_before, $expiry+ 30000);

        // not encoded yet
        return [
            'access_expiry' => $expiry,
            'access_token' => $access_token,
            'refresh_token' => $refresh_token
        ];
    }


    /**
     * creates a token
     *
     * @param string        $algorithm      The algorithm used to encrypt the token
     * @param string        $secret_key     The token secret key to be used for signature: either for access token or refresh token
     * @param array         $custom_data    The data to be encrypted in the jwt
     * @param integer       $issue_time     The unix timestamp of the token issue time
     * @param integer       $not_before     The unix timestamp of the token validity start time
     * @param integer       $expire         The unix timestamp of the token expiry time
     *
     * @return string
     *
     * @uses create_jwt
     */
    private function create_jwt($algorithm, $secret_key, $custom_data, $issue_time, $not_before, $expire)
    {
        $token_id = base64_encode(random_bytes(32));
        $server_name = 'http://localhost/php-cira-test-api/';
        $data = [
            'iat'  => $issue_time,
            'jti'  => $token_id,
            'iss'  => $server_name,
            'nbf'  => $not_before,
            'exp'  => $expire,
            'data' => $custom_data
        ];

        return JWT::encode($data, base64_decode($secret_key), $algorithm);
    }


    /**
     * verifies a token
     *
     * @param string        $tok_val        The token value passed for verification
     * @param string        $secret_key     The token secret key to check the signature issued with: either for access token or refresh token
     *
     * @return object | bool
     *
     */
    public function verify_jwt($tok_val, $secret_key)
    {
        try {
            $decode_token = JWT::decode($tok_val, base64_decode($secret_key), array(ALGORITHM));
            return $decode_token;
        } catch (Exception $e) {
            return false;
        }
    }
}