<?php
/**
 * Static page template
 *
 * @package PowerBI_Insights
 */

get_header();

while ( have_posts() ) :
	the_post();
?>

<div class="container">
	<div class="content-sidebar-wrap">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<header class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="post-hero-image" style="aspect-ratio:21/7;margin-bottom:2rem;">
					<?php the_post_thumbnail( 'pbiins-featured', [ 'alt' => get_the_title() ] ); ?>
				</div>
			<?php endif; ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>

			<?php
			wp_link_pages( [
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'powerbi-insights' ),
				'after'  => '</div>',
			] );
			?>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>

		</article>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php endwhile; ?>
<?php get_footer(); ?>
