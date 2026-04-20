<?php
/**
 * 404 error page template
 *
 * @package PowerBI_Insights
 */

get_header();
?>

<div class="container">
	<div class="error-404-wrap">
		<div class="error-404-code" aria-hidden="true">404</div>
		<h1><?php esc_html_e( 'Page Not Found', 'powerbi-insights' ); ?></h1>
		<p><?php esc_html_e( "Looks like this dashboard report doesn't exist — or it's been moved to a different workspace.", 'powerbi-insights' ); ?></p>

		<div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
				<?php esc_html_e( '← Back to Home', 'powerbi-insights' ); ?>
			</a>
			<button class="btn btn-outline" onclick="history.back()">
				<?php esc_html_e( 'Go Back', 'powerbi-insights' ); ?>
			</button>
		</div>

		<!-- Search form -->
		<div style="margin-top:3rem;width:100%;max-width:420px;">
			<p style="font-size:.9rem;color:var(--clr-text-muted);margin-bottom:.75rem;">
				<?php esc_html_e( 'Try searching for what you need:', 'powerbi-insights' ); ?>
			</p>
			<?php get_search_form(); ?>
		</div>

		<!-- Popular categories -->
		<div style="margin-top:3rem;text-align:left;width:100%;max-width:700px;">
			<h2 style="font-size:1rem;color:var(--clr-text-muted);margin-bottom:1rem;"><?php esc_html_e( 'Browse Categories', 'powerbi-insights' ); ?></h2>
			<div class="post-tags">
				<?php
				$cats = get_categories( [ 'hide_empty' => true ] );
				foreach ( $cats as $cat ) :
					?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="tag-pill" style="font-size:.85rem;padding:.3rem .8rem;">
						<?php echo esc_html( $cat->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
