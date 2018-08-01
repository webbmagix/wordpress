jQuery( document ).on( 'click', '.wmgx_key_validation_btn', function() {
	// probably if I add the line to override default action the script will work with causing the page to reload.
	// or change the button type from submit to button
	jQuery.ajax({
		url : wmgx_key_validation_vars.ajax_url,
		type : 'post',
		data : {
			action : 'wmgx_key_validation'
		},
		success : function( response ) {
			jQuery('#message-area').html( response );
		}
	});

	return false;
})
