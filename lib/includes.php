<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$username = satosms_get_option( 'talkwithtext_username', 'satosms_gateway', '' );
$password = satosms_get_option( 'talkwithtext_password', 'satosms_gateway', '' );
$originator = satosms_get_option( 'talkwithtext_originator', 'satosms_gateway', '' );

//bail out if no username or password given
if ( empty( $username ) || empty( $password ) ) {
    echo 'No username and api key found';
}

$content = 'action=balance' .
        '&username=' . rawurlencode( $username ) .
        '&api_key=' . rawurlencode( $password );

$response = file_get_contents( 'https://www.talkwithtext.com.au/sms/API/?' . $content );

echo $response;