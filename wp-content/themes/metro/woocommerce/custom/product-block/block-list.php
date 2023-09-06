<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro;

$id  = $product->get_id();
$cat = $block_data['cat_display'] ? WC_Functions::get_top_category_name() : false;
?>

<div class="product-box">
	<div class="media">
		<div class="item-img">
			<?php echo WC_Functions::get_product_thumbnail( $product, $block_data['thumb_size'] );?>
			<a href="<?php the_permalink();?>" class="item-plus-icon" title="<?php esc_attr( the_title() ); ?>"><span class="glyph-icon flaticon-plus-symbol"></span></a>
			<?php woocommerce_show_product_loop_sale_flash();?>
		</div>
		<div class="media-body">
			<div class="item-title">
				<h4><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
			</div>
			<h5 class="item-subtitle"><?php echo esc_html( $cat );?></h5>
			<div class="item-price">
			<?php if ( $price_html = $product->get_price_html() ) : ?>
				<div class="rtin-price price"><?php echo wp_kses_post( $price_html ); ?></div>
			<?php endif; ?>
			</div>
		</div>
	</div>
</div>