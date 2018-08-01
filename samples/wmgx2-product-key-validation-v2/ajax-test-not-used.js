jQuery(document).ready( function($) {
	$("#wmgx_key_validation_btn").click( function() {
		alert ("Running ajax-test.js");
		// Valiation
		$('.error').hide();
		var key1 = $('input[name="key1"]').val();
		alert (key1);
		if (key1 == "") {
			$('label[name="key1_error"]').show();
			return false;
		}

		$.ajax({
				url : the_ajax_script.ajaxurl,
				type: 'post',
				data : $("#wmgx_key_validation_form").serialize()
			}).done(function(response){ //
				alert (response);
				$("#server-results").html(response);
			});
		});
	});
