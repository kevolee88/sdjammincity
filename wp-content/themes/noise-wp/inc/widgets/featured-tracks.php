<?php

/**
 * Featured Tracks Widget class
 */
class Noise_Widget_Featured_Track extends WP_Widget
{
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Class constructor
	 * Set up the widget
	 *
	 * @return Noise_Widget_Featured_Track
	 */
	function __construct()
	{
		$this->defaults = array(
			'title'         => __( 'Featured Tracks', 'noise' ),
			'limit'         => 5,
			'show_featured' => false,
			'show_order'    => false,
			'show_thumb'    => true,
			'show_artist'   => true,
			'show_rating'   => true,
			'orderby'       => 'rating',
		);

		parent::__construct(
			'noise-featured-tracks',
			__( 'Noise - Featured Tracks', 'noise' ),
			array(
				'classname'   => 'widget-featured-tracks',
				'description' => __( 'Display most featured tracks', 'noise' ),
			),
			array( 'width' => 300, 'height' => 350 )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments
	 * @param array $instance Saved values from database
	 *
	 * @return void
	 */
	function widget( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->defaults );
		extract( $instance );
		extract( $args );

		echo $before_widget;

		if ( $title = apply_filters( 'title', $title, $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;

		$query = array(
			'post_type'      => 'track',
			'posts_per_page' => $limit,
		);

		if ( 'rating' == $orderby )
		{
			$query['orderby'] = 'meta_value_num';
			$query['meta_key'] = 'votes_week';
		}

		if ( $show_featured )
		{
			$query['meta_query'] = array(
				array(
					'key' => '_featured_track',
					'value' => '1'
				)
			);
		}

		$tracks = new WP_Query( $query );

		while ( $tracks->have_posts() ): $tracks->the_post();
			$file = noise_get_meta( 'upload', 'type=file_input' );
			$title_limit = 55;
		?>

			<article <?php post_class(); ?>>
				<?php
				if( $show_order )
				{
					printf( '<div class="track-no">%s</div>', $tracks->current_post + 1 );
					$title_limit -= 10;
				}

				if ( $show_thumb )
				{
					printf(
						'<div class="track-image">%s<a href="#" class="play-track entypo-play" data-url="%s"></a></div>',
						noise_get_image( array( 'size' => 'widget-thumb', 'echo' => false ) ),
						$file
					);
					$title_limit -= 17;
				}

				if ( $show_rating )
				{
					printf(
					'<div class="week-vote track-vote">
						<i class="icon entypo-heart"></i>
						<span>%s</span>
					</div>',
					intval( noise_get_meta( 'votes_week' ) )
					);
					$title_limit -= 8;
				}
				?>
				<div class="track-title">
					<a href="#" class="play-track" title="<?php the_title(); ?>" data-url="<?php echo $file; ?>"><?php echo noise_title_limit( $title_limit ); ?></a>
					<?php
					if ( $show_artist )
					{
						echo '<span class="artist-name">' . get_the_title( noise_get_meta( 'artist' ) ) . '</span>';
					}
					?>
				</div>
				<?php echo do_shortcode( '[audio src="' . $file . '"]' ); ?>
			</article>

		<?php
		endwhile;
		wp_reset_postdata();

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array Updated safe values to be saved
	 */
	function update( $new_instance, $old_instance )
	{
		$new_instance['title']         = strip_tags( $new_instance['title'] );
		$new_instance['limit']         = intval( $new_instance['limit'] );
		$new_instance['show_featured'] = isset( $new_instance['show_featured'] );
		$new_instance['show_thumb']    = isset( $new_instance['show_thumb'] );
		$new_instance['show_artist']   = isset( $new_instance['show_artist'] );
		$new_instance['show_rating']   = isset( $new_instance['show_rating'] );
		$new_instance['show_order']    = isset( $new_instance['show_order'] );

		return $new_instance;
	}

	/**
	 * Displays the widget options
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	function form( $instance )
	{
		// Merge with defaults
		$instance = wp_parse_args( $instance, $this->defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'noise' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" size="2" value="<?php echo $instance['limit']; ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Number Of Tracks', 'noise' ); ?></label>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_featured' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_featured' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_featured'] ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_featured' ) ); ?>"><?php _e( 'Show Featured Tracks Only', 'noise' ); ?></label>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_order' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_order'] ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_order' ) ); ?>"><?php _e( 'Show Order Number', 'noise' ); ?></label>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_thumb'] ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>"><?php _e( 'Show Thumbnail', 'noise' ); ?></label>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_artist' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_artist' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_artist'] ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_artist' ) ); ?>"><?php _e( 'Show Track Artist', 'noise' ); ?></label>
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['show_rating'] ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php _e( 'Show Rating', 'noise' ); ?></label>
		</p>

		<p>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<option value="rating" <?php selected( 'rating', $instance['orderby'] ); ?>><?php _e( 'Rating', 'noise' ); ?></option>
				<option value="date" <?php selected( 'date', $instance['orderby'] ); ?>><?php _e( 'Date', 'noise' ); ?></option>
			</select>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e( 'Order By', 'noise' ); ?></label>
		</p>
	<?php
	}
}

// Register widget
add_action( 'widgets_init', 'noise_register_widget_featured_track' );

/**
 * Register widget
 *
 * @return void
 */
function noise_register_widget_featured_track()
{
	register_widget( 'Noise_Widget_Featured_Track' );
}