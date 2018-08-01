jQuery(document).ready(function() {

  jQuery('.error').hide();

  ////// Button clicked for Validating the keys

  jQuery('#wmgx_key_validation_btn').click(function() {
    jQuery('#message-area').html('');
    var key1val = jQuery('input#key1').val();
    var key2val = jQuery('input#key2').val();

    if (key1val == "") {
      jQuery("label#key1_error").show();
      jQuery("input#key1").focus();
      return false;
    }

    if (key2val == "") {
      jQuery("label#key2_error").show();
      jQuery("input#key2").focus();
      return false;
    }

    jQuery('.error').hide();

    jQuery.ajax({
  		url : wmgx_key_validation_vars.ajax_url,
  		type : 'post',
  		data : {
  			action : 'wmgx_key_validation',
  			key1: key1val,
  			key2: key2val
  		},
  		success : function( response ) {
  			jQuery('#message-area').html( response );
  		}
  	});

  	return false;
  });



///// Button clicked for generating the keys on the Admin page
jQuery('#key-date').val(new Date());

jQuery('#generate-keys-btn').click(function() {
    alert("clicked");
    var input_date = jQuery('input#key-date').val();
    alert("date: #input_date" );

    jQuery.ajax({
      url : wmgx_key_validation_vars.ajax_url,
      type : 'post',
      data : {
        action : 'wmgx_generate_keys',
        keydate: input_date
      },
      success : function( response ) {
        jQuery('#keys-display-area').html( response );
      }
    });

    return false;
  });

}); //end jQuery(document).ready(function()
