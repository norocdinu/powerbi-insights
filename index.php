<?php
/**
 * Main template — Homepage & blog index
 *
 * @package PowerBI_Insights
 */

get_header();

$is_front = is_front_page() && is_home();
?>

<?php if ( $is_front ) : ?>
	<!-- ============================================================
	     HERO: FEATURED POST
	     ============================================================ -->
	<?php $featured = pbiins_get_featured_post(); ?>
	<?php if ( $featured ) : ?>
		<section class="hero-section" aria-label="<?php esc_attr_e( 'Featured article', 'powerbi-insights' ); ?>">
			<div class="container">
				<?php get_template_part( 'template-parts/content', 'featured', [ 'post' => $featured ] ); ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- ============================================================
	     TOP INSIGHTS SECTION
	     ============================================================ -->
	<?php
	$top_insights = pbiins_get_top_insights( 5 );
	if ( $top_insights->have_posts() ) :
	?>
	<section class="top-insights-section" aria-labelledby="insights-heading">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title" id="insights-heading">
					<?php esc_html_e( 'Top Insights', 'powerbi-insights' ); ?>
				</h2>
				<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="btn btn-outline btn-sm">
					<?php esc_html_e( 'View all →', 'powerbi-insights' ); ?>
				</a>
			</div>

			<div class="posts-grid posts-grid--two-col">
				<!-- Top insights numbered list -->
				<div>
					<div class="top-insights-list">
						<?php
						$i = 1;
						while ( $top_insights->have_posts() ) :
							$top_insights->the_post();
						?>
							<article class="insight-item">
								<div class="insight-number"><?php echo esc_html( str_pad( $i++, 2, '0', STR_PAD_LEFT ) ); ?></div>
								<div class="insight-content">
									<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									<?php pbiins_post_meta( [ 'show_author' => false, 'show_cats' => false ] ); ?>
								</div>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>

				<!-- Latest sidebar cards -->
				<div>
					<?php
					$recent = new WP_Query( [
						'posts_per_page' => 3,
						'post_status'    => 'publish',
						'post__not_in'   => $featured ? [ $featured->ID ] : [],
					] );
					if ( $recent->have_posts() ) :
					?>
						<div class="posts-grid" style="grid-template-columns:1fr;">
							<?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
								<?php get_template_part( 'template-parts/content' ); ?>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

<?php endif; ?>

<!-- ============================================================
     LATEST ARTICLES
     ============================================================ -->
<section class="latest-section" aria-labelledby="latest-heading">
	<div class="container">
		<div class="content-sidebar-wrap">

			<div class="posts-column">
				<div class="section-header">
					<h2 class="section-title" id="latest-heading">
						<?php
						if ( is_home() && ! is_front_page() ) {
							single_post_title();
						} elseif ( $is_front ) {
							esc_html_e( 'Latest Articles', 'powerbi-insights' );
						} else {
							the_archive_title();
						}
						?>
					</h2>
				</div>

				<?php if ( have_posts() ) : ?>
					<div class="posts-grid">
						<?php
						$skip_id = $featured ? $featured->ID : 0;
						while ( have_posts() ) :
							the_post();
							if ( $is_front && get_the_ID() === $skip_id ) {
								continue;
							}
							get_template_part( 'template-parts/content' );
						endwhile;
						?>
					</div>

					<!-- Pagination -->
					<div class="pagination-wrap">
						<?php
						echo wp_kses_post( paginate_links( [
							'prev_text' => '← ' . esc_html__( 'Previous', 'powerbi-insights' ),
							'next_text' => esc_html__( 'Next', 'powerbi-insights' ) . ' →',
							'type'      => 'list',
							'class'     => 'pagination',
						] ) );
						?>
					</div>

				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div><!-- .posts-column -->

			<!-- Sidebar -->
			<?php get_sidebar(); ?>

		</div><!-- .content-sidebar-wrap -->
	</div><!-- .container -->
</section>

<?php get_footer(); ?>
