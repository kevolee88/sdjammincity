<?php
class Noise_Widget_Artist extends WP_Widget
{
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor
	 *
	 * @return Noise_Widget_Artist
	 */
	function __construct()
	{
		$this->defaults = array(
			'title'   => '',
			'display' => 'recent',
			'limit'   => 5,
		);

		parent::__construct(
			'noise-artists',
			__( 'Noise - Artists', 'noise' ),
			array(
				'description' => __( 'Display Artists List', 'noise' ),
			)
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->defaults );

		extract( $args );
		echo $before_widget;

		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

		$query = array(
			'post_type'      => 'artist',
			'posts_per_page' => intval( $instance['limit'] ),
		);

		if ( 'featured' == $instance['display'] )
		{
			$query['meta_key']   = '_featured_artist';
			$query['meta_value'] = '1';
		}

		$artists = new WP_Query( $query );
		if ( $artists->have_posts() )
		{
			while( $artists->have_posts() ) : $artists->the_post();
			?>

			<div <?php post_class() ?>>
				<?php noise_get_image( 'size=widget-thumb' ) ?>
				<span class="artist-name"><?php the_title() ?></span>
				<span class="occupation"><?php echo noise_get_meta( 'occupation' ) ?></span>
			</div>

			<?php
			endwhile;
		}
		wp_reset_postdata();

		echo $after_widget;
	}

	/**
	 * Deals with the settings when they are saved by the admin.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance )
	{
		$new_instance['title'] = strip_tags( $new_instance['title'] );
		return $new_instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance
	 *
	 * @return array
	 */
	function form( $instance )
	{
		$instance = wp_parse_args( $instance, $this->defaults );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'noise' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title'] ?>" class="widefat"/>
		</p>
		<p>

		<p>
			<span><?php _e( 'Choose which artists will be displayed', 'noise' ) ?></span><br>
			<label>
				<input type="radio" name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>" value="recent" <?php checked( 'recent', $instance['display'] ) ?>>
				<?php _e( 'Most recent artists', 'noise' ) ?>
			</label><br>
			<label>
				<input type="radio" name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>" value="featured" <?php checked( 'featured', $instance['display'] ) ?>>
				<?php _e( 'Featured artists', 'noise' ) ?>
			</label>
		</p>

		</p>
		<p>
			<input  id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo $instance['limit'] ?>" size="3"/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Artists to show', 'noise' ); ?></label>
		</p>
		<?php
	}
}

add_action( 'widgets_init', 'noise_register_widget_artist' );

/**
 * Register widget tweets
 *
 * @return void
 * @since 1.0
 */
function noise_register_widget_artist()
{
	register_widget( 'Noise_Widget_Artist' );
}
