<?php
/*
Plugin Name: jQuery Contact Shortcode
Plugin URI: https://pippinsplugins.com/contact-shortcode/
Description: Adds a shortcode for displaying a jQuery Validated Contact Form
Version: 1.0
Author: Pippin Williamson
Author URI: http://184.173.226.218/~pippin
*/

// plugin root folder
$cs_base_dir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));


function pippin_shortcode_contact( $atts, $content = null)	{

	// gives access to the plugin's base directory
	global $cs_base_dir;

	extract( shortcode_atts( array(
      'email' => get_bloginfo('admin_email')
      ), $atts ) );

	$content .= '
		<script type="text/javascript">
			var $j = jQuery.noConflict();
			$j(window).load(function(){
				$j("#contact-form").submit(function() {
				  // validate and process form here
					var str = $j(this).serialize();
					   $j.ajax({
					   type: "POST",
					   url: "' . $cs_base_dir . 'sendmail.php",
					   data: str,
					   success: function(msg){
							$j("#note").ajaxComplete(function(event, request, settings)
							{
								if(msg == "OK") // Message Sent? Show the Thank You message and hide the form
								{
									result = "Your message has been sent. Thank you!";
									$j("#fields").hide();
								}
								else
								{
									result = msg;
								}
								$j(this).html(result);
							});
						}
					 });
					return false;
				});
			});
		</script>';

    // now we put all of the HTML for the form into a PHP string
		$content .= '<div id="post-a-comment" class="clear">';
		$content .= '<div id="fields">';
		$content .= '<h4>Send A Message</h4>';
		$content .= '<form id="contact-form" action="">';
		$content .= '<input name="to_email" type="hidden" id="to_email" value="' . $email . '"/>';
		$content .= '<p>';
		$content .= '<input name="name" type="text" id="name"/>';
		$content .= '<label class="error" for="name">Name *</label>';
		$content .= '</p>';
		$content .= '<p>';
		$content .= '<input name="email" type="text" id="email"/>';
		$content .= '<label for="email">E-mail address *</label>';
		$content .= '</p>';
		$content .= '<p><textarea rows="" cols="" name="message"></textarea></p>';
		$content .= '<p><input type="submit" value="Submit" class="button" id="contact-submit" /></p>';
		$content .= '</form>';
		$content .= '</div><!--end fields-->';
		$content .= '<div id="note"></div> <!--notification area used by jQuery/Ajax -->';
		$content .= '</div>';
	return $content;
}
add_shortcode('contact', 'pippin_shortcode_contact');

wp_enqueue_script('jquery');
