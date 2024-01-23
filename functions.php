<?php
/**
 * Default function file for Child Theme.
 *
 * @package parkesgolf
 */

/**
 * Setup Child Theme Styles.
 */
function parkesgolf_enqueue_styles() {
	wp_enqueue_style( 'parkesgolf-style', get_stylesheet_directory_uri() . '/style.css', false, '1.0' );
}
add_action( 'wp_enqueue_scripts', 'parkesgolf_enqueue_styles', 20 );


/**
 * Setup Child Theme Palettes
 *
 * @param string $palettes registered palette json.
 * @return string
 */
function parkesgolf_change_palette_defaults( $palettes ) {
	$palettes = '{"palette":[{"color":"#2B6CB0","slug":"palette1","name":"Palette Color 1"},{"color":"#215387","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#2B6CB0","slug":"palette1","name":"Palette Color 1"},{"color":"#215387","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#2B6CB0","slug":"palette1","name":"Palette Color 1"},{"color":"#215387","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}';
	return $palettes;
}
add_filter( 'kadence_global_palette_defaults', 'parkesgolf_change_palette_defaults', 20 );

/**
 * Setup Child Theme Defaults
 *
 * @param array $defaults registered option defaults with kadence theme.
 * @return array
 */
function parkesgolf_change_option_defaults( $defaults ) {
	$new_defaults = '[]';
	$new_defaults = json_decode( $new_defaults, true );
	return wp_parse_args( $new_defaults, $defaults );
}
add_filter( 'kadence_theme_options_defaults', 'parkesgolf_change_option_defaults', 20 );

/**
 * Function to run my shortcode for the Course Navigation.
 */
function pg_course_nav_shortcode() {

	// Things that you want to do.
	$query = new WP_Query(
		array(
			'post_type'      => 'hole',
			'post_status'    => 'publish',
			'posts_per_page' => '20',
			'order'          => 'ASC',
		)
	);

	$navigation = '<div class="pg-course-nav">';

	while ( $query->have_posts() ) {
		$query->the_post();

		$hole = array();
		$hole = (object) $hole;

		$hole->id       = get_the_ID();
		$hole->title    = get_the_title( $hole->id );
		$hole->number   = str_replace( 'hole ', '', strtolower( $hole->title ) );
		$hole->par      = get_field( 'par', $hole->id );
		$hole->distance = get_field( 'blue_tees', $hole->id );
		$hole->url      = esc_url( get_permalink( $hole->id ) );

		$format = '<a class="hole"href="%s">
					<div class="number">%s</div>
					<div class="par">Par %s</div>
					<div class="metres">%s m</div>
				</a>';

		$navigation .= sprintf( $format, $hole->url, $hole->number, $hole->par, $hole->distance );
	}

	$navigation .= '</div>';

	wp_reset_postdata();

	// Output needs to be return.
	return $navigation;
}
// Register shortcode.
add_shortcode( 'pg_course_nav', 'pg_course_nav_shortcode' );


/**
 * We use WordPress's init hook to make sure
 * our blocks are registered early in the loading
 * process. Also register the scripts for responsive web images and map hilight.
 *
 * @link https://developer.wordpress.org/reference/hooks/init/
 */
function pg_child_register_acf_blocks() {

	// phpcs:ignore
	wp_register_script( 'my-rwd', get_stylesheet_directory_uri() . '/js/jquery.rwdImageMaps.js', array( 'jquery' ), true );

	// phpcs:ignore
	wp_register_script( 'my-hilight', get_stylesheet_directory_uri() . '/js/jquery.maphilight.js', array( 'jquery' ), true );

	/**
	 * We register our block's with WordPress's handy
	 * register_block_type();
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	register_block_type( __DIR__ . '/blocks/image-map' );
}
// Here we call our pg_child_register_acf_block() function on init.
add_action( 'init', 'pg_child_register_acf_blocks' );
