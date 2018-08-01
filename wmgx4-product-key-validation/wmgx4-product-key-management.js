jQuery(document).ready(function() {

///// Button clicked for generating the keys on the Admin page
// jQuery('#key-date').val(new Date());

jQuery('#generate-keys-btn').click(function() {
    //alert("clicked..");
    var input_date = jQuery('#key-date').val();
    //alert("date:" + input_date );
    jQuery('#generate-keys-msg-area').html("Generating...");

    jQuery.ajax({
      url : wmgx_key_script_vars.ajax_url,
      type : 'post',
      data : {
        action : 'wmgx_generate_keys',
        key_date: input_date
      },
      success : function( response ) {
        jQuery('#generate-keys-msg-area').html( response );
      }
    });

    return false;
  });


  ///// Button clicked for displaying keys - todo
  jQuery('#display-keys-btn').click(function() {
      //alert("clicked..");
      var input_start_date = jQuery('#key-start-date').val();
      var input_end_date = jQuery('#key-end-date').val();
      //alert("date:" + input_date );
      jQuery('#display-keys-msg-area').html("Retrieving...");

      jQuery.ajax({
        url : wmgx_key_script_vars.ajax_url,
        type : 'post',
        data : {
          action : 'wmgx_display_keys',
          key_date: input_start_date,
          key_end_date: input_end_date
        },
        success : function( response ) {
          jQuery('#display-keys-msg-area').html( response );
        }
      });

      return false;
  });


}); //end jQuery(document).ready(function()
