<?php
/**
 * Sidebar template
 *
 * @package PowerBI_Insights
 */
?>

<aside class="sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'powerbi-insights' ); ?>">

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>

		<!-- Default widgets when no widgets are configured -->

		<!-- Search Widget -->
		<div class="widget">
			<h3 class="widget-title"><?php esc_html_e( 'Search', 'powerbi-insights' ); ?></h3>
			<?php get_search_form(); ?>
		</div>

		<!-- Newsletter Signup -->
		<div class="widget newsletter-widget">
			<h3><?php esc_html_e( 'Stay Updated', 'powerbi-insights' ); ?></h3>
			<p><?php esc_html_e( 'Get the latest Power BI tips, DAX tricks, and Microsoft Fabric news straight to your inbox.', 'powerbi-insights' ); ?></p>
			<form class="newsletter-form" id="newsletterForm">
				<input
					type="email"
					name="email"
					placeholder="<?php esc_attr_e( 'your@email.com', 'powerbi-insights' ); ?>"
					required
					aria-label="<?php esc_attr_e( 'Email address', 'powerbi-insights' ); ?>"
				>
				<button type="submit" class="btn">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
					<?php esc_html_e( 'Subscribe', 'powerbi-insights' ); ?>
				</button>
				<p class="newsletter-msg" style="display:none;font-size:.8rem;color:var(--clr-success);margin:0;"></p>
			</form>
		</div>

		<!-- Categories Widget -->
		<div class="widget widget_categories">
			<h3 class="widget-title"><?php esc_html_e( 'Categories', 'powerbi-insights' ); ?></h3>
			<ul>
				<?php
				$cats = get_categories( [ 'hide_empty' => true ] );
				foreach ( $cats as $cat ) :
					?>
					<li>
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
							<?php echo esc_html( $cat->name ); ?>
							<span class="post-count"><?php echo esc_html( $cat->count ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<!-- Recent Posts Widget -->
		<div class="widget widget_recent_entries">
			<h3 class="widget-title"><?php esc_html_e( 'Recent Posts', 'powerbi-insights' ); ?></h3>
			<?php
			$recent = new WP_Query( [
				'posts_per_page' => 5,
				'post_status'    => 'publish',
				'no_found_rows'  => true,
			] );
			?>
			<ul>
				<?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
					<li>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="recent-post-thumb">
								<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
									<?php the_post_thumbnail( 'pbiins-thumb' ); ?>
								</a>
							</div>
						<?php else : ?>
							<div class="recent-post-thumb">
								<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
									<div class="recent-post-thumb-placeholder">📊</div>
								</a>
							</div>
						<?php endif; ?>
						<div class="recent-post-info">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							<div class="recent-post-date"><?php echo esc_html( get_the_date() ); ?></div>
						</div>
					</li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
		</div>

		<!-- Tags Cloud Widget -->
		<div class="widget widget_tag_cloud">
			<h3 class="widget-title"><?php esc_html_e( 'Popular Tags', 'powerbi-insights' ); ?></h3>
			<?php
			wp_tag_cloud( [
				'smallest'  => 11,
				'largest'   => 16,
				'unit'      => 'px',
				'number'    => 20,
				'format'    => 'flat',
				'separator' => '',
				'orderby'   => 'count',
				'order'     => 'DESC',
				'show_count'=> false,
				'echo'      => false,
			] );
			?>
			<div class="tagcloud post-tags">
				<?php
				$tags = get_tags( [ 'number' => 20, 'orderby' => 'count', 'order' => 'DESC' ] );
				foreach ( $tags as $tag ) :
					?>
					<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-pill">
						#<?php echo esc_html( $tag->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

	<?php endif; ?>

</aside><!-- .sidebar -->
