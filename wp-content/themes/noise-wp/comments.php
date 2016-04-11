<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<?php $num_comments = get_comments_number(); ?>
		<h3 class="comments-title">
		<?php printf( _n( '1 Comment', '%s Comments', $num_comments, 'noise' ), number_format_i18n( $num_comments ) ); ?>
		</h3>

		<nav role="navigation" class="pagination comments-pagination">
			<?php paginate_comments_links(); ?>
		</nav>

		<ol class="commentlist">
			<?php
			wp_list_comments(  array(
				'callback' => 'noise_comment',
				'type'     => 'comment',
			) );
			?>
		</ol>

		<?php if ( count( $comments_by_type['pings'] ) ) : ?>

			<h3 class="comments-title">
				<?php printf( _n( '1 Trackback', '%s Trackbacks', count( $comments_by_type['pings'] ), 'noise' ), number_format_i18n( count( $comments_by_type['pings'] ) ) ) ?>
			</h3>

			<ol class="commentlist trackbacks">
				<?php wp_list_comments( array(
					'callback' => 'noise_comment',
					'type'     => 'pings',
				) );
				?>
			</ol>

		<?php endif; // count( $comments_by_type['pings'] ) ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(); ?>

</div><!-- #comments -->