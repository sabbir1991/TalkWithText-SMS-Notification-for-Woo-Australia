<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SMS Gateway handler class
 *
 * @author satosms
 */
class SatSMS_SMS_Gateways {

    private static $_instance;

    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new SatSMS_SMS_Gateways();
        }

        return self::$_instance;
    }

    /**
     * TalkwithText SMS Gateway
     *
     * @param  array $sms_data
     * @return boolean
     */
    function talkwithtext( $sms_data ) {
        $username     = satosms_get_option( 'talkwithtext_username', 'satosms_gateway', '' );
        $password     = satosms_get_option( 'talkwithtext_password', 'satosms_gateway', '' );
        $originator   = satosms_get_option( 'talkwithtext_originator', 'satosms_gateway', '' );
        $phone_prefix = satosms_get_option( 'phone_number_prefix', 'satosms_general', '' );

        if( empty( $username ) || empty( $password ) ) {
            return;
        }

        if ( empty( $phone_prefix ) ) {
            $phone_number = $sms_data['number'];
        } else {
            $phone_number = $phone_prefix . ltrim( $sms_data['number'], '0' );
        }

        $content = 'action=compose' .
                '&username=' . rawurlencode( $username ) .
                '&api_key=' . rawurlencode( $password ) .
                '&sender=' . rawurlencode( $originator ) .
                '&to=' . rawurlencode( $phone_number ) .
                '&message=' . rawurlencode( $sms_data['sms_body'] );

        $response = file_get_contents( 'https://www.talkwithtext.com.au/sms/API/?' . $content );

        return $response;
    }
}
