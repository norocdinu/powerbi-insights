<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="<?php echo esc_attr( get_option( 'pbiins_default_theme', 'dark' ) ); ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Reading progress bar (single posts only) -->
<?php if ( is_single() ) : ?>
<div class="reading-progress-bar" id="readingProgressBar" role="progressbar" aria-hidden="true"></div>
<?php endif; ?>

<a class="skip-link sr-only" href="#main-content"><?php esc_html_e( 'Skip to content', 'powerbi-insights' ); ?></a>

<div class="site-wrapper">

	<!-- ======================================================
	     SITE HEADER
	     ====================================================== -->
	<header class="site-header" role="banner">
		<div class="container">
			<div class="header-inner">

				<!-- Branding -->
				<div class="site-branding">
					<?php pbiins_logo_markup(); ?>
				</div>

				<!-- Primary Navigation -->
				<nav class="main-navigation" id="site-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'powerbi-insights' ); ?>">
					<?php
					wp_nav_menu( [
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'pbiins_fallback_nav',
					] );
					?>
				</nav>

				<!-- Header Actions -->
				<div class="header-actions">
					<!-- Search toggle -->
					<button class="search-toggle" id="searchToggle" aria-label="<?php esc_attr_e( 'Open search', 'powerbi-insights' ); ?>" aria-expanded="false" aria-controls="searchOverlay">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
					</button>

					<!-- Dark/Light Mode Toggle -->
					<button class="theme-toggle" id="themeToggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'powerbi-insights' ); ?>">
						<!-- Moon icon (shown in light mode) -->
						<svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
						<!-- Sun icon (shown in dark mode) -->
						<svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
					</button>

					<!-- Mobile menu toggle -->
					<button class="menu-toggle" id="menuToggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'powerbi-insights' ); ?>" aria-expanded="false" aria-controls="mobileNav">
						<svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
						<svg class="icon-close" style="display:none" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
					</button>
				</div>

			</div><!-- .header-inner -->
		</div><!-- .container -->

		<!-- Mobile Navigation -->
		<nav class="mobile-nav" id="mobileNav" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'powerbi-insights' ); ?>" aria-hidden="true">
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'menu_id'        => 'mobile-menu',
				'container'      => false,
				'fallback_cb'    => 'pbiins_fallback_nav',
			] );
			?>
		</nav>

	</header><!-- .site-header -->

	<!-- Search Overlay -->
	<div class="search-overlay" id="searchOverlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'powerbi-insights' ); ?>" aria-hidden="true">
		<div class="search-overlay-inner">
			<?php get_search_form(); ?>
			<span class="search-overlay-close" id="searchClose" role="button" tabindex="0">
				<?php esc_html_e( 'Press Esc or click to close', 'powerbi-insights' ); ?>
			</span>
		</div>
	</div>

	<!-- SITE MAIN -->
	<main id="main-content" class="site-main" role="main">
