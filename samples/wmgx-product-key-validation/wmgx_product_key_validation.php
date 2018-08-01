<?php
/**
 * @package wmgx_product_key_validation
 * @version 1.0
 */
 /*
Plugin Name: WebbMagix Product Key Validation
Plugin URI: https://WebbMagix.com
Description: Custom plugin to validate the key pair of the product.
Author: Sathish Kumar
Version: 1.0
Author URI: https://WebbMagix.com
Text Domain: wmgx-product-key-validation
*/

// Shortcode to handle the display of the form and errors if any.
// See: https://codex.wordpress.org/Shortcode_API#Output
add_shortcode('wmgx_key_validator', 'wmgx_key_validation');
function wmgx_key_validation(){
  error_log("Running wmgx_key_validation shortcode");
  ob_start();

  echo wmgx_html_display_form();
  wmgx_form_message();

  return ob_get_clean();
}

function wmgx_html_display_form()
{
  error_log("Running wmgx_html_display_form()");
  ob_start();

  //action="<?php echo esc_url( admin_url( 'admin-post.php' ) );

  ?>
  <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
    <p>Enter the number from the bottle<br />
      <input type="text" name="key1" pattern="[0-9]+" value="<?php isset($_POST['key1']) ? esc_attr($_POST['key1']) : '';
      ?>" size="10" />
    </p>
    <p>Enter the number from the cap<br />
      <input type="text" name="key2" pattern="[0-9]+" value="<?php isset($_POST['key2']) ? esc_attr($_POST['key2']) : '';
      ?>" size="10" />
    </p>
    <p><input type="submit" name="wmgx_key_validation" value="Validate Keys" ></p>
    <input type="hidden" name="wmgx_key_submitted" id="wmgx_key_submitted" value="true" />
    <input type="hidden" name="action" value="wmgx_key_validation" >
  </form>
  <?php

  //echo ob_get_clean();
  return ob_get_clean();
}

// Form succsess/error message
function wmgx_form_message()
{
    global $errors;
    $msg = '';
    if (is_wp_error($errors) && empty($errors->get_error_messages())) {
        $msg  = '<div class="wmgx_success">';
        $msg .= '<p>Your bottle is genuine.</p>';
        $msg .= '</div>';

      //Empty $_POST because we already sent email
      //$_POST = '';
    } else {
        if (is_wp_error($errors) && !empty($errors->get_error_messages())) {
            $error_messages = $errors->get_error_messages();
            foreach ($error_messages as $k => $message) {
                $msg  .= '<div class="wmgx-error '.$k.'">';
                $msg  .= '<p>'.$message.'</p>';
                $msg  .= '</div>';
            }
        }
    }

    error_log("wmgx_form_message() returning: " . $msg);
    echo $msg;
    // return $msg;
}

add_action('admin_post_nopriv_wmgx_key_validation', 'wmgx_key_validation_form');
add_action('admin_post_wmgx_key_validation', 'wmgx_key_validation_form');
function wmgx_key_validation_form() {
    error_log("Running wmgx_key_validation_form()");

    $result = "<h2>Your bottle is kind of genuine.</h2>";

    wp_redirect('/organiqvalleyv2');
    //return $result;
}

// Form validation
function wmgx_validate_form()
{
    error_log("Running wmgx_validate_form()");
    $errors = new WP_Error();

    // No need to validate
    if (! isset($_POST['wmgx_key_validation'])) {
      return $errors;
    }

    if (isset($_POST[ 'key1' ]) && $_POST[ 'key1' ] == '') {
        $errors->add('key1_error', 'Please fill in a valid key1.');
    }

    if (isset($_POST[ 'key2' ]) && $_POST[ 'key2' ] == '') {
        $errors->add('key2_error', 'Please fill in a valid key2.');
    }

    return $errors;
}
