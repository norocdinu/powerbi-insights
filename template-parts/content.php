<?php
/**
 * Post card — used in archive/homepage grids
 *
 * @package PowerBI_Insights
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>

	<!-- Thumbnail -->
	<div class="post-card-thumbnail">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
				<?php the_post_thumbnail( 'pbiins-card', [ 'alt' => '' ] ); ?>
			</a>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
				<div class="post-card-thumbnail-placeholder">
					<?php
					$cats = get_the_category();
					$icon = '📊';
					if ( $cats ) {
						$slug = $cats[0]->slug;
						if ( false !== strpos( $slug, 'dax' ) ) $icon = '⚡';
						elseif ( false !== strpos( $slug, 'fabric' ) ) $icon = '🔷';
						elseif ( false !== strpos( $slug, 'visual' ) ) $icon = '📈';
						elseif ( false !== strpos( $slug, 'tip' ) ) $icon = '💡';
					}
					echo esc_html( $icon );
					?>
				</div>
			</a>
		<?php endif; ?>
	</div>

	<div class="post-card-body">
		<!-- Meta -->
		<?php pbiins_post_meta(); ?>

		<!-- Title -->
		<h3>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<!-- Excerpt -->
		<?php if ( has_excerpt() || get_the_content() ) : ?>
			<p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
		<?php endif; ?>

		<!-- Tags -->
		<?php if ( has_tag() ) : ?>
			<div class="post-tags">
				<?php
				$tags = get_the_tags();
				$max  = 3;
				$i    = 0;
				if ( $tags ) :
					foreach ( $tags as $tag ) :
						if ( $i >= $max ) break;
						?>
						<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-pill">
							#<?php echo esc_html( $tag->name ); ?>
						</a>
						<?php
						$i++;
					endforeach;
				endif;
				?>
			</div>
		<?php endif; ?>
	</div>

</article>
