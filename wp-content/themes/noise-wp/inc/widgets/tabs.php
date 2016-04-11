<?php
/**
 * Tabs Widget Class
 */
class Noise_Widget_Tabs extends WP_Widget
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
	 * @return Noise_Widget_Tabs
	 */
	function __construct()
	{
		$this->defaults = array(
			'title'               => '',
			'popular_show'        => true,
			'popular_title'       => __( 'Popular', 'noise' ),
			'popular_limit'       => 5,
			'popular_thumb'       => true,
			'popular_comments'    => false,
			'popular_author'      => true,
			'popular_date'        => true,
			'popular_date_format' => 'M/d/Y',
			'recent_show'         => true,
			'recent_title'        => __( 'Recent', 'noise' ),
			'recent_limit'        => 5,
			'recent_thumb'        => true,
			'recent_comments'     => false,
			'recent_author'       => true,
			'recent_date'         => true,
			'recent_date_format'  => 'M/d/Y',
			'comments_show'       => true,
			'comments_title'      => __( 'Comments', 'noise' ),
			'comments_limit'      => 5,
		);

		parent::__construct(
			'noise-tabs',
			__( 'Noise - Tabs', 'noise' ),
				array(
				'classname'   => 'widget-tabs noise-recent-posts',
				'description' => __( 'Display most popular posts, recent posts, recent comments in tabbed widget.', 'noise' ),
			),
			array( 'width' => 780, 'height' => 350 )
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
		$active = false;

		if ( $title = apply_filters( 'widget_title', $title, $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;

		echo '<div class="noise-tabs tabs">';
			echo '<ul class="tabs-nav">';
			if ( $popular_show )
			{
				$active = true;
				echo "<li><a href='#popular-tab' class='active'>$popular_title</a></li>";
			}

			if ( $recent_show )
			{
				$class = $active ? '' : 'active';
				$active = true;
				echo "<li><a href='#recent-tab' class='$class'>$recent_title</a></li>";
			}

			if ( $comments_show )
			{
				$class = $active ? '' : 'active';
				$active = false;
				echo "<li><a href='#comment-tab' class='$class'>$comments_title</a></li>";
			}
			echo '</ul>';
			?>
			<?php if ( $popular_show ) : ?>
				<div id="popular-tab" class="open tab-panel popular-tab">
					<?php
					$active = true;
					$popular_posts = new WP_Query( array(
						'posts_per_page' => $popular_limit,
						'orderby'   => 'comment_count',
						'order'     => 'DESC'
					) );
					while ( $popular_posts->have_posts() ): $popular_posts->the_post(); ?>
						<article class="popular-post">
							<?php
							if ( $popular_thumb )
							{
								printf(
									'<a class="thumb" href="%s" title="%s">%s</a>',
									get_permalink(),
									the_title_attribute( 'echo=0' ),
									noise_get_image( 'size=widget-thumb&echo=0' ),
									the_title_attribute( 'echo=0' )
								);
							}
							?>
							<div class="text">
								<a class="title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'noise' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
								<?php
								if ( $popular_date )
									echo '<time class="date">' . esc_html( get_the_time( $popular_date_format ) ) . '</time>';

								if ( $popular_author )
								{
									echo ' <span class="entry-author">';
									_e( 'by ', 'noise' );
									the_author_posts_link();
									echo '</span>';
								}

								if ( $popular_comments )
									echo '<span class="comments">' . sprintf( __( '%s Comments', 'noise' ), get_comments_number() ) . '</span>';
								?>
							</div>
						</article>
					<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>

			<?php if ( $recent_show ) : ?>
				<div id="recent-tab" class="<?php echo $active ? '' : 'open' ?> tab-panel recent-tab">
					<?php
					$active = true;
					the_widget(
						'Noise_Widget_Recent_Posts',
						array(
							'limit'         => $recent_limit,
							'thumb'         => $recent_thumb,
							'date'          => $recent_date,
							'comments'      => $recent_comments,
							'date_format'   => $recent_date_format,
						),
						array(
							'before_widget' => '',
							'after_widget'  => '',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<?php
			if ( $comments_show ) : ?>
				<div id="comment-tab" class="<?php echo $active ? '' : 'open' ?> tab-panel comment-tab">
					<?php
					$comments = get_comments( array(
						'status' => 'approve',
						'number' => $comments_limit,
					) );

					foreach ( $comments as $comment )
					{
						echo sprintf(
							'<div class="comment">
								<p class="comment-summary">%s <span class="author-comment">%s %s</span></p>
								<span class="post-comment">%s <a href="%s" title="%s">%s</a></span>
							</div>',
							wp_trim_words( strip_tags( $comment->comment_content ), 10 ),
							__( 'by', 'noise' ),
							$comment->comment_author,
							__( 'on', 'noise' ),
							get_comments_link( $comment->comment_post_ID ),
							get_the_title( $comment->comment_post_ID ),
							wp_trim_words( strip_tags( get_the_title( $comment->comment_post_ID ) ), 7 )
						);
					}
					?>
				</div>
			<?php
			endif;
		echo '</div>';
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
		$new_instance['title']               = strip_tags( $new_instance['title'] );

		$new_instance['popular_show']        = isset( $new_instance['popular_show'] );
		$new_instance['popular_thumb']       = isset( $new_instance['popular_thumb'] );
		$new_instance['popular_comments']    = isset( $new_instance['popular_comments'] );
		$new_instance['popular_author']      = isset( $new_instance['popular_author'] );
		$new_instance['popular_date']        = isset( $new_instance['popular_date'] );
		$new_instance['popular_title']       = strip_tags( $new_instance['popular_title'] );
		$new_instance['popular_limit']       = intval( $new_instance['popular_limit'] );
		$new_instance['popular_date_format'] = strip_tags( $new_instance['popular_date_format'] );

		$new_instance['recent_show']         = isset( $new_instance['recent_show'] );
		$new_instance['recent_thumb']        = isset( $new_instance['recent_thumb'] );
		$new_instance['recent_comments']     = isset( $new_instance['recent_comments'] );
		$new_instance['recent_author']       = isset( $new_instance['recent_author'] );
		$new_instance['recent_date']         = isset( $new_instance['recent_date'] );
		$new_instance['recent_title']        = strip_tags( $new_instance['recent_title'] );
		$new_instance['recent_limit']        = intval( $new_instance['recent_limit'] );
		$new_instance['recent_date_format']  = strip_tags( $new_instance['recent_date_format'] );

		$new_instance['comments_show']       = isset( $new_instance['comments_show'] );
		$new_instance['comments_title']      = strip_tags( $new_instance['comments_title'] );
		$new_instance['comments_limit']      = intval( $new_instance['comments_limit'] );

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
		<div style="width: 250px; float: left; margin-right: 10px;">
			<p><strong><?php _e( 'Popular Posts', 'noise' ); ?></strong></p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'popular_show' ); ?>" name="<?php echo $this->get_field_name( 'popular_show' ); ?>" value="1" <?php checked( 1, $instance['popular_show'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'popular_show' ); ?>"><?php _e( 'Show Popular Tab', 'noise' ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'popular_title' ); ?>"><?php _e( 'Label', 'noise' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'popular_title' ); ?>" name="<?php echo $this->get_field_name( 'popular_title' ); ?>" value="<?php echo esc_attr( $instance['popular_title'] ); ?>" />
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_limit' ) ); ?>" type="text" size="2" value="<?php echo $instance['popular_limit']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_limit' ) ); ?>"><?php _e( 'Number Of Posts', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_comments' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_comments'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_comments' ) ); ?>"><?php _e( 'Show Comment Number', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_thumb'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_thumb' ) ); ?>"><?php _e( 'Show Thumbnail', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_author' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_author'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_author' ) ); ?>"><?php _e( 'Show Post Author', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_date' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_date'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_date' ) ); ?>"><?php _e( 'Show Date', 'noise' ); ?></label>
			</p>
			<p>
				<input size="6" id="<?php echo esc_attr( $this->get_field_id( 'popular_date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_date_format' ) ); ?>" type="text" value="<?php echo $instance['popular_date_format']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_date_format' ) ); ?>"><?php _e( 'Date Format', 'noise' ); ?></label>
				<a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">[?]</a>
			</p>
		</div>
		<div style="width: 250px; float: left; margin-right: 10px;">
			<p><strong><?php _e( 'Recent Posts', 'noise' ); ?></strong></p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'recent_show' ); ?>" name="<?php echo $this->get_field_name( 'recent_show' ); ?>" value="1" <?php checked( 1, $instance['recent_show'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'recent_show' ); ?>"><?php _e( 'Show Recent Posts Tab', 'noise' ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'recent_title' ); ?>"><?php _e( 'Label', 'noise' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'recent_title' ); ?>" name="<?php echo $this->get_field_name( 'recent_title' ); ?>" value="<?php echo esc_attr( $instance['recent_title'] ); ?>" />
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_limit' ) ); ?>" type="text" size="2" value="<?php echo $instance['recent_limit']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_limit' ) ); ?>"><?php _e( 'Number Of Posts', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_comments' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_comments'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_comments' ) ); ?>"><?php _e( 'Show Comment Number', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_thumb'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_thumb' ) ); ?>"><?php _e( 'Show Thumbnail', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_author' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_author'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_author' ) ); ?>"><?php _e( 'Show Post Author', 'noise' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_date' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_date'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_date' ) ); ?>"><?php _e( 'Show Date', 'noise' ); ?></label>
			</p>
			<p>
				<input size="6" id="<?php echo esc_attr( $this->get_field_id( 'recent_date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_date_format' ) ); ?>" type="text" value="<?php echo $instance['recent_date_format']; ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_date_format' ) ); ?>"><?php _e( 'Date Format', 'noise' ); ?></label>
				<a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">[?]</a>
			</p>
		</div>
		<div style="width: 250px;float:left;">
			<p><strong><?php _e( 'Recent Comments', 'noise' ); ?></strong></p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'comments_show' ); ?>" name="<?php echo $this->get_field_name( 'comments_show' ); ?>" value="1" <?php checked( 1, $instance['comments_show'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'comments_show' ); ?>"><?php _e( 'Show Recent Posts Tab', 'noise' ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'comments_title' ); ?>"><?php _e( 'Label', 'noise' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'comments_title' ); ?>" name="<?php echo $this->get_field_name( 'comments_title' ); ?>" value="<?php echo esc_attr( $instance['comments_title'] ); ?>" />
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'comments_limit' ); ?>" name="<?php echo $this->get_field_name( 'comments_limit' ); ?>" type="text" value="<?php echo $instance['comments_limit']; ?>" size="3">
				<label for="<?php echo $this->get_field_id( 'comments_limit' ); ?>"><?php _e( 'Number of comments to show', 'noise' ); ?></label>
			</p>
		</div>
		<div class="clear"></div>
	<?php
	}
}

// Register widget
add_action( 'widgets_init', 'noise_register_widget_tabs' );

/**
 * Register widget
 *
 * @return void
 */
function noise_register_widget_tabs()
{
	register_widget( 'Noise_Widget_Tabs' );
}
