<?php
/**
 * Template Name: Under Construction
 */
get_header();

$title = noise_get_meta( 'uc_title' );
$title = empty( $title ) ? get_the_title() : $title;
$desc  = noise_get_meta( 'uc_desc' );
$end   = noise_get_meta( 'uc_endtime' );
?>

<div class="under-construction">
	<div class="parallax"></div>
	<div class="section-mark"></div>
	<div class="uc-content">
		<i class="entypo-warning"></i>
		<div class="countdown-notice">
			<h1 class="uc-title inner"><?php echo esc_html( $title ); ?></h1>
			<?php if ( !empty( $desc ) ) : ?>
				<p class="uc-desc inner"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( !empty( $end ) ) : ?>
		<div class="countdown-wrap">
			<span id="countdown" class="countdown" data-end="<?php echo date( 'F d, Y H:i:s', strtotime( $end ) ); ?>">
				<span class="count-month counter">00</span>
				<span class="count-day counter">00</span>
				<span class="count-hour counter">00</span>
				<span class="count-minute counter">00</span>
				<span class="count-second counter">00</span>
			</span>
			<span class="countdown-labels">
				<span><?php _e( 'Months', 'noise' ); ?></span>
				<span><?php _e( 'Days', 'noise' ); ?></span>
				<span><?php _e( 'Hours', 'noise' ); ?></span>
				<span><?php _e( 'Minutes', 'noise' ); ?></span>
				<span><?php _e( 'Seconds ', 'noise' ); ?></span>
			</span>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>