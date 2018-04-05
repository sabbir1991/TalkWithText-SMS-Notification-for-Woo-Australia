<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WordPress settings API class
 *
 * @author Tareq Hasan
 */
class SatSMS_Setting_Options {

    private $settings_api;

    function __construct() {

        $this->settings_api = new WeDevs_Settings_API();

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    /**
     * Admin init hook
     * @return void
     */
    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Admin Menu CB
     * @return void
     */
    function admin_menu() {
        add_menu_page( __( 'SMS Settings', 'satosms' ), __( 'SMS Settings', 'satosms' ), 'manage_options', 'sat-order-sms-notification-settings', array( $this, 'plugin_page' ), 'dashicons-email-alt' );
    }

    /**
     * Get All settings Field
     * @return array
     */
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'satosms_general',
                'title' => __( 'General Settings', 'satosms' )
            ),
            array(
                'id' => 'satosms_gateway',
                'title' => __( 'SMS Gateway Settings', 'satosms' )
            ),

            array(
                'id' => 'satosms_message',
                'title' => __( 'SMS Settings', 'satosms' )
            ),

            array(
                'id' => 'satosms_message_diff_status',
                'title' => __( 'SMS Body Settings', 'satosms' )
            ),

            array(
                'id' => 'satosms_creadit_recharge',
                'title' => __( 'SMS Credits & Recharge', 'satosms' )
            )

        );
        return apply_filters( 'satosms_settings_sections' , $sections );
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $buyer_message = "Thanks for purchasing\nYour [order_id] is now [order_status]\nThank you";
        $admin_message = "You have a new Order\nThe [order_id] is now [order_status]\n";
        $settings_fields = array(

            'satosms_general' => apply_filters( 'satosms_general_settings', array(
                array(
                    'name' => 'enable_notification',
                    'label' => __( 'Enable SMS Notifications', 'satosms' ),
                    'desc' => __( 'If checked then enable your sms notification for new order', 'satosms' ),
                    'type' => 'checkbox',
                ),

                array(
                    'name' => 'admin_notification',
                    'label' => __( 'Enable Admin Notifications', 'satosms' ),
                    'desc' => __( 'If checked then enable admin sms notification for new order', 'satosms' ),
                    'type' => 'checkbox',
                    'default' => 'on'
                ),

                array(
                    'name' => 'buyer_notification',
                    'label' => __( 'Enable buyer Notification', 'satosms' ),
                    'desc' => __( 'If checked then buyer can get notification options in checkout page', 'satosms' ),
                    'type' => 'checkbox',
                ),

                array(
                    'name' => 'force_buyer_notification',
                    'label' => __( 'Force buyer notification', 'satosms' ),
                    'desc' => __( 'If select yes then buyer notification option must be required in checkout page', 'satosms' ),
                    'type' => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'   => 'No'
                    )
                ),
                array(
                    'name' => 'phone_number_prefix',
                    'label' => __( 'Phone number prefix', 'satosms' ),
                    'desc' => __( 'Enter your phone number prefix( country code ). if leave empty then this country code need to be added manually in checkout page', 'satosms' ),
                    'type' => 'text',
                    'default' => ''
                ),

                array(
                    'name' => 'buyer_notification_text',
                    'label' => __( 'Buyer Notification Text', 'satosms' ),
                    'desc' => __( 'Enter your text which is appeared in checkout page for buyer as a checkbox', 'satosms' ),
                    'type' => 'textarea',
                    'default' => 'Send me order status notifications via SMS (N.B.: Your SMS will be sent in your billing email. Make sure phone number must have an extension)'
                ),
                array(
                    'name' => 'order_status',
                    'label' => __( 'Check Order Status ', 'satosms' ),
                    'desc' => __( 'In which status you will send notifications', 'satosms' ),
                    'type' => 'multicheck',
                    'options' => wc_get_order_statuses()
                )
            ) ),

            'satosms_gateway' => array(),

            'satosms_message' => apply_filters( 'satosms_message_settings',  array(
                array(
                    'name' => 'sms_admin_phone',
                    'label' => __( 'Enter your Phone Number with extension', 'satosms' ),
                    'desc' => __( '<br>Admin order sms notifications will be send in this number. Please make sure that the number must have a extension (e.g.: +8801626265565 where +88 will be extension)', 'satosms' ),
                    'type' => 'text'
                ),
                array(
                    'name' => 'admin_sms_body',
                    'label' => __( 'Enter your SMS body', 'satosms' ),
                    'desc' => __( ' Write your custom message. When an order is create then you get this type of format message. For order id just insert <code>[order_id]</code> and for order status insert <code>[order_status]</code>. Similarly order items : <code>[order_items]</code>, order amount: <code>[order_amount]</code>', 'satosms' ),
                    'type' => 'textarea',
                    'default' => __( $admin_message, 'satosms' )
                ),

                array(
                    'name' => 'sms_body',
                    'label' => __( 'Enter buyer SMS body', 'satosms' ),
                    'desc' => __( ' Write your custom message. If enbale buyer notification options then buyer can get this message in this format. For order id just insert <code>[order_id]</code> and for order status insert <code>[order_status]</code>. Similarly order items : <code>[order_items]</code>, Order amount: <code>[order_amount]</code>', 'satosms' ),
                    'type' => 'textarea',
                    'default' => __( $buyer_message, 'satosms' )
                ),
            ) ),

            'satosms_message_diff_status' => apply_filters( 'satosms_message_diff_status_settings',  array(

                array(
                    'name' => 'enable_diff_status_mesg',
                    'label' => __( 'Enable different message for different order status', 'satosms' ),
                    'desc' => __( 'If checked then admin and buyer get sms body content according with different enabled order status ( N.B: if this option is enabled then SMS content for admin and buyer in SMS Settings tab doesn\'t work. )', 'satosms' ),
                    'type' => 'checkbox'
                ),

            ) ),
        );

        return apply_filters( 'satosms_settings_section_content', $settings_fields );
    }

    /**
     * Loaded Plugin page
     * @return void
     */
    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

    /**
     * Get sms Gateway settings
     * @return array
     */
    function get_sms_gateway() {
        $gateway = array(
            'none'         => __( '--select--', 'satosms' ),
            'talkwithtext' => __( 'Talk With Text', 'satosms' ),
            'twilio'       => __( 'Twilio', 'satosms' ),
            'clickatell'   => __( 'Clickatell', 'satosms' ),
            'nexmo'        => __( 'Nexmo', 'satosms' ),
            'smsglobal'    => __( 'SMS global', 'satosms' ),
            'hoiio'        => __( 'Hoiio', 'satosms' ),
            'intellisms'   => __( 'Intellisms', 'satosms' ),
        );

        return apply_filters( 'satosms_sms_gateway', $gateway );
    }

} // End of SatSMS_Setting_Options Class

/**
 * SMS Gateway Settings Extra panel options
 * @return void
 */
function satosms_settings_field_gateway() {

    $talkwithtext_username   = satosms_get_option( 'talkwithtext_username', 'satosms_gateway', '' );
    $talkwithtext_password   = satosms_get_option( 'talkwithtext_password', 'satosms_gateway', '' );
    $talkwithtext_originator = satosms_get_option( 'talkwithtext_originator', 'satosms_gateway', '' );
    $twt_helper        = sprintf( 'Don\'t have a TalkwithText Account? <a href="%s" target="_blank">%s</a>', 'https://talkwithtext.com.au/pricing/', 'APPLY HERE' );

    ?>

    <?php do_action( 'satosms_gateway_settings_options_before' ); ?>

    <div class="talkwithtext_wrapper">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $twt_helper, 'satosms' ); ?></strong>
        </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text User email', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_username]" id="satosms_gateway[talkwithtext_username]" value="<?php echo $talkwithtext_username; ?>">
                    <span><?php _e( 'The HTTP API user email that is supplied to your account', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text AP keys', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_password]" id="satosms_gateway[talkwithtext_password]" value="<?php echo $talkwithtext_password; ?>">
                    <span><?php _e( 'The HTTP API Keys of your account', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text Originator', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_originator]" id="satosms_gateway[talkwithtext_originator]" value="<?php echo $talkwithtext_originator; ?>">
                    <span><?php _e( 'The originator of your message (11 alphanumeric or 14 numeric values)', 'satosms' ); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <?php do_action( 'satosms_gateway_settings_options_after' ) ?>
    <?php
}

// hook for Settings API for adding extra sections
add_action( 'wsa_form_bottom_satosms_gateway', 'satosms_settings_field_gateway' );

function satosms_settings_field_message_diff_status() {
    $enabled_order_status = satosms_get_option( 'order_status', 'satosms_general', array() );
    ?>
    <div class="satosms_different_message_status_wrapper satosms_hide_class">
        <hr>
        <?php if ( $enabled_order_status  ): ?>
            <h3>Set your sms content for Buyer</h3>
            <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
                <strong><?php _e( 'Set your sms content according to your enabled order status in General Settings', 'satosms' ); ?></strong><br>
                <span><?php _e( 'Write your custom message. When an order is create then you get this type of format message. For order id just insert <code>[order_id]</code>. Similarly order items : <code>[order_items]</code>, order amount: <code>[order_amount]</code>', 'satosms' ); ?></span>
            </p>
            <table class="form-table">
                <?php foreach ( $enabled_order_status as $buyer_status_key => $buyer_status_value ): ?>
                    <?php
                        $buyer_display_order_status = str_replace( 'wc-', '', $buyer_status_key );
                        $buyer_content_value = satosms_get_option( 'buyer-'.$buyer_status_key, 'satosms_message_diff_status', '' );
                    ?>
                    <tr valign="top">
                        <th scrope="row"><?php echo sprintf( '%s %s', ucfirst( str_replace( '-', ' ', $buyer_display_order_status ) ) , __( 'Order Status', 'satosms' ) ); ?></th>
                        <td>
                            <textarea class="regular-text" name="satosms_message_diff_status[buyer-<?php echo $buyer_status_key; ?>]" id="satosms_message_diff_status[buyer-<?php echo $buyer_status_key; ?>]" cols="55" rows="5"><?php echo $buyer_content_value; ?></textarea>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

            <hr>

            <h3>Set your sms content for Admin</h3>
            <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
                <strong><?php _e( 'Set sms content according to your enabled order status in General Settings', 'satosms' ); ?></strong><br>
                <span><?php _e( 'Write your custom message. When an order is create then you get this type of format message. For order id just insert <code>[order_id]</code>. Similarly order items : <code>[order_items]</code>, order amount: <code>[order_amount]</code>', 'satosms' ); ?></span>
            </p>
            <table class="form-table">
                <?php foreach ( $enabled_order_status as $admin_status_key => $admin_status_value ): ?>
                    <?php
                        $admin_display_order_status = str_replace( 'wc-', '', $admin_status_key );
                        $admin_content_value = satosms_get_option( 'admin-'.$admin_status_key, 'satosms_message_diff_status', '' );
                    ?>
                    <tr valign="top">
                        <th scrope="row"><?php echo sprintf( '%s %s', ucfirst( str_replace( '-', ' ', $admin_display_order_status ) ) , __( 'Order Status', 'satosms' ) ); ?></th>
                        <td>
                            <textarea class="regular-text" name="satosms_message_diff_status[admin-<?php echo $admin_status_key; ?>]" id="satosms_message_diff_status[buyer-<?php echo $admin_status_key; ?>]" cols="55" rows="5"><?php echo $admin_content_value; ?></textarea>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

        <?php else: ?>
            <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-size: 14px;"><?php _e( 'Sorry no order status will be selected for sending sms. Please select some order status from Generan Settings Tab') ?></p>
        <?php endif ?>
    </div>

    <?php
}

add_action( 'wsa_form_bottom_satosms_message_diff_status', 'satosms_settings_field_message_diff_status' );

function satosms_settings_field_satosms_creadit_recharge() {
    ?>
    <div style="padding-left:15px; margin-top:15px">
        <?php include_once SATSMS_DIR . '/lib/includes.php'; ?>

        <p style="">
            <a class="button" href="https://talkwithtext.com.au/account-refill/" target="_blank">Refill Your Account</a>
        </p>
    </div>

    <?php
}

add_action( 'wsa_form_bottom_satosms_creadit_recharge', 'satosms_settings_field_satosms_creadit_recharge' );

