<?php
/**
 * Single post template
 *
 * @package PowerBI_Insights
 */

get_header();

while ( have_posts() ) :
	the_post();
?>

<div class="container">
	<div class="content-sidebar-wrap">

		<!-- POST CONTENT -->
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>

			<!-- Post Header -->
			<header class="post-header">
				<?php pbiins_post_meta(); ?>
				<h1 class="post-title"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="post-excerpt"><?php the_excerpt(); ?></p>
				<?php endif; ?>
			</header>

			<!-- Featured Image -->
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="post-hero-image">
					<?php the_post_thumbnail( 'pbiins-featured', [
						'alt'     => get_the_title(),
						'loading' => 'eager',
					] ); ?>
				</div>
			<?php endif; ?>

			<!-- Post Content -->
			<div class="entry-content" id="postContent">
				<?php the_content(); ?>
			</div>

			<!-- Post Footer: Tags -->
			<?php if ( has_tag() ) : ?>
				<footer class="post-footer" style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--clr-border);">
					<div class="post-tags">
						<span style="font-size:.8rem;font-weight:600;color:var(--clr-text-muted);margin-right:.5rem;"><?php esc_html_e( 'Tags:', 'powerbi-insights' ); ?></span>
						<?php
						$tags = get_the_tags();
						if ( $tags ) :
							foreach ( $tags as $tag ) :
								?>
								<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-pill">
									#<?php echo esc_html( $tag->name ); ?>
								</a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</footer>
			<?php endif; ?>

			<!-- Author Box -->
			<?php
			$author_id  = get_the_author_meta( 'ID' );
			$author_bio = get_the_author_meta( 'description' );
			if ( $author_bio ) :
			?>
				<aside class="author-box" aria-label="<?php esc_attr_e( 'About the author', 'powerbi-insights' ); ?>">
					<div class="author-avatar">
						<?php echo get_avatar( $author_id, 72, '', get_the_author(), [ 'class' => '' ] ); ?>
					</div>
					<div class="author-info">
						<div class="author-name">
							<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
								<?php the_author(); ?>
							</a>
						</div>
						<p class="author-bio"><?php echo esc_html( $author_bio ); ?></p>
					</div>
				</aside>
			<?php endif; ?>

			<!-- Post Navigation -->
			<nav class="post-navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'powerbi-insights' ); ?>">
				<?php
				$prev = get_previous_post();
				$next = get_next_post();
				?>
				<div class="nav-previous">
					<?php if ( $prev ) : ?>
						<span class="nav-label">← <?php esc_html_e( 'Previous', 'powerbi-insights' ); ?></span>
						<a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="nav-title">
							<?php echo esc_html( get_the_title( $prev->ID ) ); ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="nav-next">
					<?php if ( $next ) : ?>
						<span class="nav-label"><?php esc_html_e( 'Next', 'powerbi-insights' ); ?> →</span>
						<a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="nav-title">
							<?php echo esc_html( get_the_title( $next->ID ) ); ?>
						</a>
					<?php endif; ?>
				</div>
			</nav>

			<!-- Related Posts -->
			<?php
			$cats    = get_the_category();
			$cat_ids = wp_list_pluck( $cats, 'term_id' );
			if ( $cat_ids ) :
				$related = new WP_Query( [
					'posts_per_page'      => 3,
					'post_status'         => 'publish',
					'post__not_in'        => [ get_the_ID() ],
					'category__in'        => $cat_ids,
					'orderby'             => 'rand',
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
				] );
				if ( $related->have_posts() ) :
			?>
				<aside class="related-posts" aria-labelledby="related-heading" style="margin-top:3rem;">
					<div class="section-header">
						<h3 class="section-title" id="related-heading"><?php esc_html_e( 'Related Articles', 'powerbi-insights' ); ?></h3>
					</div>
					<div class="posts-grid">
						<?php while ( $related->have_posts() ) : $related->the_post(); ?>
							<?php get_template_part( 'template-parts/content' ); ?>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</aside>
			<?php endif; endif; ?>

			<!-- Comments -->
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>

		</article><!-- .single-article -->

		<!-- Sidebar -->
		<?php get_sidebar(); ?>

	</div><!-- .content-sidebar-wrap -->
</div><!-- .container -->

<?php
endwhile;
get_footer();
?>
