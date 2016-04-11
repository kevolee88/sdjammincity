<?php
/**
 * Template for comments and pingbacks.
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @param string $comment
 * @param array  $args
 * @param int    $depth
 *
 * @return void
 */
function noise_comment( $comment, $args, $depth )
{
	$GLOBALS['comment'] = $comment;

	$comment_type = get_comment_type( $comment->comment_ID );

	$templates = array( "comment-{$comment_type}.php" );

	// If the comment type is a 'pingback' or 'trackback', allow the use of 'comment-ping.php'
	if ( 'pingback' == $comment_type || 'trackback' == $comment_type )
		$templates[] = 'comment-ping.php';

	// Add the fallback 'comment.php' template
	$templates[] = 'comment.php';

	if ( $template = locate_template( $templates ) )
		require $template;
}

add_filter( 'comment_form_defaults', 'noise_comment_form_defaults' );

/**
 * Change markup for comment form
 *
 * @param array $defaults
 *
 * @return array
 */
function noise_comment_form_defaults( $defaults )
{
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );

	$defaults['title_reply'] = __( 'Post a comment', 'noise' );
	$defaults['fields'] = array(
		'author' => '<p class="comment-form-author entypo-user"><input name="author" type="text" placeholder="' . __( 'Name', 'noise' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . '></p>',
		'url'    => '<p class="comment-form-url entypo-light-bulb"><input name="url" type="text" placeholder="' . __( 'Website', 'noise' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '"></p>',
		'email'  => '<p class="comment-form-email entypo-mail"><input name="email" type="text" placeholder="' . __( 'Email', 'noise' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" ' . $aria_req . '></p>',
	);
	$defaults['comment_field'] = '<p class="comment-form-comment entypo-text"><textarea name="comment" placeholder="' . __( 'Message...', 'noise' ) . '"></textarea></p>';
	$defaults['comment_notes_before'] = '<p class="comment-notes">' . __( 'You can write your comments about this post through the form below', 'noise' ) . '</p>';
	$defaults['comment_notes_after'] = '';

	return $defaults;
}