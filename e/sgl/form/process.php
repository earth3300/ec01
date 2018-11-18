<?php

defined('NDA') || exit;

function process_contact_form(){
    require_once( dirname(__FILE__) . '/data.php' );
    $data = get_mailer_data();
    if ( ! empty( $_POST ) && check_admin_referer( 'contact-form', 'contact-form' ) ) {
        if ( strlen( $_POST['contact_best_time'] ) > 0 ){
          return;
        }
        $items = array( 'contact_name', 'contact_email', 'contact_subject', 'contact_message' );
        $response['form_response'] = '';
        foreach ( $_POST as $key => $value ) {
            if ( in_array( $key, $items ) ){
                $response[$key] = esc_attr( $_POST[$key] );
            }
        }
        if ( ! empty ( $response ) ){
            $email = get_mailer_data_email();
            $response['contact_subject'] = $email['sent_from'];
            $r = mail_message( get_mail_admin(), $response );
            $meta = get_mailer_data_meta();
           if ( $r === true ){
               $response['form_response'] = $meta['success']['text'];
               $response['contact_name'] = '';
               $response['contact_email'] = '';
               $response['contact_subject'] = '';
               $response['contact_message'] = '';
               $response['message'] = '';
           }
           else {
               $response['form_response'] = $meta['failure']['text'];
           }

        }
        return $response;
    }
    else {
        return false;
    }
}
