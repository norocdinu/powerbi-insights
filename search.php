<?php
/**
 * Search results template
 *
 * @package PowerBI_Insights
 */

get_header();
?>

<div class="container">

	<header class="search-header">
		<div class="section-title" style="margin-bottom:.75rem;"><?php esc_html_e( 'Search Results', 'powerbi-insights' ); ?></div>
		<h1 class="archive-title">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Results for: %s', 'powerbi-insights' ),
				'<span style="color:var(--clr-accent)">' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
		<?php if ( have_posts() ) : ?>
			<p class="search-count">
				<?php
				global $wp_query;
				printf(
					/* translators: %d: number of results */
					esc_html( _n( '%d result found', '%d results found', $wp_query->found_posts, 'powerbi-insights' ) ),
					esc_html( $wp_query->found_posts )
				);
				?>
			</p>
		<?php endif; ?>

		<!-- Inline search form -->
		<div style="max-width:500px;margin-top:1.25rem;">
			<?php get_search_form(); ?>
		</div>
	</header>

	<div class="content-sidebar-wrap">
		<div>
			<?php if ( have_posts() ) : ?>
				<div class="posts-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content' ); ?>
					<?php endwhile; ?>
				</div>

				<div class="pagination-wrap">
					<?php
					echo wp_kses_post( paginate_links( [
						'prev_text' => '← ' . esc_html__( 'Previous', 'powerbi-insights' ),
						'next_text' => esc_html__( 'Next', 'powerbi-insights' ) . ' →',
						'type'      => 'list',
					] ) );
					?>
				</div>

			<?php else : ?>
				<div style="text-align:center;padding:4rem 2rem;">
					<div style="font-size:3rem;margin-bottom:1rem;">🔍</div>
					<h2><?php esc_html_e( 'No results found', 'powerbi-insights' ); ?></h2>
					<p style="color:var(--clr-text-muted);max-width:400px;margin:.75rem auto 2rem;">
						<?php esc_html_e( 'Try different keywords, check your spelling, or browse by category.', 'powerbi-insights' ); ?>
					</p>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
						<?php esc_html_e( '← Back to Home', 'powerbi-insights' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
