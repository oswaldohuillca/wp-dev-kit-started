<?php
/**
 * Block template file: template/global/espaciado.php
 *
 * Espaciado Global Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'espaciado-global-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-espaciado-global';
if ( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $classes .= ' align' . $block['align'];
}



$height_desktop = get_field("altura_desktop");
$height_mobile = get_field("altura_mobile");

$is_admin = is_admin();

?>

<style type="text/css">
	<?php echo '#' . $id; ?> {
		height: <?= ca($height_mobile);?>;
	}

    @media (min-width:768px) {
        <?php echo '#' . $id; ?> {
            height: <?= ca($height_desktop);?>;
	    }
    }
</style>

<?php if($is_admin):?>
    <div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="min-height: 100px; text-align: center;display: flex;justify-content: center;align-items: center;background-color: #f8f9fa">
        <div>Espaciado</div>
    </div>
<?php else: ?>
    <div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>" ></div>
<?php endif;?>
