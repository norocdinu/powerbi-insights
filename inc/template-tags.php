<?php
/**
 * Template tag helpers
 *
 * @package PowerBI_Insights
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fallback navigation — displays category links when no menu is assigned.
 */
function pbiins_fallback_nav(): void {
	$cats = get_categories( [ 'number' => 6, 'hide_empty' => true ] );
	if ( ! $cats ) return;
	echo '<ul id="primary-menu">';
	foreach ( $cats as $cat ) {
		printf(
			'<li class="menu-item"><a href="%s">%s</a></li>',
			esc_url( get_category_link( $cat->term_id ) ),
			esc_html( $cat->name )
		);
	}
	echo '</ul>';
}

/**
 * Custom comment callback — cleaner markup than WordPress default.
 */
function pbiins_comment( WP_Comment $comment, array $args, int $depth ): void {
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item' ); ?>>
		<div class="comment-body">
			<div class="comment-author">
				<?php echo get_avatar( $comment, 36, '', '', [ 'class' => 'comment-avatar' ] ); ?>
				<span><?php comment_author_link(); ?></span>
			</div>
			<div class="comment-meta">
				<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
					<time datetime="<?php comment_time( 'c' ); ?>"><?php comment_date(); ?></time>
				</a>
				<?php if ( '0' === $comment->comment_approved ) : ?>
					<em><?php esc_html_e( '(Your comment is awaiting moderation.)', 'powerbi-insights' ); ?></em>
				<?php endif; ?>
			</div>
			<div class="comment-content">
				<?php comment_text(); ?>
			</div>
			<div class="comment-reply">
				<?php
				comment_reply_link( array_merge( $args, [
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'reply_text' => esc_html__( 'Reply', 'powerbi-insights' ),
					'before'     => '',
					'after'      => '',
				] ) );
				?>
			</div>
		</div>
	<?php
}

/**
 * Auto-inject table of contents into single post content.
 */
function pbiins_inject_toc( string $content ): string {
	if ( ! is_singular( 'post' ) ) {
		return $content;
	}

	// Find all h2/h3 headings
	preg_match_all( '/<h([23])([^>]*)>(.*?)<\/h[23]>/is', $content, $matches, PREG_SET_ORDER );

	if ( count( $matches ) < 3 ) {
		return $content; // Don't inject TOC for short posts
	}

	$toc_items = '';
	foreach ( $matches as $match ) {
		$level   = $match[1];
		$heading = wp_strip_all_tags( $match[3] );
		$slug    = sanitize_title( $heading );
		$class   = 'toc-h' . $level;

		$toc_items .= sprintf(
			'<li class="%s"><a href="#%s">%s</a></li>',
			esc_attr( $class ),
			esc_attr( $slug ),
			esc_html( $heading )
		);

		// Add ID to heading in content
		$attrs = $match[2];
		if ( false === strpos( $attrs, 'id=' ) ) {
			$replacement = '<h' . $level . $attrs . ' id="' . esc_attr( $slug ) . '">' . $match[3] . '</h' . $level . '>';
			$content     = str_replace( $match[0], $replacement, $content );
		}
	}

	$toc = sprintf(
		'<details class="toc-box" open>
			<summary>%s</summary>
			<ol class="toc-list">%s</ol>
		</details>',
		esc_html__( 'Table of Contents', 'powerbi-insights' ),
		$toc_items
	);

	// Insert TOC after the first paragraph
	$split = strpos( $content, '</p>' );
	if ( false !== $split ) {
		$content = substr( $content, 0, $split + 4 ) . $toc . substr( $content, $split + 4 );
	} else {
		$content = $toc . $content;
	}

	return $content;
}
add_filter( 'the_content', 'pbiins_inject_toc', 20 );

/**
 * Wrap code blocks with copy-button header automatically.
 */
function pbiins_enhance_code_blocks( string $content ): string {
	if ( ! is_singular() ) return $content;

	// Match <pre><code class="language-*">...</code></pre>
	$content = preg_replace_callback(
		'#<pre><code class="language-([a-z-]+)">(.*?)</code></pre>#is',
		function ( array $m ) {
			$lang = strtoupper( $m[1] );
			$code = $m[2];
			$raw  = html_entity_decode( wp_strip_all_tags( $code ), ENT_QUOTES );

			return sprintf(
				'<div class="code-block-wrap">
					<div class="code-block-header">
						<span class="code-lang">%s</span>
						<button class="copy-code-btn" data-copy="%s" type="button">
							<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
							Copy
						</button>
					</div>
					<pre><code class="language-%s">%s</code></pre>
				</div>',
				esc_html( $lang ),
				esc_attr( $raw ),
				esc_attr( strtolower( $m[1] ) ),
				$code
			);
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'pbiins_enhance_code_blocks', 25 );

/**
 * Add preload hint for hero image on front page.
 */
function pbiins_preload_featured_image(): void {
	if ( ! is_front_page() ) return;
	$featured = pbiins_get_featured_post();
	if ( ! $featured || ! has_post_thumbnail( $featured->ID ) ) return;

	$img_id  = get_post_thumbnail_id( $featured->ID );
	$img_src = wp_get_attachment_image_src( $img_id, 'pbiins-featured' );
	if ( $img_src ) {
		printf(
			'<link rel="preload" as="image" href="%s">%s',
			esc_url( $img_src[0] ),
			"\n"
		);
	}
}
add_action( 'wp_head', 'pbiins_preload_featured_image', 1 );
