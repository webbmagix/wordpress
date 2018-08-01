jQuery( document ).on( 'click', '#wmgx_key_validation_btn', function() {
	// probably if I add the line to override default action the script will work with causing the page to reload.
	//var key1val = $('input[name="key1"]').val();
	//var key1_value = $('#key1').val();
	//alert (key1_value);
	
	jQuery.ajax({
		url : wmgx_key_validation_vars.ajax_url,
		type : 'post',
		data : {
			action : 'wmgx_key_validation',
			key1: 'hardcoded-key1',
			key2: 'hardcoded-key2'
		},
		success : function( response ) {
			jQuery('#message-area').html( response );
		}
	});

	return false;
})