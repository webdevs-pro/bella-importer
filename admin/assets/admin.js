jQuery(document).ready(function( $ ) {

	var posts = {};

	$( '.bci-show-step-2' ).on( 'click', function() {
		$( '.bci-step-1' ).remove();
		$( '.bci-step-2' ).show();
	} );














   
	// Submit form data via Ajax
	jQuery( '#bella-import' ).on( 'submit', function( e ) {
		e.preventDefault();

		var fd = new FormData();
		var file = jQuery(document).find('input[type="file"]');
		var individual_file = file[0].files[0];
		fd.append( 'file', individual_file ); 
		fd.append( 'action', 'bci_upload_xml' );  
		$( '#error-response' ).html( '' );
		$( '.bci-step-2 .button.button-primary' ).prop('disabled', true );
		$( '.bci-step-2 .spinner' ).addClass( 'is-active' );

		$.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: fd,
			contentType: false,
			processData: false,
			success: function( response ) {
				var json = JSON.parse( response );
				console.log(json.status);
				var status = json.status;
				if ( status == 'ok' ) {
					posts = json.posts;
					console.log( json.posts );
					$( '.bci-step-2' ).remove();
					$( '.bci-step-3' ).show();
					step_3();
				}
				if ( status == 'error' ) {
					$( '#error-response' ).html( json.message ); 
					$( '.bci-step-2 .button.button-primary' ).prop('disabled', false );
					$( '.bci-step-2 .spinner' ).removeClass( 'is-active' );
				}
			}
		});

		return false;
	});



	function step_3() {
		alert();
	}




});