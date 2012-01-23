var Disclosapage = {};

Disclosapage.check = function () {
	if ( ! jQuery) {
		setTimeout('Disclosapage.check()', 10);
	} else {
		Disclosapage.init();
	}
}

Disclosapage.init = function () {
	jQuery.noConflict();

	// Set up a click function for any and all future disclosure targets.
	$('.disclosure_target').live('click', function(){
		// Open or close the branch
		$(this).toggleClass( 'disclosure_triangle_right' ).parent().nextAll('ul.mojo_sub_structure:first').slideToggle();
		// Now record the state change
		var page_bar = $(this).parent();
		var new_disclosure_state = ( $(page_bar).data('disclosure_state') == 'o' ? 'c' : 'o' );
		$(page_bar).data('disclosure_state', new_disclosure_state ); // Record the state change against the bar in the tree
		// Also record the state change back to the database.
		$('form#disclosapage input[name="page_url"]').val( $( 'a.mojo_page_link_inline', page_bar ).attr('href') );
		$('form#disclosapage input[name="new_state"]').val( new_disclosure_state );
		$.post( 
			$('form#disclosapage').attr('action') + 'admin/addons/disclosapage/change_state_ajax', 
			$('form#disclosapage').serialize() 
		);
	});

	// Hook onto any actions that will result in the page tree being loaded.
	// First, hook onto any clicks on the MojoBar 'Pages' button.
	$('#mojo_admin_pages a' ).live('click', function() {
		if (($( '#mojo_site_structure').size() > 0) && ($('#mojo_reveal_page').css('display') != 'none')){
			// The tree is already present *and* visible, so this click is putting it away.
		} else {
			// Otherwise, the tree is being opened and we just need to wait for it.
			setTimeout('Disclosapage.waitForTree()', 100);
		}
	});
	// Also add a hook to the save buttons on the add Add Page and Edit Page panes, because these will lead to the page tree being reopened.
	$('input[name=page_prefs]').live('click', function() {
		setTimeout('Disclosapage.waitForTree()', 100);
	});
	// And lastly add a hook to the 'Pages' breadcrumb link on the Add Page and Edit Page panes, which is a shortcut back to the tree.
	// (It's also on the main Pages page, but we test before adding extra controls below.)
	$('#mojo_reveal_page_back').live('click', function() {
		setTimeout('Disclosapage.waitForTree()', 100);
	});
}

Disclosapage.waitForTree = function () {
	jQuery.noConflict();
	// Wait until the page manipulation tree is in place and visible ...
	if (($( '#mojo_site_structure' ).size() == 0) || ($('#mojo_reveal_page').css('display') == 'none')){
		setTimeout('Disclosapage.waitForTree()', 100);
	} else {
		// ... then add disclosure targets in the appropriate places, if they aren't there already.
		if ( $('.disclosure_target:first').size() == 0 ) {
			$('li:has(ul.mojo_sub_structure) > div.ui-droppable:first-child').prepend('<span class="disclosure_target">&nbsp;&nbsp;&nbsp;&nbsp;</span>').data('disclosure_state', 'o');
		}

		// Now fetch the recorded states of all known disclosure triangles, so that we can close up those that were closed up previously.
		$.post( 
			// Use the action attribute from the communications form just to help us find our ajax script.
			$('form#disclosapage').attr('action') + 'admin/addons/disclosapage/disclosure_state_ajax',
			'',
			function( response ) {
				if ( response.disclosure_state ) {
					Disclosapage.set_disclosure_states( response.disclosure_state );
				}
			},
			'json'
		);
	}
}

Disclosapage.set_disclosure_states = function( disclosure_state ) {
	// Use the supplied disclosure state array to set the state of all known disclosure triangles.
	var base_url = $('form#disclosapage').attr('action');
	for ( var uri_string in disclosure_state ) {
		var page_bar = $( 'a.mojo_page_link_inline[href="' + base_url + uri_string + '"]' ) || $( 'a.mojo_page_link_inline[href="' + base_url + 'index.php?' + uri_string + '"]' )
		var myspan = $( page_bar ).parent().parent().find( 'span.disclosure_target');
		// Record the state in the DOM for when we next toggle it.
		$( myspan ).parent().data( 'disclosure_state',  disclosure_state[uri_string] );
		// If the state of this branch is c=closed, close it now.
		if ( disclosure_state[uri_string] == 'c' ) {
			$( myspan ).toggleClass( 'disclosure_triangle_right' ).parent().nextAll('ul.mojo_sub_structure:first').slideToggle();
		}
	}

	// FUTURE FEATURE: Pages (page bars) that weren't found should have their state entries dropped from the disclosure state list,
	//                 as they have probably either had their page_url_titles changed, or been removed altogether.

}

setTimeout('Disclosapage.check()', 10);


// The approach above is derived from Dan Horrigan's "Equipment" addon for MojoMotor.
// https://github.com/dhorrigan/mojo-equipment
