<?php
/**
 * Comments template
 *
 * @package PowerBI_Insights
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			printf(
				/* translators: %d: comment count */
				esc_html( _n( '%d Comment', '%d Comments', $comments_number, 'powerbi-insights' ) ),
				esc_html( number_format_i18n( $comments_number ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( [
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 48,
				'callback'    => 'pbiins_comment',
			] );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="comment-navigation" aria-label="<?php esc_attr_e( 'Comment navigation', 'powerbi-insights' ); ?>">
				<?php paginate_comments_links(); ?>
			</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'powerbi-insights' ); ?></p>
	<?php endif; ?>

	<?php comment_form( [
		'title_reply'          => esc_html__( 'Leave a Comment', 'powerbi-insights' ),
		'title_reply_to'       => esc_html__( 'Reply to %s', 'powerbi-insights' ),
		'cancel_reply_link'    => esc_html__( 'Cancel reply', 'powerbi-insights' ),
		'label_submit'         => esc_html__( 'Post Comment', 'powerbi-insights' ),
		'class_submit'         => 'btn',
		'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s">',
	] ); ?>

</section><!-- #comments -->
