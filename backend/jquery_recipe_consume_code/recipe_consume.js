jQuery(function($) {

    // AJAX url
    // currently pointing to a subdomain I set up which already has the theme activated
    // and 3 recipes defined
    var ajaxurl = 'https://cd001.chromaroot.com/wp-admin/admin-ajax.php';

    // define action
	var data = {
		action:"get_recipes"
	};

    // get recipes via AJAX
	$.get( ajaxurl, data, function( response ) {
        // parse JSON response
		response = $.parseJSON(response);
        // output recipe array to browser console
		console.log(response);
	});

});
