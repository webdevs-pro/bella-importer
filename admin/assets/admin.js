jQuery(document).ready(function( $ ) {




















   
	// Submit form data via Ajax
    jQuery( '#bella-import' ).on( 'submit', function( e )
	{
        e.preventDefault();

        var fd = new FormData();
        var file = jQuery(document).find('input[type="file"]');

        console.log(file);
        console.log(fd);
		
		// get file field value using field id
		var fileInputElement = document.getElementById("bella-import-file");

  		var fileName = fileInputElement.files[0].name;
		
		
		if( trim( fileName ) == "" ) {
			alert('Upload your file');
			return false;
		} else {
			$.ajax({
				url: ajaxurl,
				type:"POST",
				processData: false,
				contentType: false,
				data:  new FormData(this),
				success : function( response ){
					var returnedData = JSON.parse(response);
					if(returnedData.code == 200){
						alert('File uploaded!');
					}else{
						alert(returnedData.msg);
					}
				},
			} );
			return false;
		}
		return false;
    });
});