<?php
/**
 * Class for show fields
 *
 * @package   FitWP Options Framework
 * @author    The FitWP Team <anh@fitwp.com>
 * @copyright Copyright (c) 2013 by fitwp.com
 * @version   1.0
 */
class FitWP_Options_Fields
{
	/**
	 * HTML helper object
	 *
	 * @var FitWP_Options_HTML
	 */
	public $html;

	/**
	 * Constructor
	 *
	 * @return FitWP_Options_Fields
	 */
	function __construct()
	{
		$this->html = new FitWP_Options_HTML;
	}

	/**
	 * Show list of fields
	 *
	 * @param  array $fields List of fields
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function show( $fields )
	{
		$html = '';
		foreach ( $fields as $field )
		{
			// Get field wrapper HTML and allow theme to hook to change
			$field_html = $this->field_wrapper_html( $field );
			$field_html = fitwp_options_filter( 'wrapper_html', $field_html, $field );

			$html .= $field_html;
		}
		return $html;
	}

	/**
	 * Get field wrapper HTML
	 *
	 * @param array  $field Field options
	 * @param string $value Field value
	 *
	 * @return  string
	 */
	function field_wrapper_html( $field, $value = '' )
	{
		$class = isset( $field['class'] ) ? " {$field['class']}" : '';

		return $this->html->div(
			array( 'class' => "field field-{$field['type']}$class" ),
			$this->field_label( $field ),
			$this->field_input( $field, $value )
		);
	}

	/**
	 * Get field label
	 *
	 * @param   array $field
	 *
	 * @return  string
	 * @since   1.0.0
	 */
	function field_label( $field )
	{
		if ( empty( $field['label'] ) )
			return '';

		$desc = empty( $field['label_desc'] ) ? '' : $this->html->div( 'class=desc', $field['label_desc'] );
		return $this->html->div( 'class=label', $this->html->label( '', $field['label'] ), $desc );
	}

	/**
	 * Get field input
	 *
	 * @param   array $field
	 * @param  mixed  $value Field value
	 *
	 * @return  string
	 * @since   1.0.0
	 */
	function field_input( $field, $value = '' )
	{
		if ( !$value && isset( $field['id'] ) )
		{
			$default = isset( $field['default'] ) ? $field['default'] : false;
			$value = get_theme_mod( $field['id'], $default );

			// Hook to change how to get value
			$value = fitwp_options_filter( 'get_value', $value, $field );
		}

		// Get field HTML and allow theme to hook to change
		$html = $this->{$field['type']}( $field, $value );
		$html = fitwp_options_filter( 'html', $html, $field );

		$suffix = empty( $field['suffix'] ) ? '' : $this->html->span( 'class=suffix', $field['suffix'] );
		$desc = empty( $field['input_desc'] ) ? '' : $this->html->div( 'class=desc', $field['input_desc'] );
		return $this->html->div( 'class=input', $html, $suffix, $desc );
	}

	/**
	 * Get HTML for divider field
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function divider( $field, $value )
	{
		return '<hr>';
	}

	/**
	 * Get HTML for text field
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function text( $field, $value )
	{
		$size = isset( $field['size'] ) ? $field['size'] : '';
		$atts = array(
			'id'    => $field['id'],
			'class' => $size ? "input-$size" : '',
			'name'  => $field['id'],
			'type'  => 'text',
			'value' => $value,
		);
		return $this->html->input( $atts );
	}

	/**
	 * Get HTML for number field
	 *
	 * @param  array $field Field
	 * @param  int   $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function number( $field, $value )
	{
		$size = isset( $field['size'] ) ? $field['size'] : 'mini';
		$atts = array(
			'id'    => $field['id'],
			'class' => "input-$size",
			'name'  => $field['id'],
			'type'  => 'number',
			'value' => $value,
		);
		return $this->html->input( $atts );
	}

	/**
	 * Get HTML for email field
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 * @since   1.0.0
	 */
	function email( $field, $value )
	{
		$size = isset( $field['size'] ) ? $field['size'] : '';
		$atts = array(
			'id'    => $field['id'],
			'class' => $size ? "input-$size" : '',
			'name'  => $field['id'],
			'type'  => 'email',
			'value' => $value,
		);
		return $this->html->input( $atts );
	}

	/**
	 * Get HTML for textarea field
	 *
	 * @param   array  $field Field
	 * @param   string $value
	 *
	 * @return  string
	 * @since   1.0.0
	 */
	function textarea( $field, $value )
	{
		$size = isset( $field['size'] ) ? $field['size'] : 'xxlarge';
		$atts = array(
			'class' => "input-$size",
			'id'    => $field['id'],
			'name'  => $field['id'],
			'rows'  => isset( $field['rows'] ) ? intval( $field['rows'] ) : 5
		);
		return $this->html->textarea( $atts, $value );
	}

	/**
	 * Get HTML for select field
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function select( $field, $value )
	{
		$size = isset( $field['size'] ) ? $field['size'] : '';
		$select_atts = array(
			'class' => $size ? "input-{$size}" : '',
			'id'    => $field['id'],
			'name'  => $field['id'],
		);

		$items = array( $this->html->option( array( 'value' => '' ), __( 'Select', 'fitwp' ) ) );
		foreach ( $field['options'] as $v => $label )
		{
			$atts = array( 'value' => $v );
			if ( $v == $value )
				$atts['selected'] = 'selected';
			$items[] = $this->html->option( $atts, $label );
		}

		return $this->html->select( $select_atts, implode( '', $items ) );
	}

	/**
	 * Get HTML for radio field
	 *
	 * @param array  $field Field
	 * @param string $value
	 * @param string $sep   Separator, default is '<br>'. Used in "toggle" field
	 *
	 * @return  string
	 */
	function radio( $field, $value, $sep = '<br>' )
	{
		$items = array();
		foreach ( $field['options'] as $k => $v )
		{
			$atts = array(
				'value' => $k,
				'type'  => 'radio',
				'name'  => $field['id'],
			);
			if ( $k == $value )
				$atts['checked'] = 'checked';

			$items[] = $this->html->label( '', $this->html->input( $atts ), ' ' . $v );
		}
		return implode( $sep, $items );
	}

	/**
	 * Get HTML for checkbox list field
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function checkbox_list( $field, $value )
	{
		$value = (array) $value;
		$items = array();
		foreach ( $field['options'] as $k => $v )
		{
			$atts = array(
				'value' => $k,
				'type'  => 'checkbox',
				'name'  => $field['id'] . '[]',
			);
			if ( in_array( $k, $value ) )
				$atts['checked'] = 'checked';

			$items[] = $this->html->label( '', $this->html->input( $atts ), ' ' . $v );
		}

		return implode( '<br>', $items );
	}

	/**
	 * Get HTML for color picker field
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 */
	function color( $field, $value )
	{
		$atts = array(
			'id'    => $field['id'],
			'class' => 'color',
			'name'  => $field['id'],
			'type'  => 'text',
			'value' => $value,
		);
		return $this->html->input( $atts );
	}

	/**
	 * Get HTML for image field
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function image( $field, $value )
	{
		$field['size'] = 'large';
		return $this->text( $field, $value )
		. $this->html->button( array( 'type' => 'button', 'class' => 'button button-select' ), '...' )
		. $this->html->button( array( 'type' => 'button', 'class' => 'button button-clear' . ( $value ? '' : ' hidden' ) ), 'x' )
		. $this->html->img( "src=$value" );
	}

	/**
	 * Get HTML for switcher field
	 *
	 * @param array $field Field
	 * @param int   $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function switcher( $field, $value )
	{
		$atts = array(
			'value' => 1,
			'type'  => 'checkbox',
			'name'  => $field['id'],
		);
		$label_atts = array(
			'class' => 'switcher',
		);
		if ( 1 == $value )
		{
			$atts['checked'] = 'checked';
			$label_atts['class'] .= ' on';
		}

		return $this->html->label( $label_atts, $this->html->input( $atts ) );
	}

	/**
	 * Get HTML for toggle field
	 *
	 * @param array  $field Field options
	 * @param string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function toggle( $field, $value )
	{
		$html = '';
		foreach ( $field['options'] as $k => $v )
		{
			$atts = array(
				'value' => $k,
				'type'  => 'radio',
				'name'  => $field['id'],
			);
			if ( $k == $value )
				$atts['checked'] = 'checked';

			$html .= $this->html->label( 'class=button', $this->html->input( $atts ), $v );
		}
		return $this->html->div( 'class=button-group', $html );
	}

	/**
	 * Get HTML for toggle multiple field
	 *
	 * @param array  $field Field options
	 * @param string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function toggle_multiple( $field, $value )
	{
		$html = '';
		$value = (array) $value;
		foreach ( $field['options'] as $k => $v )
		{
			$atts = array(
				'value' => $k,
				'type'  => 'checkbox',
				'name'  => $field['id'] . '[]',
			);
			if ( in_array( $k, $value ) )
				$atts['checked'] = 'checked';

			$html .= $this->html->label( 'class=button', $this->html->input( $atts ), $v );
		}
		return $this->html->div( array( 'class' => 'button-group multiple' ), $html );
	}

	/**
	 * Get HTML for image toggle field
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function image_toggle( $field, $value )
	{
		$html = '';
		foreach ( $field['options'] as $k => $v )
		{
			$atts = array(
				'type'  => 'radio',
				'name'  => $field['id'],
				'value' => $k
			);
			if ( $k == $value )
				$atts['checked'] = 'checked';
			$html .= $this->html->label( '',
				$this->html->input( $atts ),
				$this->html->img( array( 'src' => $v ) )
			);
		}
		$layout = isset( $field['layout'] ) ? 'vertical' : 'horizontal';
		return $this->html->div( array( 'class' => $layout ), $html );
	}

	/**
	 * Get HTML for size field
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function size( $field, $value )
	{
		$name = $field['id'];
		$value = array_merge( array( 'number' => '', 'unit' => 'px' ), (array) $value );

		$input_field = array_merge( $field, array( 'id' => "{$name}[number]" ) );
		$input = $this->number( $input_field, $value['number'] );

		$select_field = array_merge( $field, array( 'id' => "{$name}[unit]", 'size' => 'mini', 'options' => array( 'px' => 'px', '%' => '%' ) ) );
		$select = $this->select( $select_field, $value['unit'] );

		return $input . $select;
	}

	/**
	 * Get HTML for custom sidebars field
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function custom_sidebars( $field, $value )
	{
		$html = '';
		$html .= $this->html->input( array( 'class' => 'input-large', 'type' => 'text' ) );
		$html .= $this->html->button( array( 'class' => 'button button-add' ), __( 'Add Sidebar', 'fitwp' ) );

		$remove = $this->html->button( array( 'class' => 'button button-remove' ), 'x' );
		$input_field = array(
			'id'   => $field['id'] . '[]',
			'size' => 'medium',
		);

		$lis = $this->html->li( 'class=hidden', $this->text( $input_field, '' ), $remove );
		$value = array_filter( (array) $value );
		if ( !empty( $value ) )
		{
			foreach ( $value as $sidebar )
			{
				$lis .= $this->html->li( '', $this->text( $input_field, $sidebar ), $remove );
			}
		}
		$html .= $this->html->ul( 'class=sidebar-list', $lis );
		return $this->html->div( 'class=input-group', $html );
	}

	/**
	 * Get HTML for font field
	 *
	 * @param   array $field Field
	 *
	 * @param         $value
	 *
	 * @return  string
	 * @since   1.0.0
	 */
	function font( $field, $value )
	{
		$value = wp_parse_args( $value, array(
			'font'        => '',
			'size'        => '',
			'line_height' => '',
			'styles'      => array(),
			'color'       => '',
		) );

		return $this->html->div( 'class=horizontal',
			$this->field_wrapper_html( array(
				'type'    => 'select',
				'id'      => $field['id'] . '[font]',
				'options' => $field['fonts'],
			), $value['font'] ),
			$this->field_wrapper_html( array(
				'type'   => 'number',
				'id'     => $field['id'] . '[size]',
				'suffix' => 'px',
			), $value['size'] ),
			$this->field_wrapper_html( array(
				'type'   => 'number',
				'id'     => $field['id'] . '[line_height]',
				'suffix' => 'px',
			), $value['line_height'] ),
			$this->field_wrapper_html( array(
				'type'    => 'toggle_multiple',
				'id'      => $field['id'] . '[styles]',
				'options' => array( 'bold' => 'B', 'italic' => 'I', 'underline' => 'U' ),
			), $value['styles'] ),
			$this->field_wrapper_html( array(
				'type' => 'color',
				'id'   => $field['id'] . '[color]',
			), $value['color'] )
		);
	}

	/**
	 * Get HTML for background field
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 */
	function background( $field, $value )
	{
		$name = $field['id'];
		$value = wp_parse_args( $value, array(
			'color'      => '',
			'image'      => '',
			'position_x' => '',
			'position_y' => '',
			'repeat'     => '',
			'attachment' => '',
		) );
		return $this->html->div( 'class=horizontal',
			$this->field_wrapper_html( array(
				'type'       => 'color',
				'id'         => "{$name}[color]",
				'label_desc' => __( 'Color', 'fitwp' )
			), $value['color'] ),
			$this->field_wrapper_html( array(
				'type'       => 'image',
				'id'         => "{$name}[image]",
				'label_desc' => __( 'Image', 'fitwp' )
			), $value['image'] )
		)
		. $this->html->div( 'class=horizontal',
			$this->field_wrapper_html( array(
				'type'       => 'select',
				'id'         => "{$name}[position_x]",
				'label_desc' => __( 'Position X', 'fitwp' ),
				'options'    => array(
					'left'   => __( 'Left', 'fitwp' ),
					'center' => __( 'Center', 'fitwp' ),
					'right'  => __( 'Right', 'fitwp' )
				)
			), $value['position_x'] ),
			$this->field_wrapper_html( array(
				'type'       => 'select',
				'id'         => "{$name}[position_y]",
				'label_desc' => __( 'Position Y', 'fitwp' ),
				'options'    => array(
					'top'    => __( 'Top', 'fitwp' ),
					'center' => __( 'Center', 'fitwp' ),
					'bottom' => __( 'Bottom', 'fitwp' )
				)
			), $value['position_y'] )
		)
		. $this->html->div( 'class=horizontal',
			$this->field_wrapper_html( array(
				'type'       => 'select',
				'id'         => "{$name}[repeat]",
				'label_desc' => __( 'Repeat', 'fitwp' ),
				'options'    => array(
					'repeat'    => __( 'Repeat', 'fitwp' ),
					'repeat-x'  => __( 'Repeat Horizontally', 'fitwp' ),
					'repeat-y'  => __( 'Repeat Vertically', 'fitwp' ),
					'no-repeat' => __( 'No Repeat', 'fitwp' ),
				)
			), $value['repeat'] ),
			$this->field_wrapper_html( array(
				'type'       => 'select',
				'id'         => "{$name}[attachment]",
				'label_desc' => __( 'Attachment', 'fitwp' ),
				'options'    => array(
					'scroll' => __( 'Scroll', 'fitwp' ),
					'fixed'  => __( 'Fixed', 'fitwp' ),
				)
			), $value['attachment'] )
		);
	}

	/**
	 * Get HTML for social field
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return  string
	 * @since   1.0.0
	 */
	function social( $field, $value )
	{
		$value = (array) $value;
		$icons = array(
			'facebook'     => __( 'Facebook URL', 'fitwp' ),
			'twitter'      => __( 'Twitter URL', 'fitwp' ),
			'flickr'       => __( 'Flickr URL', 'fitwp' ),
			'vimeo'        => __( 'Vimeo URL', 'fitwp' ),
			'google'       => __( 'Google Plus URL', 'fitwp' ),
			'linkedin'     => __( 'LinkedIn URL', 'fitwp' ),
			'pinterest'    => __( 'Pinterest URL', 'fitwp' ),
			'skype'        => __( 'Skype ID', 'fitwp' ),
			'behance'      => __( 'Behance URL', 'fitwp' ),
			'dribbble'     => __( 'Dribbble URL', 'fitwp' ),
			'soundcloud'   => __( 'SoundCloud URL', 'fitwp' ),
			'github'       => __( 'GitHub URL', 'fitwp' ),
			'instagram'    => __( 'Instagram URL', 'fitwp' ),
			'tumblr'       => __( 'Tumblr URL', 'fitwp' ),
			'reverbnation' => __( 'Reverbnation URL', 'fitwp' ),
		);

		$html = '';
		foreach ( $icons as $name => $label )
		{
			$active = 'active';
			if ( !isset( $value[$name] ) || empty( $value[$name] ) )
			{
				$value[$name] = '';
				$active = '';
			}

			$html .= $this->html->div( array( 'class' => 'social-icon' . ( $value[$name] ? ' active' : '' ) ),
				$this->html->div( array( 'class' => "icon50 icon-$name $active" ) ),
				$this->field_wrapper_html( array(
					'type'  => 'text',
					'id'    => $field['id'] . "[$name]",
					'label' => $label,
					'size'  => 'xxlarge',
				), $value[$name] )
			);
		}

		return $html;
	}

	/**
	 * Get HTML for color field
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function color_scheme( $field, $value )
	{
		$options = '';
		foreach ( $field['options'] as $color )
		{
			$atts = array(
				'type'  => 'radio',
				'name'  => $field['id'],
				'value' => $color,
			);
			$label_atts = array( 'class' => "color-scheme $color" );
			if ( $color == $value )
			{
				$atts['checked'] = 'checked';
				$label_atts['class'] .= ' active';
			}
			$options .= $this->html->label( $label_atts, $this->html->input( $atts ) );
		}
		return $this->html->div( 'class=color-schemes', $options );
	}

	/**
	 * Get HTML for box field
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function box( $field, $value )
	{
		$class = 'alert' . ( isset( $field['box_type'] ) ? " {$field['box_type']}" : '' );
		return $this->html->div( array( 'class' => $class ), $field['text'] );
	}

	/**
	 * Get HTML for backup field
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function backup( $field, $value )
	{
		$value = get_theme_mods();
		$value = base64_encode( maybe_serialize( $value ) );
		return $this->html->textarea( array( 'class' => 'input-xxlarge', 'rows'  => 5 ), $value ) .
		$this->html->button( array( 'class' => 'button import-options' ), __( 'Import Options', 'fitwp' ) );
	}

	/**
	 * Get HTML for group foe;d
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return  string
	 * @since   1.0.0
	 */
	function group( $field, $value )
	{
		$layout = isset( $field['layout'] ) ? $field['layout'] : 'horizontal';
		return $this->html->div( "class=$layout", $this->show( $field['children'] ) );
	}

	/**
	 * Generate Slider control
	 * New field created in Noise
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function slider( $field, $value )
	{
		$html = '';
		$number = isset( $field['number'] ) ? intval( $field['number'] ) : 5;

		for( $i = 0; $i < $number; $i++ )
		{
			$val = wp_parse_args( $value[$i], array(
				'caption' => '',
				'image'   => '',
			) );

			$html .= $this->field_wrapper_html( array(
				'type'       => 'text',
				'id'         => $field['id'] . "[$i][caption]",
				'label'      => __( 'Slide', 'fitwp' ) . ' ' . ($i + 1),
				'input_desc' => __( 'Caption', 'fitwp' ),
				'size'       => 'large',
			), $val['caption'] );

			$html .= $this->field_wrapper_html( array(
				'type'       => 'image',
				'id'         => $field['id'] . "[$i][image]",
				'input_desc' => __( 'Image', 'fitwp' ),
			), $val['image'] );

			$html .= '<br>';
		}

		return $this->html->div( 'class=vertical', $html );
	}

	/**
	 * Generate Testimonial Slider control
	 * New field created in Noise
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function testimonial( $field, $value )
	{
		$html = '';
		$number = isset( $field['number'] ) ? intval( $field['number'] ) : 5;

		for( $i = 0; $i < $number; $i++ )
		{
			$val = wp_parse_args( $value[$i], array(
				'author'  => '',
				'content' => '',
			) );

			$html .= $this->field_wrapper_html( array(
				'type'       => 'text',
				'id'         => $field['id'] . "[$i][author]",
				'label'      => __( 'Quote', 'fitwp' ) . ' ' . ($i + 1),
				'input_desc' => __( 'Author name', 'fitwp' ),
				'size'       => 'xxlarge',
			), $val['author'] );

			$html .= $this->field_wrapper_html( array(
				'type'       => 'textarea',
				'id'         => $field['id'] . "[$i][content]",
				'input_desc' => __( 'Quote content', 'fitwp' ),
			), $val['content'] );

			$html .= '<br>';
		}

		return $this->html->div( 'class=vertical', $html );
	}

	/**
	 * Generate Sections order control
	 * New field created in Noise
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function sections( $field, $value )
	{
		$html    = '';
		$default = array_keys( $field['section'] );
		$diff    = array_diff( $default, $value ); // Determine new sections added to default
		$value   = array_merge( $value, $diff ); // Append new sections to value

		foreach ( $value as $k )
		{
			// Check if some section removed from default setting
			if ( !in_array( $k, $default ) )
				continue;

			$atts = array(
				'value' => $k,
				'type'  => 'hidden',
				'name'  => $field['id'] . '[]',
			);

			$html .= $this->html->label( 'class=button', $this->html->input( $atts ), $field['section'][$k] );
		}

		return $this->html->div( array( 'class' => 'vertical sections-order' ), $html );
	}

	/**
	 * Generate posts select control
	 * New field created in Noise
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function post( $field, $value )
	{
		$html    = '';

		$field['args'] = isset( $field['args'] ) ? $field['args'] : array();
		$args = wp_parse_args( $field['args'], array(
			'post_type'  => 'post',
			'posts_per_page' => '-1',
		) );

		$query = get_posts( $args );
		$options = array();
		foreach( $query as $p )
		{
			$options[$p->ID] = $p->post_title;
		}

		$field['options'] = $options;
		return $this->select( $field, $value );
	}

	/**
	 * Generate editor control
	 * New field created in Noise
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function editor( $field, $value )
	{
		$settings = isset( $field['settings'] ) ? $field['settings'] : array();

		ob_start();
		wp_editor(
			$value,
			$field['id'],
			$settings
		);
		$editor = ob_get_clean();

		return $editor;
	}
}
