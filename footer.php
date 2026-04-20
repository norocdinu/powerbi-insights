	</main><!-- #main-content -->

	<!-- ======================================================
	     SITE FOOTER
	     ====================================================== -->
	<footer class="site-footer" role="contentinfo">
		<div class="container">
			<div class="footer-top">

				<!-- Brand column -->
				<div class="footer-brand">
					<div class="site-title">
						<?php
						$blog_name = get_bloginfo( 'name' );
						$parts     = explode( ' ', $blog_name, 2 );
						echo esc_html( $parts[0] );
						if ( isset( $parts[1] ) ) {
							echo ' <span style="color:var(--clr-accent)">' . esc_html( $parts[1] ) . '</span>';
						}
						?>
					</div>
					<p><?php bloginfo( 'description' ); ?></p>

					<!-- Social links from Social Menu -->
					<?php if ( has_nav_menu( 'social' ) ) : ?>
						<nav class="social-links" aria-label="<?php esc_attr_e( 'Social Links', 'powerbi-insights' ); ?>">
							<?php
							wp_nav_menu( [
								'theme_location'  => 'social',
								'container'       => false,
								'menu_class'      => '',
								'link_before'     => '<span class="sr-only">',
								'link_after'      => '</span>',
								'item_spacing'    => 'discard',
								'fallback_cb'     => false,
								'items_wrap'      => '%3$s',
							] );
							?>
						</nav>
					<?php else : ?>
						<!-- Default social links placeholder -->
						<div class="social-links">
							<a href="#" class="social-link" aria-label="Twitter/X">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.259 5.63 5.905-5.63Zm-1.161 17.52h1.833L7.084 4.126H5.117Z"/></svg>
							</a>
							<a href="#" class="social-link" aria-label="LinkedIn">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
							</a>
							<a href="#" class="social-link" aria-label="GitHub">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
							</a>
							<a href="#" class="social-link" aria-label="RSS Feed">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<!-- Footer menu column 1 -->
				<div>
					<div class="footer-col-title"><?php esc_html_e( 'Topics', 'powerbi-insights' ); ?></div>
					<?php if ( has_nav_menu( 'footer-1' ) ) : ?>
						<?php
						wp_nav_menu( [
							'theme_location' => 'footer-1',
							'container'      => false,
							'menu_class'     => 'footer-links',
							'depth'          => 1,
							'fallback_cb'    => false,
						] );
						?>
					<?php else : ?>
						<ul class="footer-links">
							<?php
							$cats = get_categories( [ 'number' => 6, 'hide_empty' => true ] );
							foreach ( $cats as $cat ) :
								?>
								<li><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<!-- Footer menu column 2 -->
				<div>
					<div class="footer-col-title"><?php esc_html_e( 'Pages', 'powerbi-insights' ); ?></div>
					<?php if ( has_nav_menu( 'footer-2' ) ) : ?>
						<?php
						wp_nav_menu( [
							'theme_location' => 'footer-2',
							'container'      => false,
							'menu_class'     => 'footer-links',
							'depth'          => 1,
							'fallback_cb'    => false,
						] );
						?>
					<?php else : ?>
						<ul class="footer-links">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'powerbi-insights' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About', 'powerbi-insights' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'powerbi-insights' ); ?></a></li>
							<li><a href="<?php echo esc_url( get_feed_link() ); ?>"><?php esc_html_e( 'RSS Feed', 'powerbi-insights' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>

				<!-- Footer widget area -->
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<div>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
				<?php endif; ?>

			</div><!-- .footer-top -->

			<!-- Footer bottom bar -->
			<div class="footer-bottom">
				<div>
					<?php
					printf(
						/* translators: 1: year, 2: site name */
						esc_html__( '© %1$s %2$s. All rights reserved.', 'powerbi-insights' ),
						esc_html( date_i18n( 'Y' ) ),
						'<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>'
					);
					?>
				</div>
				<div>
					<?php
					printf(
						/* translators: link to WordPress */
						esc_html__( 'Built with %s', 'powerbi-insights' ),
						'<a href="https://wordpress.org" rel="nofollow noopener">WordPress</a>'
					);
					?>
					<?php if ( has_nav_menu( 'footer-2' ) ) : ?>
						&nbsp;·&nbsp;
						<?php wp_nav_menu( [
							'theme_location' => 'footer-2',
							'container'      => false,
							'menu_class'     => '',
							'depth'          => 1,
							'fallback_cb'    => false,
						] ); ?>
					<?php endif; ?>
				</div>
			</div><!-- .footer-bottom -->

		</div><!-- .container -->
	</footer><!-- .site-footer -->

</div><!-- .site-wrapper -->

<!-- Back to top button -->
<button class="back-to-top" id="backToTop" aria-label="<?php esc_attr_e( 'Back to top', 'powerbi-insights' ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
