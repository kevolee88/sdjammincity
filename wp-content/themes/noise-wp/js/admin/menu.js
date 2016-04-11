jQuery( function( $ )
{
	var api = window.wpNavMenu
		$div = $( '#noise-sections' ),
		$submit = $( '#submit-noise-sections' ),
		$spinner = $submit.siblings( '.spinner' );

	$submit.on( 'click', function( e )
	{
		e.preventDefault();
		$spinner.show();

		$div.find( 'input:checked' ).each( function()
		{
			var li = $( this ).closest( 'li' ),
				url = li.find( '.menu-item-url' ).val(),
				title = li.find( '.menu-item-title' ).val();

			api.addLinkToMenu( url, title, api.addMenuItemToBottom, function()
			{
				$spinner.hide();
			} );
		} );
	} );
} );