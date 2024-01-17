<?php
/**
 * Image Map Block template.
 *
 * @package parkesgolf
 *
 * @param array $block The block settings and attributes.
 */

$image = get_field( 'image' );

$image_map_name = 'image-map-' . $image['ID'];

$image_map_name_target = '#' . $image_map_name;

echo wp_get_attachment_image(
	$image['ID'],
	'full',
	'',
	array(
		'class'  => 'pg_image_map',
		'usemap' => esc_attr( $image_map_name_target ),
	)
);

echo '<map name=' . esc_attr( $image_map_name ) . '>';

$format = '<area alt="%s" title="%s" href="%s" coords="%s" shape="polygon">';

// Check for areas for this image map.
if ( have_rows( 'areas' ) ) :

	// loop through the rows of data.
	while ( have_rows( 'areas' ) ) :

		the_row();

		// display a sub field value.
		$name = get_sub_field( 'name' );
		$urli = get_sub_field( 'link' );
		$cord = get_sub_field( 'co_ordinates' );

		// phpcs:ignore
		printf( $format, $name, $name, $urli, $cord );

	endwhile;

endif;
echo '</map>';
?>
<script>
jQuery(document).ready(function(e) {
	jQuery('img[usemap]').rwdImageMaps();
	jQuery('img[usemap]').maphilight();
});
</script>
