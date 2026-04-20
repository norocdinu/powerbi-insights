<?php
/**
 * Custom Gutenberg blocks registration
 *
 * Registers three custom blocks:
 *  1. powerbi-insights/powerbi-tip   — Highlighted tip box with Power BI branding
 *  2. powerbi-insights/dax-snippet   — DAX code block with syntax coloring
 *  3. powerbi-insights/pbi-embed     — Responsive Power BI report embed
 *
 * @package PowerBI_Insights
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   BLOCK 1: Power BI Tip
   ============================================================ */

function pbiins_render_powerbi_tip( array $attributes, string $content ): string {
	$title   = sanitize_text_field( $attributes['tipTitle'] ?? '' );
	$type    = sanitize_key( $attributes['tipType'] ?? 'tip' );
	$allowed = [ 'tip' => '💡', 'note' => '📝', 'warning' => '⚠️', 'powerbi' => '📊' ];
	$icon    = $allowed[ $type ] ?? '💡';
	$class   = $type === 'powerbi' ? 'powerbi-tip' : $type . '-box';

	ob_start();
	?>
	<div class="highlight-box <?php echo esc_attr( $class ); ?>">
		<div class="highlight-box-icon" aria-hidden="true"><?php echo esc_html( $icon ); ?></div>
		<div class="highlight-box-content">
			<?php if ( $title ) : ?>
				<strong><?php echo esc_html( $title ); ?></strong>
			<?php endif; ?>
			<?php echo wp_kses_post( $content ); ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

register_block_type( 'powerbi-insights/powerbi-tip', [
	'title'           => __( 'Power BI Tip', 'powerbi-insights' ),
	'description'     => __( 'A highlighted tip, note, or warning box.', 'powerbi-insights' ),
	'category'        => 'powerbi-insights',
	'icon'            => 'lightbulb',
	'supports'        => [ 'html' => false, 'align' => false ],
	'attributes'      => [
		'tipTitle' => [ 'type' => 'string', 'default' => '' ],
		'tipType'  => [ 'type' => 'string', 'default' => 'tip', 'enum' => [ 'tip', 'note', 'warning', 'powerbi' ] ],
	],
	'render_callback' => 'pbiins_render_powerbi_tip',
] );

/* ============================================================
   BLOCK 2: DAX Snippet
   ============================================================ */

function pbiins_render_dax_snippet( array $attributes ): string {
	$code     = $attributes['code'] ?? '';
	$label    = sanitize_text_field( $attributes['label'] ?? 'DAX' );
	$desc     = sanitize_text_field( $attributes['description'] ?? '' );
	$safe_code = esc_html( $code );

	ob_start();
	?>
	<div class="code-block-wrap wp-block-powerbi-insights-dax-snippet">
		<?php if ( $desc ) : ?>
			<p style="font-size:.875rem;color:var(--clr-text-muted);margin-bottom:.5rem;"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
		<div class="code-block-header">
			<span class="code-lang"><?php echo esc_html( $label ); ?></span>
			<button class="copy-code-btn" data-copy="<?php echo esc_attr( $code ); ?>" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
				<?php esc_html_e( 'Copy', 'powerbi-insights' ); ?>
			</button>
		</div>
		<pre><code class="language-dax"><?php echo $safe_code; // Already escaped above ?></code></pre>
	</div>
	<?php
	return ob_get_clean();
}

register_block_type( 'powerbi-insights/dax-snippet', [
	'title'           => __( 'DAX Snippet', 'powerbi-insights' ),
	'description'     => __( 'A styled DAX code block with copy button.', 'powerbi-insights' ),
	'category'        => 'powerbi-insights',
	'icon'            => 'editor-code',
	'supports'        => [ 'html' => false, 'align' => [ 'wide' ] ],
	'attributes'      => [
		'code'        => [ 'type' => 'string', 'default' => '' ],
		'label'       => [ 'type' => 'string', 'default' => 'DAX' ],
		'description' => [ 'type' => 'string', 'default' => '' ],
	],
	'render_callback' => 'pbiins_render_dax_snippet',
] );

/* ============================================================
   BLOCK 3: Power BI Report Embed
   ============================================================ */

function pbiins_render_pbi_embed( array $attributes ): string {
	$embed_url  = esc_url( $attributes['embedUrl'] ?? '' );
	$title      = sanitize_text_field( $attributes['reportTitle'] ?? __( 'Power BI Report', 'powerbi-insights' ) );
	$show_header = (bool) ( $attributes['showHeader'] ?? true );

	if ( ! $embed_url ) {
		return '<p class="text-muted">' . esc_html__( 'No embed URL provided.', 'powerbi-insights' ) . '</p>';
	}

	// Only allow Power BI embed domains
	$parsed = wp_parse_url( $embed_url );
	$host   = $parsed['host'] ?? '';
	$allowed_hosts = [ 'app.powerbi.com', 'msit.powerbi.com', 'report.powerbi.com' ];
	if ( ! in_array( $host, $allowed_hosts, true ) ) {
		return '<p class="text-muted">' . esc_html__( 'Only Power BI embed URLs are allowed.', 'powerbi-insights' ) . '</p>';
	}

	ob_start();
	?>
	<div class="pbi-embed-wrap wp-block-powerbi-insights-pbi-embed">
		<?php if ( $show_header ) : ?>
			<div class="pbi-embed-header">
				<div class="pbi-embed-logo" aria-hidden="true">PBI</div>
				<span class="pbi-embed-title"><?php echo esc_html( $title ); ?></span>
				<a href="<?php echo esc_url( $embed_url ); ?>" target="_blank" rel="noopener noreferrer"
				   style="margin-left:auto;font-size:.75rem;color:var(--clr-text-muted);">
					<?php esc_html_e( 'Open in Power BI ↗', 'powerbi-insights' ); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="pbi-embed-container">
			<iframe
				title="<?php echo esc_attr( $title ); ?>"
				src="<?php echo esc_url( $embed_url ); ?>"
				frameborder="0"
				allowfullscreen="true"
				loading="lazy"
			></iframe>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

register_block_type( 'powerbi-insights/pbi-embed', [
	'title'           => __( 'Power BI Embed', 'powerbi-insights' ),
	'description'     => __( 'Embed a responsive Power BI report.', 'powerbi-insights' ),
	'category'        => 'powerbi-insights',
	'icon'            => 'chart-bar',
	'supports'        => [ 'html' => false, 'align' => [ 'wide', 'full' ] ],
	'attributes'      => [
		'embedUrl'    => [ 'type' => 'string', 'default' => '' ],
		'reportTitle' => [ 'type' => 'string', 'default' => '' ],
		'showHeader'  => [ 'type' => 'boolean', 'default' => true ],
	],
	'render_callback' => 'pbiins_render_pbi_embed',
] );

/* ============================================================
   CUSTOM BLOCK CATEGORY
   ============================================================ */

function pbiins_block_category( array $categories ): array {
	return array_merge(
		[
			[
				'slug'  => 'powerbi-insights',
				'title' => __( 'Power BI Insights', 'powerbi-insights' ),
				'icon'  => 'chart-bar',
			],
		],
		$categories
	);
}
add_filter( 'block_categories_all', 'pbiins_block_category' );

/* ============================================================
   CLASSIC EDITOR SHORTCODES (fallback / page builder support)
   ============================================================ */

/**
 * [pbi_tip type="tip|note|warning|powerbi" title="Title"]Content[/pbi_tip]
 */
function pbiins_shortcode_tip( array $atts, string $content = '' ): string {
	$atts = shortcode_atts( [
		'type'  => 'tip',
		'title' => '',
	], $atts, 'pbi_tip' );

	return pbiins_render_powerbi_tip( [
		'tipTitle' => $atts['title'],
		'tipType'  => $atts['type'],
	], do_shortcode( $content ) );
}
add_shortcode( 'pbi_tip', 'pbiins_shortcode_tip' );

/**
 * [dax label="DAX" description=""]MEASURE = ...[/dax]
 */
function pbiins_shortcode_dax( array $atts, string $content = '' ): string {
	$atts = shortcode_atts( [
		'label'       => 'DAX',
		'description' => '',
	], $atts, 'dax' );

	return pbiins_render_dax_snippet( [
		'code'        => trim( $content ),
		'label'       => $atts['label'],
		'description' => $atts['description'],
	] );
}
add_shortcode( 'dax', 'pbiins_shortcode_dax' );

/**
 * [pbi_embed url="https://app.powerbi.com/..." title="Report" /]
 */
function pbiins_shortcode_embed( array $atts ): string {
	$atts = shortcode_atts( [
		'url'   => '',
		'title' => 'Power BI Report',
	], $atts, 'pbi_embed' );

	return pbiins_render_pbi_embed( [
		'embedUrl'    => $atts['url'],
		'reportTitle' => $atts['title'],
		'showHeader'  => true,
	] );
}
add_shortcode( 'pbi_embed', 'pbiins_shortcode_embed' );
