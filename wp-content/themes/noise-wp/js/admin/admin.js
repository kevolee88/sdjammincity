jQuery( document ).ready( function( $ )
{
	/**
	 * Display meta box base on selected post format
	 * @return void
	 */
	function displayPostMetaBox()
	{
		var format = $( '#post-formats-select input:checked' ).val();

		if( typeof format != 'undefined' )
		{
			$( '.metabox-holder .postbox[id^=format-]' ).hide();
			$( '.metabox-holder .postbox#format-' + format ).stop( true, true ).fadeIn( 500 );
		}

	}

	function displayPageMetaBox()
	{
		var template = $( '#page_template' ).val();

		// Blog description
		if ( 'template-blog.php' == template )
			$( '.postbox#blog-desc' ).stop( true, true ).fadeIn();
		else
			$( '.postbox#blog-desc' ).hide();

		// Hide page title
		if ( 'template-fullwidth-page.php' == template )
			$( '.postbox#page-title' ).stop( true, true ).fadeIn();
		else
			$( '.postbox#page-title' ).hide();

		// Under construction
		if ( 'template-under-construction.php' == template )
			$( '.postbox#under-construction' ).stop( true, true ).fadeIn();
		else
			$( '.postbox#under-construction' ).hide();
	}

	function displayGalleryInput()
	{
		var $galleryPostBox = $( '.postbox#gallery-information' ),
			type = $galleryPostBox.find( 'input[name=type]:checked' ).val();

		if ( 'photos' == type )
		{
			$galleryPostBox.find( '.rwmb-image_advanced-wrapper' ).stop( true, true ).fadeIn();
			$galleryPostBox.find( '.rwmb-file_input-wrapper' ).hide();
		}
		else
		{
			$galleryPostBox.find( '.rwmb-image_advanced-wrapper' ).hide();
			$galleryPostBox.find( '.rwmb-file_input-wrapper' ).stop( true, true ).fadeIn();
		}
	}

	// Display right meta box
	$( '#post-formats-select input' ).on( 'change', displayPostMetaBox );
	$( '#page_template' ).on( 'change', displayPageMetaBox );
	$( '.postbox#gallery-information input[name=type]' ).on( 'change', displayGalleryInput );

	// Check to display right meta box at begining
	$( window ).load( function()
	{
		displayPostMetaBox();
		displayPageMetaBox();
		displayGalleryInput();
	} );

	// Order sections
	$( '.sections-order' ).sortable();

	// Parallax pattern
	var $pattern = $( 'input#parallax_pattern' ).siblings( 'img' );
	if ( $pattern.length && $pattern.attr( 'src' ) )
	{
		var $preview = $( '<div class="pattern-preview"></div>' ).css( {
			width: '100%',
			height: 100,
			marginTop: 10,
			backgroundImage: 'url(' + $pattern.attr( 'src' ) + ')'
		} );

		$pattern.hide().after( $preview );

		$pattern.siblings( '.button-clear' ).on( 'click', function( event )
		{
			event.preventDefault();
			$( this ).siblings( '.pattern-preview' ).hide();
		} );
	}

	// Artist display
	var $artistDisplay = $( 'input[name="artists_display"]' ),
		$artistLimit = $artistDisplay.closest( '.field' ).next();
	function displayArtist()
	{
		if ( 'recent' != $artistDisplay.filter( ':checked' ).val() )
		{
			$artistLimit.stop( true, true ).slideUp();
		}
		else
		{
			$artistLimit.stop( true, true ).slideDown();
		}
	}

	displayArtist();
	$artistDisplay.on( 'change', displayArtist );

	// Ajax set featured artists
	$( '.noise-toggle-featured' ).on( 'click', function( event )
	{
		event.preventDefault();
		var $this = $( this ),
			$star = $this.find( 'span' ),
			$spinner = $this.siblings( '.spinner' );

		$spinner.show();
		$star.hide();
		$.get( $this.attr( 'href' ), function( response )
		{
			if ( response.success )
			{
				if ( response.data.featured )
					$star.removeClass( 'dashicons-star-empty' ).addClass( 'dashicons-star-filled' );
				else
					$star.removeClass( 'dashicons-star-filled' ).addClass( 'dashicons-star-empty' );
			}
			$spinner.hide();
			$star.show();
		} );
	} );

	// Custom color scheme
	$( 'input[name="custom_color_scheme"]' ).change( function ()
	{
		var $this = $( this ),
			$next = $this.closest( '.field' ).next();

		$next[$this.is( ':checked' ) ? 'slideDown' : 'slideUp']();
	} ).trigger( 'change' );

	// Top slider type
	var $sliderType = $( 'input[name="slider_type"]' );
	$sliderType.change( function ()
	{
		var $this        = $( this ),
			$field       = $this.closest( '.field' ),
			$image       = $field.nextAll( '.static-bg-field' ),
			$video       = $field.nextAll( '.video-bg-field' ),
			$slider      = $field.nextAll( '.images-bg-field' ),
			$groupSlider = $slider.filter( '.slider-field' ),
			$info        = $groupSlider.find( '.field-box' ),
			$content     = $groupSlider.next( '.field-editor' ),
			contentType  = $( 'input[name="content_type"]:checked' ).val(),
			type         = $this.val();

		if ( 'images' == type )
		{
			$image.slideUp();
			$video.slideUp();
			$slider.slideDown();
			$groupSlider.find( '.input .field-image' ).slideDown();
			if ( 'content_custom' == contentType )
			{
				$groupSlider.find( '.input .field-text .input' ).slideUp();
				$info.slideUp();
				$content.slideDown();
			}
			else
			{
				$content.slideUp();
			}
		}
		else if ( 'video' == type )
		{
			$image.slideDown();
			$video.slideDown();
			$groupSlider.find( '.input .field-image' ).slideUp();
			if ( 'content_custom' == contentType )
			{
				$groupSlider.slideUp();
				$content.slideDown();
			}
			else
			{
				$groupSlider.find( '.input .field-text .input' ).slideDown();
				$groupSlider.slideDown();
				$content.slideUp();
			}
		}
		else
		{
			$image.slideDown();
			$video.slideUp();
			$groupSlider.find( '.input .field-image' ).slideUp();
			if ( 'content_custom' == contentType )
			{
				$groupSlider.slideUp();
				$content.slideDown();
			}
			else
			{
				$slider.find( '.input .field-text .input' ).slideDown();
				$groupSlider.slideDown();
				$content.slideUp();
			}
		}
	} ).trigger( 'change' );

	// Top slider: Content inside slider
	$( 'input[name="content_type"]' ).change( function ()
	{
		var $this = $( this ),
			$field = $this.closest( '.field' ),
			$image_type = $field.prev( '.field-toggle' ).find( '.button-group input' ).val(),
			$groupSlider = $field.next( '.field-group' ),
			$slider = $groupSlider.find( '.field-slider' ),
			$info = $groupSlider.find( '.field-box' ),
			$content = $groupSlider.next( '.field-editor' ),
			$sliderType = $('input[name="slider_type"]:checked').val(),
			type = $this.val();

		if ( 'content_custom' == type )
		{
			$content.slideDown();
			if ( 'images' == $sliderType )
			{
				$groupSlider.slideDown();
				$slider.find( '.input .field-text .input' ).slideUp();
				$info.slideUp();
			}
			else
			{
				$groupSlider.slideUp();
			}
		}
		else
		{
			$content.slideUp();
			$groupSlider.slideDown();
			if ( 'images' == $sliderType )
			{
				$slider.find( '.input .field-image' ).slideDown();
				$slider.find( '.input .field-text .input' ).slideDown();
			}
			else
			{
				$slider.find( '.input .field-image' ).slideUp();
				$slider.find( '.input .field-text .input' ).slideDown();
			}
		}
	} ).trigger( 'change' );

	$( 'input[name="countdown_bg_type"]' ).change( function ()
	{
		var $this = $( this ),
			$video = $this.closest( '.field' ).next().next( '.field-group' )
			type = $this.val();

		if ( 'video' == type )
		{
			$video.slideDown();
		}
		else
		{
			$video.slideUp();
		}
	} ).trigger( 'change' );

	$( 'input[name="intro_audio"]' ).change( function ()
	{
		var $this = $( this ),
			$select = $this.closest( '.field' ).next( '.field-post' );

		if ( $this.is( ':checked' ) )
		{
			$select.slideDown();
		}
		else
		{
			$select.slideUp();
		}
	} ).trigger( 'change' );

	$( 'input[name="enable_footer_colums"]' ).change( function ()
	{
		var $this = $( this ),
			$images = $this.closest( '.field' ).next( '.field-image_toggle' );

		if ( $this.is( ':checked' ) )
		{
			$images.slideDown();
		}
		else
		{
			$images.slideUp();
		}
	} ).trigger( 'change' );
});
