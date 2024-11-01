<?php

use WooCommerceVariationSwatches\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Get the plugin instance.
 *
 * @since 1.0.1
 * @return WooCommerceVariationSwatches\Plugin
 */
function wc_variation_swatches() {
	return Plugin::instance();
}


/**
 * Add extra attributes types
 *
 * @since 1.0.0
 *
 * @return array attribute_types
 */
function wc_variation_swatches_types() {

	$types = array(
		'color' => esc_html__( 'Color', 'wc-variation-swatches' ),
		'image' => esc_html__( 'Image', 'wc-variation-swatches' ),
		'label' => esc_html__( 'Label', 'wc-variation-swatches' ),
	);

	return apply_filters( 'wc_variation_swatches_types', $types );
}

/**
 * Get attribute name.
 *
 * @param string $taxonomy Taxonomy name.
 *
 * @since 1.0.0
 * @return object
 */
function wc_variation_swatches_get_tax_attribute( $taxonomy ) {
	global $wpdb;
	$attribute_name     = preg_replace( '/^pa_/i', '', $taxonomy );
	$attribute_taxonomy = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s", $attribute_name ) );

	return $attribute_taxonomy;
}

/**
 * Get WC attribute taxonomy by taxonomy name
 *
 * @param string $taxonomy_name Taxonomy Name.
 *
 * @since 1.0.0
 * @return object
 */
function wc_variation_swatches_get_attr_tax_by_name( $taxonomy_name ) {
	global $wpdb;
	$taxonomy_name      = preg_replace( '/^pa_/i', '', $taxonomy_name );
	$attribute_taxonomy = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s", $taxonomy_name ) );

	return $attribute_taxonomy;
}

/**
 * Get attribute field depending on attribute type
 *
 * @param string $type Field Type.
 * @param null   $value Field Value.
 *
 * @since 1.0.0
 * @return void
 */
function wc_variation_swatches_field( $type, $value = null ) {
	switch ( $type ) {
		case 'image':
			$image = $value ? wp_get_attachment_image_src( $value ) : '';
			$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
			?>
			<div class="wc-variation-swatches-preview" style="float:left;margin-right:10px;">
				<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/>
			</div>
			<div class="wc-variation-swatches-button-container">
				<input type="hidden" class="wc-variation-swatches-term-image" name="image" value="<?php echo esc_attr( $value ); ?>"/>
				<button type="button" class="wc-variation-swatches-upload-image button" style="margin-right: 10px;"><?php esc_html_e( 'Select image', 'wc-variation-swatches' ); ?></button>
				<button type="button" class="wc-variation-swatches-remove-image button button-link-delete <?php echo $value ? '' : 'hidden'; ?>"><?php esc_html_e( 'Remove image', 'wc-variation-swatches' ); ?></button>
			</div>
			<?php
			break;
		default:
			echo '<input type="text" id="term-' . esc_attr( $type ) . '" name="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '" />';
	}
}


/**
 * Get attribute field label depending on attribute type
 *
 * @param string $type Attribute Type.
 *
 * @since 1.0.0
 * @return void
 */
function wc_variation_swatches_field_label( $type ) {

	switch ( $type ) {
		case 'color':
			esc_html_e( 'Color', 'wc-variation-swatches' );
			break;

		case 'image':
			esc_html_e( 'Image', 'wc-variation-swatches' );
			break;

		case 'label':
			esc_html_e( 'Label', 'wc-variation-swatches' );
			break;
		default:
			esc_html_e( 'Term', 'wc-variation-swatches' );
	}
}

/**
 * Return saved setting options
 *
 * @param string $key Key name.
 * @param string $default_value Default Value.
 * @param string $section Section name.
 *
 * @since 1.0.0
 * @return string
 */
function wc_variation_swatches_get_settings( $key, $default_value = '', $section = '' ) {
	$option = get_option( $section, array() );
	return ! empty( $option[ $key ] ) ? $option[ $key ] : $default_value;
}
