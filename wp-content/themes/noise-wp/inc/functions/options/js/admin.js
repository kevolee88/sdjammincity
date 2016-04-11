jQuery( function ( $ )
{
	var $wrap = $( '#theme-options' ),
		frame;

	frame = wp.media( {
		library: {
			type: 'image'
		},
		title: FitWP_Options.media_title
	} );

	/**
	 * Select menu item
	 *
	 * @return void
	 */
	function menu()
	{
		$wrap.on( 'click', '#menu a', function ( e )
		{
			e.preventDefault();

			var $el = $( this ),
				$li = $el.parent(),
				$ul = $li.parent();

			if ( $li.hasClass( 'active' ) )
				return;

			$ul.find( 'li' ).removeClass( 'active' );
			$ul.find( 'ul' ).slideUp( 250 );
			$li.children( 'ul' ).slideDown( 250 )
				.parents( '#menu li' ).addClass( 'active' );

			// Active first child menu when clicked menu is root
			if ( $ul.attr( 'id' ) == 'menu' && $li.children( 'ul' ).length )
			{
				$li = $li.find( '> ul > li:first-child' );
				$el = $li.find( '> a' );
			}

			$li.addClass( 'active' );

			// Show section title
//			$( '#section-title' ).html( $el.text() );

			// Show section content
			$( $el.attr( 'href' ) )
				.addClass( 'active' )
				.siblings().removeClass( 'active' );
		} );
		$( '#menu a:eq(0)' ).trigger( 'click' );
	}

	/**
	 * Ajax save & reset
	 *
	 * @return void
	 */
	function ajax()
	{
		// Ajax save options
		$wrap.on( 'click', '.save-options', function ( e )
		{
			e.preventDefault();

			// Fix editor ajax saving issue
			// $( '.field-editor textarea' ).each( function()
			// {
			// 	var id = $( this ).attr( 'id' ),
			// 		ifr = tinymce.DOM.get( id + '_ifr'),
			// 		height;

			// 	if ( ifr )
			// 		height = ifr.style.height;

			// 	tinymce.execCommand( 'mceRemoveEditor', false, id );
			// 	tinymce.execCommand( 'mceAddEditor', false, id );

			// 	if ( height )
			// 		tinymce.DOM.setStyle( ifr, 'height', height );
			// } );

			showAjaxProcessing();

			$.post( ajaxurl, {
				action: 'fitwp_options_save',
				_ajax_nonce: FitWP_Options.nonce_save,
				data: $( '#theme-options' ).serialize()
			}, function()
			{
				hideAjaxProcessing();
			} );
		} );

		// Ajax reset options
		$wrap.on( 'click', '.reset-options', function ( e )
		{
			if ( !confirm( FitWP_Options.reset_notice ) )
				return false;

			e.preventDefault();
			showAjaxProcessing();

			$.post( ajaxurl, {
				'action': 'fitwp_options_reset',
				'_ajax_nonce': FitWP_Options.nonce_reset
			}, function ()
			{
				hideAjaxProcessing();
				location.reload();
			} );
		} );

		// Ajax import options
		$wrap.on( 'click', '.import-options', function( e )
		{
			if ( !confirm( FitWP_Options.import_notice ) )
				return false;

			var data = $( this ).prev( 'textarea' ).val();

			e.preventDefault();
			showAjaxProcessing();

			$.post( ajaxurl, {
				action: 'fitwp_options_import',
				data: data,
				_ajax_nonce: FitWP_Options.nonce_import
			}, function ()
			{
				hideAjaxProcessing();
				location.reload();
			} );
		} );

	}

	/**
	 * Handle image select from media library
	 *
	 * @return void
	 */
	function image()
	{
		// Select file from media library
		$wrap.on( 'click', '.button-select', function ( e )
		{
			e.preventDefault();
			var $el = $( this );

			// Remove all attached 'select' event
			frame.off( 'select' );

			// Update inputs when select image
			frame.on( 'select', function ()
			{
				var url = frame.state().get( 'selection' ).first().toJSON().url;
				$el.siblings( 'input' ).val( url )
					.siblings( '.button-clear' ).show()
					.siblings( 'img' ).attr( 'src', url );
			} );

			frame.open();
		} );

		// Clear selected images
		$wrap.on( 'click', '.button-clear', function ( e )
		{
			e.preventDefault();
			$( this ).hide()
				.siblings( 'input' ).val( '' )
				.siblings( 'img' ).attr( 'src', '' );
		} );
	}

	/**
	 * Change on button group
	 *
	 * @return void
	 */
	function buttonGroup()
	{
		$wrap.on( 'change', '.button-group input', function ()
		{
			var $t = $( this ),
				$label = $t.closest( 'label' ),
				$parent = $label.parent();
			if ( $parent.hasClass( 'multiple' ) )
				$t.is( ':checked' ) ? $label.addClass( 'active' ) : $label.removeClass( 'active' );
			else
				$label.addClass( 'active' ).siblings().removeClass( 'active' );
		} );
		$( '.button-group input:checked' ).trigger( 'change' );
	}

	/**
	 * Change on image toggle
	 *
	 * @return void
	 */
	function imageToggle()
	{
		$wrap.on( 'change', '.field-image_toggle input', function ()
		{
			$( this ).closest( 'label' ).addClass( 'active' ).siblings().removeClass( 'active' );
		} );
		$( '.field-image_toggle input:checked' ).trigger( 'change' );
	}

	/**
	 * Add/remove sidebars
	 *
	 * @return void
	 */
	function sidebars()
	{
		$wrap.on( 'click', '.field-custom_sidebars .button-add', function ( e )
		{
			e.preventDefault();
			var $t = $( this ),
				sidebar = $t.siblings( 'input' ).val();
			if ( !sidebar )
				return;

			var $ul = $t.siblings( 'ul' );
			$ul.find( '.hidden' ).clone().appendTo( $ul ).removeClass( 'hidden' ).find( 'input' ).val( sidebar );
		} );
		$wrap.on( 'click', '.field-custom_sidebars .button-remove', function ( e )
		{
			e.preventDefault();
			$( this ).parent().remove();
		} );
	}

	/**
	 * Social icon
	 *
	 * @return void
	 */
	function social()
	{
		$wrap.on( 'change', '.social-icon input', function ( e )
		{
			e.preventDefault();
//			$( this ).siblings().toggleClass( 'active' );
			if ( !$( this ).val() )
				$( this ).parents( '.social-icon' ).find( '.icon50' ).removeClass( 'active' );
			else
				$( this ).parents( '.social-icon' ).find( '.icon50' ).addClass( 'active' );
		} );
	}


	/**
	 * Change switcher state
	 *
	 * @return void
	 */
	function switcher()
	{
		$wrap.on( 'change', '.switcher input[type=checkbox]', function()
		{
			if ( $( this ).is( ':checked' ) )
				$( this ).parent().addClass( 'on' );
			else
				$( this ).parent().removeClass( 'on' );
		} );
	}

	/**
	 * Change color scheme
	 *
	 * @return void
	 */
	function colorScheme()
	{
		// Add .fitsc-active to label when its input is checked
		$wrap.on( 'change', '.color-scheme input', function ()
		{
			$( this ).parent().addClass( 'active' ).siblings().removeClass( 'active' );
		} );
	}

	/**
	 * Show ajax processing
	 *
	 * @param el
	 */
	function showAjaxProcessing()
	{
		$( '.ajax-processing' ).show();
	}

	/**
	 * Hide ajax processing
	 *
	 * @return void
	 */
	function hideAjaxProcessing()
	{
		$( '.ajax-processing' ).hide();
	}

	// Run
	$( '.color' ).wpColorPicker();
	menu();
	ajax();
	image();
	buttonGroup();
	imageToggle();
	sidebars();
	social();
	switcher();
	colorScheme();

	// Fix editor ajax saving issue
	window.onload = function ()
	{
		for ( i in tinymce.editors )
		{
			tinymce.editors[i].on( 'change', function ( e )
			{
				e.target.save();
			} );
		}
	}
} );
