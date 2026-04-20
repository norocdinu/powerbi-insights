<?php
/**
 * Custom search form
 *
 * @package PowerBI_Insights
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div style="display:flex;gap:.5rem;">
		<label class="sr-only" for="search-field-<?php echo esc_attr( uniqid() ); ?>">
			<?php esc_html_e( 'Search for:', 'powerbi-insights' ); ?>
		</label>
		<input
			type="search"
			id="search-field"
			class="search-field"
			placeholder="<?php esc_attr_e( 'Search articles…', 'powerbi-insights' ); ?>"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			name="s"
			autocomplete="off"
			spellcheck="false"
		>
		<button type="submit" class="btn search-submit" aria-label="<?php esc_attr_e( 'Search', 'powerbi-insights' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
		</button>
	</div>
</form>
