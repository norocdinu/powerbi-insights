<?php
/**
 * Archive template — categories, tags, author, date archives
 *
 * @package PowerBI_Insights
 */

get_header();
?>

<div class="container">

	<!-- Archive Header -->
	<header class="archive-header">
		<div class="section-title" style="margin-bottom:.75rem;">
			<?php
			if ( is_category() ) esc_html_e( 'Category', 'powerbi-insights' );
			elseif ( is_tag() ) esc_html_e( 'Tag', 'powerbi-insights' );
			elseif ( is_author() ) esc_html_e( 'Author', 'powerbi-insights' );
			elseif ( is_date() ) esc_html_e( 'Date Archive', 'powerbi-insights' );
			else esc_html_e( 'Archives', 'powerbi-insights' );
			?>
		</div>
		<h1 class="archive-title"><?php the_archive_title(); ?></h1>
		<?php
		$desc = get_the_archive_description();
		if ( $desc ) :
			?>
			<div class="archive-description"><?php echo wp_kses_post( $desc ); ?></div>
		<?php endif; ?>
	</header>

	<div class="content-sidebar-wrap">

		<div>
			<?php if ( have_posts() ) : ?>
				<p class="search-count" style="margin-bottom:1.5rem;color:var(--clr-text-muted);font-size:.9rem;">
					<?php
					global $wp_query;
					printf(
						/* translators: %d: post count */
						esc_html( _n( '%d article found', '%d articles found', $wp_query->found_posts, 'powerbi-insights' ) ),
						esc_html( $wp_query->found_posts )
					);
					?>
				</p>

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
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>

	</div><!-- .content-sidebar-wrap -->
</div><!-- .container -->

<?php get_footer(); ?>
