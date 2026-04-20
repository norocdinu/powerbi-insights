<?php
/**
 * Featured post card — used in hero section
 *
 * @package PowerBI_Insights
 */

$post = $args['post'] ?? null;
if ( ! $post ) return;

setup_postdata( $post );
?>

<div class="featured-post-card">

	<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
		<div class="post-thumbnail">
			<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" tabindex="-1" aria-hidden="true">
				<?php
				echo get_the_post_thumbnail( $post->ID, 'pbiins-featured', [
					'alt'     => get_the_title( $post->ID ),
					'loading' => 'eager',
				] );
				?>
			</a>
		</div>
	<?php else : ?>
		<div class="post-thumbnail" style="background:linear-gradient(135deg,var(--clr-surface-2),var(--clr-accent-soft));display:flex;align-items:center;justify-content:center;font-size:5rem;">
			📊
		</div>
	<?php endif; ?>

	<div class="post-body">
		<div class="featured-badge">
			<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
			<?php esc_html_e( 'Featured', 'powerbi-insights' ); ?>
		</div>

		<?php pbiins_post_meta( [ 'show_reading' => false ] ); ?>

		<h2>
			<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
				<?php echo esc_html( get_the_title( $post->ID ) ); ?>
			</a>
		</h2>

		<p class="post-excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt( $post->ID ), 28 ) ); ?>
		</p>

		<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="btn">
			<?php esc_html_e( 'Read Article', 'powerbi-insights' ); ?>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
		</a>
	</div>

</div>

<?php wp_reset_postdata(); ?>
