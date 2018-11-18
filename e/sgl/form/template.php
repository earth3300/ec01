<?php
/**
 * Form Template
 *
 * File: template.php
 * Created: 2018-11-17
 * Updated: 2018-11-17
 * Time: 11:42 EST
 */

namespace Earth3300\EC01;

defined( 'NDA' ) || exit;

class FormTemplate extends FormWriter
{

  protected function getForm()
  {
      $items = get_mailer_data();

      $meta = get_mailer_data_meta();

      $response = process_contact_form();

      /** Wrap the form in a div. */
      $str = '<div class="form">' . PHP_EOL;

      /** Open the form. */
      $str .= sprintf( '<form action="" method="post">%s', PHP_EOL );

      /** Add a "use once" field to help prevent misuse. */
      $str .= wp_nonce_field( 'contact-form', 'contact-form', true, false ) . PHP_EOL;

      /** Cycle through the fields */
      foreach ( $items as $item ) {

          if ( $item['display'] ) {

              $required = $item['required'] ? 'required' : '';

              $placeholder = ! empty( $item['placeholder'] ) ? 'placeholder="%s"' : '';

              switch( $item['type'] ) {
                  case 'text' :
                  $str .= sprintf('<p>%s<br /><input type="text" name="contact_%s" %s maxlength="%s" %s value="%s" /></p>%s', $item['label'], $item['name'], $required, $item['maxlength'], $placeholder, $response[ 'contact_' . $item['name'] ], PHP_EOL );
                  break;
                  case 'email' :
                  $str .= sprintf('<p>%s<br /><input type="email" name="contact_%s" %s maxlength="%s" %s value="%s" /></p>%s', $item['label'], $item['name'], $required, $item['maxlength'], $placeholder, $response[ 'contact_' . $item['name'] ], PHP_EOL );
                  break;
                  case 'textarea' :
                  $str .= sprintf('<p>%s<br /><textarea name="contact_%s" %s maxlength="%s" %s>%s</textarea></p>%s', $item['label'], $item['name'], $required, $item['maxlength'], $placeholder, $response[ 'contact_' . $item['name'] ], PHP_EOL );
                  break;
                  default:
              }
          }
      }

      /** A hidden field to attract those anxious bots. */
      $str .= '<input type="hidden" name="contact_best_time" maxlength="40" placeholder="Best time to call..." value="" />' . PHP_EOL;

      /** Submit button. This is disabled for a few seconds to prevent anxious bots from using it. */
      $str .= $meta['submit']['display'] ? sprintf('<p><button id="form-submit" type="submit" class="button button-primary" disabled="true">%s</button></p>%s', $meta['submit']['title'] , PHP_EOL ) : '';

      /** Form response div. */
      $str .= sprintf('<div class="form-response">%s</div><!-- .form-response -->%s', $response['form_response'], PHP_EOL );

      /** Close the form. */
      $str .= '</form>' . PHP_EOL;

      /** Close the form div */
      $str .= '</div><!-- .form -->' . PHP_EOL;

      /** Return the form. */
      return $str;
  }
} // End class
