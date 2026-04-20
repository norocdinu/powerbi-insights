<?php
/**
 * PowerBI Insights Theme Functions
 *
 * @package PowerBI_Insights
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'PBIINS_VERSION', '1.0.0' );
define( 'PBIINS_DIR', get_template_directory() );
define( 'PBIINS_URI', get_template_directory_uri() );

/* ============================================================
   THEME SETUP
   ============================================================ */

function pbiins_setup() {
	load_theme_textdomain( 'powerbi-insights', PBIINS_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [
		'search-form', 'comment-form', 'comment-list',
		'gallery', 'caption', 'style', 'script',
	] );
	add_theme_support( 'custom-logo', [
		'flex-height' => true,
		'flex-width'  => true,
		'unlink-homepage-logo' => true,
	] );
	add_theme_support( 'post-formats', [ 'aside', 'image', 'video', 'link', 'quote' ] );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-background', [
		'default-color' => 'f5f7fa',
	] );

	// Custom image sizes
	add_image_size( 'pbiins-featured', 1200, 630, true );
	add_image_size( 'pbiins-card', 600, 340, true );
	add_image_size( 'pbiins-thumb', 120, 120, true );
	add_image_size( 'pbiins-hero', 1920, 700, true );

	register_nav_menus( [
		'primary'  => __( 'Primary Navigation', 'powerbi-insights' ),
		'footer-1' => __( 'Footer Column 1', 'powerbi-insights' ),
		'footer-2' => __( 'Footer Column 2', 'powerbi-insights' ),
		'social'   => __( 'Social Links', 'powerbi-insights' ),
	] );
}
add_action( 'after_setup_theme', 'pbiins_setup' );

/* ============================================================
   SCRIPTS & STYLES
   ============================================================ */

function pbiins_enqueue_assets() {
	// Google Fonts — Inter
	wp_enqueue_style(
		'pbiins-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap',
		[],
		null
	);

	// Main stylesheet
	wp_enqueue_style(
		'pbiins-style',
		get_stylesheet_uri(),
		[ 'pbiins-fonts' ],
		PBIINS_VERSION
	);

	// Block styles
	wp_enqueue_style(
		'pbiins-blocks',
		PBIINS_URI . '/assets/css/blocks.css',
		[ 'pbiins-style' ],
		PBIINS_VERSION
	);

	// Main theme JS (defer)
	wp_enqueue_script(
		'pbiins-theme',
		PBIINS_URI . '/assets/js/theme.js',
		[],
		PBIINS_VERSION,
		true
	);
	wp_script_add_data( 'pbiins-theme', 'defer', true );

	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Pass data to JS
	wp_localize_script( 'pbiins-theme', 'pbiinsData', [
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'pbiins_nonce' ),
		'strings'  => [
			'copied'    => __( 'Copied!', 'powerbi-insights' ),
			'copyCode'  => __( 'Copy', 'powerbi-insights' ),
			'tocTitle'  => __( 'Table of Contents', 'powerbi-insights' ),
		],
	] );
}
add_action( 'wp_enqueue_scripts', 'pbiins_enqueue_assets' );

// Editor assets
function pbiins_editor_assets() {
	wp_enqueue_style(
		'pbiins-editor',
		PBIINS_URI . '/assets/css/blocks.css',
		[ 'wp-edit-blocks' ],
		PBIINS_VERSION
	);

	wp_enqueue_script(
		'pbiins-editor-blocks',
		PBIINS_URI . '/assets/js/editor-blocks.js',
		[ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ],
		PBIINS_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'pbiins_editor_assets' );

/* ============================================================
   WIDGET AREAS
   ============================================================ */

function pbiins_widgets_init() {
	$shared_args = [
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	];

	register_sidebar( array_merge( $shared_args, [
		'name'        => __( 'Main Sidebar', 'powerbi-insights' ),
		'id'          => 'sidebar-1',
		'description' => __( 'Widgets in the main sidebar area.', 'powerbi-insights' ),
	] ) );

	register_sidebar( array_merge( $shared_args, [
		'name'        => __( 'Footer Column 1', 'powerbi-insights' ),
		'id'          => 'footer-1',
		'description' => __( 'First footer widget column.', 'powerbi-insights' ),
	] ) );

	register_sidebar( array_merge( $shared_args, [
		'name'        => __( 'Footer Column 2', 'powerbi-insights' ),
		'id'          => 'footer-2',
		'description' => __( 'Second footer widget column.', 'powerbi-insights' ),
	] ) );
}
add_action( 'widgets_init', 'pbiins_widgets_init' );

/* ============================================================
   CUSTOM POST META
   ============================================================ */

/**
 * Calculate estimated reading time.
 */
function pbiins_reading_time( int $post_id = 0 ): string {
	$post_id  = $post_id ?: get_the_ID();
	$content  = get_post_field( 'post_content', $post_id );
	$words    = str_word_count( wp_strip_all_tags( $content ) );
	$minutes  = max( 1, (int) ceil( $words / 230 ) );

	return sprintf(
		/* translators: %d: number of minutes */
		_n( '%d min read', '%d min read', $minutes, 'powerbi-insights' ),
		$minutes
	);
}

/**
 * Render post meta row.
 */
function pbiins_post_meta( array $args = [] ): void {
	$defaults = [
		'show_date'     => true,
		'show_author'   => true,
		'show_cats'     => true,
		'show_reading'  => true,
	];
	$args = wp_parse_args( $args, $defaults );
	?>
	<div class="post-meta">
		<?php if ( $args['show_cats'] && has_category() ) : ?>
			<?php
			$cats = get_the_category();
			if ( $cats ) :
				$cat = $cats[0];
				?>
				<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="cat-badge">
					<?php echo esc_html( $cat->name ); ?>
				</a>
				<span class="sep">·</span>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $args['show_author'] ) : ?>
			<span>
				<?php
				printf(
					/* translators: %s: author name */
					esc_html__( 'By %s', 'powerbi-insights' ),
					'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
				);
				?>
			</span>
			<span class="sep">·</span>
		<?php endif; ?>

		<?php if ( $args['show_date'] ) : ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
		<?php endif; ?>

		<?php if ( $args['show_reading'] ) : ?>
			<span class="sep">·</span>
			<span class="reading-time">
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
				<?php echo esc_html( pbiins_reading_time() ); ?>
			</span>
		<?php endif; ?>
	</div>
	<?php
}

/* ============================================================
   EXCERPT
   ============================================================ */

function pbiins_excerpt_length(): int { return 22; }
add_filter( 'excerpt_length', 'pbiins_excerpt_length' );

function pbiins_excerpt_more( string $more ): string {
	return '…';
}
add_filter( 'excerpt_more', 'pbiins_excerpt_more' );

/* ============================================================
   BODY CLASSES
   ============================================================ */

function pbiins_body_classes( array $classes ): array {
	if ( is_singular() ) {
		$classes[] = 'single-view';
	}
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'pbiins_body_classes' );

/* ============================================================
   CUSTOM BLOCKS
   ============================================================ */

require_once PBIINS_DIR . '/inc/custom-blocks.php';

/* ============================================================
   TEMPLATE TAGS
   ============================================================ */

require_once PBIINS_DIR . '/inc/template-tags.php';

/* ============================================================
   CONTENT WIDTH
   ============================================================ */

function pbiins_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'pbiins_content_width', 780 );
}
add_action( 'after_setup_theme', 'pbiins_content_width', 0 );

/* ============================================================
   CUSTOM LOGO OUTPUT
   ============================================================ */

function pbiins_logo_markup(): void {
	if ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding" rel="home">
			<div class="logo-mark" aria-hidden="true">PBI</div>
			<div>
				<div class="site-title">
					<?php
					$blog_name = get_bloginfo( 'name' );
					$parts     = explode( ' ', $blog_name, 2 );
					echo esc_html( $parts[0] );
					if ( isset( $parts[1] ) ) {
						echo ' <span>' . esc_html( $parts[1] ) . '</span>';
					}
					?>
				</div>
				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<p class="site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
				<?php endif; ?>
			</div>
		</a>
		<?php
	}
}

/* ============================================================
   NEWSLETTER AJAX HANDLER (placeholder)
   ============================================================ */

function pbiins_newsletter_signup(): void {
	check_ajax_referer( 'pbiins_nonce', 'nonce' );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );

	if ( ! is_email( $email ) ) {
		wp_send_json_error( [ 'message' => __( 'Invalid email address.', 'powerbi-insights' ) ] );
	}

	// Hook into your preferred email service here (Mailchimp, ConvertKit, etc.)
	do_action( 'pbiins_newsletter_subscribe', $email );

	wp_send_json_success( [ 'message' => __( 'Thank you for subscribing!', 'powerbi-insights' ) ] );
}
add_action( 'wp_ajax_pbiins_newsletter', 'pbiins_newsletter_signup' );
add_action( 'wp_ajax_nopriv_pbiins_newsletter', 'pbiins_newsletter_signup' );

/* ============================================================
   DEFER SCRIPTS
   ============================================================ */

function pbiins_script_add_defer( string $tag, string $handle ): string {
	if ( wp_scripts()->get_data( $handle, 'defer' ) ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'pbiins_script_add_defer', 10, 2 );

/* ============================================================
   REMOVE EMOJI (PERFORMANCE)
   ============================================================ */

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/* ============================================================
   DISABLE XML-RPC (SECURITY)
   ============================================================ */

add_filter( 'xmlrpc_enabled', '__return_false' );

/* ============================================================
   FEATURED POSTS QUERY (for "Top Insights" section)
   ============================================================ */

function pbiins_get_top_insights( int $count = 5 ): WP_Query {
	return new WP_Query( [
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'meta_query'     => [
			[
				'key'     => '_pbiins_top_insight',
				'value'   => '1',
				'compare' => '=',
			],
		],
		'orderby'        => 'meta_value_num',
		'meta_key'       => '_pbiins_insight_order',
		'order'          => 'ASC',
	] );
}

/* ============================================================
   POST META BOX — Top Insights
   ============================================================ */

function pbiins_add_meta_boxes(): void {
	add_meta_box(
		'pbiins_post_options',
		__( 'PowerBI Insights Options', 'powerbi-insights' ),
		'pbiins_post_options_callback',
		'post',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'pbiins_add_meta_boxes' );

function pbiins_post_options_callback( WP_Post $post ): void {
	wp_nonce_field( 'pbiins_save_meta', 'pbiins_meta_nonce' );
	$is_top     = get_post_meta( $post->ID, '_pbiins_top_insight', true );
	$order      = get_post_meta( $post->ID, '_pbiins_insight_order', true );
	$is_feature = get_post_meta( $post->ID, '_pbiins_featured', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="pbiins_top_insight" value="1" <?php checked( $is_top, '1' ); ?>>
			<?php esc_html_e( 'Top Insight', 'powerbi-insights' ); ?>
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Insight Order:', 'powerbi-insights' ); ?>
			<input type="number" name="pbiins_insight_order" value="<?php echo esc_attr( $order ); ?>" min="1" max="99" style="width:60px;margin-left:.5rem;">
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="pbiins_featured" value="1" <?php checked( $is_feature, '1' ); ?>>
			<?php esc_html_e( 'Homepage Featured Post', 'powerbi-insights' ); ?>
		</label>
	</p>
	<?php
}

function pbiins_save_post_meta( int $post_id ): void {
	if ( ! isset( $_POST['pbiins_meta_nonce'] ) ||
		 ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pbiins_meta_nonce'] ) ), 'pbiins_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$top_insight = isset( $_POST['pbiins_top_insight'] ) ? '1' : '';
	update_post_meta( $post_id, '_pbiins_top_insight', $top_insight );

	$order = isset( $_POST['pbiins_insight_order'] ) ? absint( $_POST['pbiins_insight_order'] ) : 0;
	update_post_meta( $post_id, '_pbiins_insight_order', $order );

	$featured = isset( $_POST['pbiins_featured'] ) ? '1' : '';
	update_post_meta( $post_id, '_pbiins_featured', $featured );
}
add_action( 'save_post', 'pbiins_save_post_meta' );

/* ============================================================
   GET FEATURED POST
   ============================================================ */

function pbiins_get_featured_post(): ?WP_Post {
	// First: check for manually featured post
	$query = new WP_Query( [
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'meta_query'     => [
			[
				'key'   => '_pbiins_featured',
				'value' => '1',
			],
		],
	] );

	if ( $query->have_posts() ) {
		return $query->posts[0];
	}

	// Fallback: sticky post
	$stickies = get_option( 'sticky_posts' );
	if ( ! empty( $stickies ) ) {
		return get_post( $stickies[0] );
	}

	// Fallback: latest post
	$fallback = new WP_Query( [
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	] );
	return $fallback->have_posts() ? $fallback->posts[0] : null;
}

/* ============================================================
   CUSTOMIZER — LOGO SIZE
   ============================================================ */

function pbiins_customize_register( WP_Customize_Manager $wp_customize ): void {
	// Add logo height control under the existing Site Identity section
	$wp_customize->add_setting( 'pbiins_logo_max_height', [
		'default'           => 50,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage', // live preview
	] );

	$wp_customize->add_control( 'pbiins_logo_max_height', [
		'label'       => __( 'Logo Max Height (px)', 'powerbi-insights' ),
		'description' => __( 'Set the maximum display height of your logo image.', 'powerbi-insights' ),
		'section'     => 'title_tagline',
		'type'        => 'number',
		'input_attrs' => [ 'min' => 20, 'max' => 200, 'step' => 1 ],
		'priority'    => 8,
	] );
}
add_action( 'customize_register', 'pbiins_customize_register' );

/**
 * Output inline CSS for logo height (applied via CSS custom property).
 */
function pbiins_logo_height_css(): void {
	$height = absint( get_theme_mod( 'pbiins_logo_max_height', 50 ) );
	if ( $height === 50 ) return; // Skip if default — already set in stylesheet
	printf(
		'<style id="pbiins-logo-height">:root { --logo-max-height: %dpx; }</style>' . "\n",
		$height
	);
}
add_action( 'wp_head', 'pbiins_logo_height_css' );

/**
 * Live preview JS for logo height in Customizer.
 */
function pbiins_customize_preview_js(): void {
	?>
	<script>
	(function($){
		wp.customize('pbiins_logo_max_height', function(value){
			value.bind(function(newVal){
				document.documentElement.style.setProperty('--logo-max-height', newVal + 'px');
			});
		});
	})(jQuery);
	</script>
	<?php
}
add_action( 'customize_preview_init', function() {
	add_action( 'wp_footer', 'pbiins_customize_preview_js' );
} );
