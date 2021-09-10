<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$orderby                 = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'default';
$catalog_orderby_options = array(
    'default'       => __( 'Default sorting', 'tutor-plugin' ),
    'price'         => __( 'Sort by price: low to high', 'tutor-plugin' ),
    'price-desc'    => __( 'Sort by price: high to low', 'tutor-plugin' )
);

?>
<form class="tutor-plugin-ordering" method="get">
    <select name="orderby" class="orderby">
        <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
            <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
    // Keep query string vars intact
    foreach ( $_GET as $key => $val ) {
        if ( 'orderby' === $key || 'submit' === $key ) {
            continue;
        }
        if ( is_array( $val ) ) {
            foreach( $val as $innerVal ) {
                echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
            }
        } else {
            echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
        }
    }
    ?>
</form>
