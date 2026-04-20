<?php
/**
 * No posts found template part
 *
 * @package PowerBI_Insights
 */
?>

<div style="text-align:center;padding:4rem 2rem;background:var(--clr-surface);border:1px solid var(--clr-border);border-radius:var(--radius-lg);">
	<div style="font-size:3rem;margin-bottom:1rem;">📭</div>
	<h2 style="margin-bottom:.75rem;"><?php esc_html_e( 'Nothing here yet', 'powerbi-insights' ); ?></h2>
	<p style="color:var(--clr-text-muted);max-width:400px;margin:0 auto 2rem;">
		<?php
		if ( is_search() ) {
			esc_html_e( 'No articles matched your search. Try different keywords or browse by category.', 'powerbi-insights' );
		} else {
			esc_html_e( 'It looks like nothing was found at this location.', 'powerbi-insights' );
		}
		?>
	</p>
	<?php get_search_form(); ?>
</div>
