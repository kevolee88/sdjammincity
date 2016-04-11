<li <?php comment_class(); ?>>
	<article id="comment-<?php comment_ID(); ?>">
		<?php echo get_avatar( $comment, 80 ); ?>
		<footer>
			<span class="comment-author vcard">
				<?php printf( '<cite class="url fn n">%s</cite>', get_comment_author_link() ); ?>
			</span>

			<span class="comment-meta">
				<time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
					$diff = human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) );
					$daydiff = round( (strtotime( date( 'r' ) ) - strtotime( get_comment_time( 'r' ) ) ) / (24*60*60), 0 );
					$diff = $daydiff >= 1 ? get_comment_time( get_option( 'date_format' ) ) : $diff . __( ' ago', 'noise' );
					echo $diff;
					?>
				</time>
			</span>
		</footer>

		<div class="comment-content">
			<?php if ( 0 == $comment->comment_approved ) : ?>
				<p><em><?php _e( 'Your comment is awaiting moderation.', 'noise' ); ?></em></p>
			<?php endif; ?>

			<?php comment_text(); ?>
		</div>

		<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</article>