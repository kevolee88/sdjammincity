<?php
$num_events = intval( fitwp_option( 'events_num' ) );
$upcoming = new WP_Query( array(
	'post_type'      => 'event',
	'posts_per_page' => $num_events,
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_key'       => 'datetime',
	'meta_query'     => array(
		array(
			'key'     => 'datetime',
			'value'   => date( 'Y-m-d h:i:s' ),
			'compare' => '>='
		),
	),
) );

$past_event = new WP_Query( array(
	'post_type'      => 'event',
	'posts_per_page' => $num_events,
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
	'meta_key'       => 'datetime',
	'meta_query'     => array(
		array(
			'key'     => 'datetime',
			'value'   => date( 'Y-m-d h:i:s' ),
			'compare' => '<'
		),
	),
) );
?>
<section id="section-events" class="section-events section tabs-title">
	<div class="inner">
		<h2 class="section-title">
			<?php
			$title  = noise_kses( fitwp_option( 'event_title' ) );
			$suffix = noise_kses( fitwp_option( 'event_suffix' ) );
			$desc   = noise_kses( fitwp_option( 'event_subtitle' ) );
			printf(
				'<span>%s</span>%s%s',
				$title,
				$suffix ? '<span class="suffix">' . $suffix . '</span>' : '',
				$desc ? '<span class="desc">' . $desc . '</span>' : ''
			);
			?>
		</h2>

		<div class="tabs simple-tabs">
			<ul class="tabs-nav">
				<li><a href="#upcoming" class="active"><?php _e( 'Upcoming', 'noise' ) ?></a> /</li>
				<li><a href="#past-event"><?php _e( 'Past Events', 'noise' ) ?></a></li>
			</ul>
			<div id="upcoming" class="open tab-panel event-content">
				<?php if ( $upcoming->have_posts() ) : ?>
					<div id="upcoming-events" class="vertical-slider tiny-carousel">
						<div class="viewport">
							<ul class="slides overview">
								<?php
								while ( $upcoming->have_posts() ) : $upcoming->the_post();
									$status   = noise_get_meta( 'status' );
									$datetime = strtotime( noise_get_meta( 'datetime' ) );
									$buy      = noise_get_meta( 'buy_ticket' );
								?>
								<li>
									<div <?php post_class( 'status-' . $status ); ?>>
										<?php
										if ( $status == 'buy' )
											$label = __( 'Buy Ticket', 'noise' );
										elseif ( $status == 'sold_out' )
											$label = __( 'Sold Out', 'noise' );
										elseif ( $status == 'free' )
											$label = __( 'Free', 'noise' );
										else
											$label = __( 'Canceled', 'noise' );

										printf( '
											<div class="group-time group-event">
												<div class="day-event">%s</div>
												<div class="time-event">
													<span class="time">%s</span>
													<span class="time">%s</span>
												</div>
											</div>
											<div class="place-event group-event">%s</div>
											<div class="name-event group-event">%s</div>
											<div class="group-status group-event">
												<div class="infor-event">%s</div>
												<div class="hidden infor-content-event">%s</div>
												%s
												%s
												<div class="status">%s</div>
											</div>',
											date_i18n( 'd', $datetime ),
											date_i18n( 'F, Y', $datetime ),
											date_i18n( 'G:i A', $datetime ),
											noise_get_meta( 'place' ),
											get_the_title(),
											"&ldquo;",
											noise_get_meta( 'info' ),
											noise_get_meta( 'images' ) ? '<a class="open-gallery event-gallery entypo-pictures" href="#" data-id="' . get_the_ID() . '" ></a>' : '',
											noise_get_meta( 'videos' ) ? '<a class="open-videos event-videos entypo-video" href="#" data-id="' . get_the_ID() . '" ></a>' : '',
											'buy' == $status ? "<a href='" . esc_url( $buy ) . "' target='_blank'>$label</a>" : $label
										);
										?>
									</div>
								</li>
								<?php
								endwhile;
								wp_reset_postdata();
								?>
							</ul>
						</div>
						<div id="upcoming-events-nav" class="flex-direction-nav">
							<a class="flex-prev prev"></a>
							<a class="flex-next next"></a>
						</div>
					</div>
				<?php endif; ?>
			</div><!-- end #upcoming -->

			<div id="past-event" class="tab-panel past-event event-content">
				<?php if ( $past_event->have_posts() ) : ?>
					<div id="past-events" class="vertical-slider tiny-carousel">
						<div class="viewport">
							<ul class="slides overview">
								<?php
								while ( $past_event->have_posts() ) : $past_event->the_post();
									$datetime = strtotime( noise_get_meta( 'datetime' ) );
								?>
								<li>
									<div <?php post_class(); ?>>
										<?php
										printf( '
											<div class="day-event">%s</div>
											<div class="time-event">
												<span class="time">%s</span>
												<span class="time">%s</span>
											</div>
											<div class="place-event">%s</div>
											<div class="name-event">%s</div>
											<div class="group-status">
												<div class="infor-event">%s</div>
												<div class="hidden infor-content-event">%s</div>
												<a class="open-gallery event-gallery entypo-pictures" href="#" data-id="%s" ></a>
												<a class="open-videos event-videos entypo-video" href="#" data-id="%s" ></a>
											</div>',
											date_i18n( 'd', $datetime ),
											date_i18n( 'F, Y', $datetime ),
											date_i18n( 'G:i A', $datetime ),
											noise_get_meta( 'place' ),
											get_the_title(),
											"&ldquo;",
											noise_get_meta( 'info' ),
											get_the_ID(),
											get_the_ID()
										);
										?>
									</div>
								</li>
								<?php
								endwhile;
								wp_reset_postdata();
								?>
							</ul>
						</div>
						<div id="past-events-nav" class="flex-direction-nav">
							<a class="flex-prev prev"></a>
							<a class="flex-next next"></a>
						</div>
					</div>
				<?php endif; ?>
			</div><!-- end #past-event -->
			<div class="content-infor-event hidden">
				<i class="icon-infor entypo-info"></i>
				<div class="show-infor-event"></div>
				<i class="close-infor entypo-cross entypo-close"></i>
			</div>
		</div>
	</div>
</section>
