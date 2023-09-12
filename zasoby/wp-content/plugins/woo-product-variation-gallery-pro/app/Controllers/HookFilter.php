<?php

namespace Rtwpvgp\Controllers;

use Rtwpvg\Helpers\Functions;

class HookFilter {

	function __construct() {
		add_filter( 'rtwpvg_slider_js_options', [ &$this, 'rtwpvg_slider_js_options' ] );
		add_filter( 'rtwpvg_js_options', [ &$this, 'rtwpvg_js_options' ] );
		add_filter( 'rtwpvg_thumbnail_position', [ &$this, 'rtwpvg_thumbnail_position' ] );
		add_filter( 'rtwpvg_gallery_image_inner_html', [ &$this, 'gallery_image_inner_html' ], 10, 5 );

		add_filter( 'rtwpvg_image_html_class', [ &$this, 'image_html_class' ], 10, 3 );
		add_filter( 'rtwpvg_thumbnail_image_html_class', [ &$this, 'thumbnail_image_html_class' ], 10, 3 );
		add_filter( 'rtwpvg_gallery_has_video', [ &$this, 'gallery_has_video' ], 10, 2 );
		add_filter( 'rtwpvg_get_image_props', [ $this, 'add_video_props' ], 10, 2 );

	}

	function gallery_has_video( $output, $attachment_id ) {
		$has_video = trim( get_post_meta( $attachment_id, 'rtwpvg_video_link', true ) ); // The function will change the hooks.
		if ( $has_video ) {
			$output = $has_video;
		}
		return $output;
	}

	function rtwpvg_slider_js_options( $default ) {
		$default['arrows']         = rtwpvg()->get_option( 'slider_arrow' ) ? true : false;
		$default['adaptiveHeight'] = rtwpvg()->get_option( 'slider_adaptive_height' ) ? true : false;
		return $default;
	}

	function rtwpvg_js_options( $default ) {
		$default['enable_thumbnail_slide'] = rtwpvg()->get_option( 'thumbnail_slide' ) ? true : false;
		return $default;
	}

	function rtwpvg_thumbnail_position( $default ) {
		return rtwpvg()->get_option( 'thumbnail_position', 'bottom' );
	}

	function gallery_image_inner_html( $inner_html, $image, $template, $attachment_id, $options ) {
		$has_video = Functions::gallery_has_video( $attachment_id );
		if ( ! empty( $has_video ) ) {
			$type  = wp_check_filetype( $has_video );
			$style = 'width: 100%; height: 400px; margin: 0;padding: 0; background-color: #000';

			if ( ! empty( $type['type'] ) ) {
				$inner_html = sprintf(
					'<video preload="auto" controls="" controlslist="nodownload" src="%s" style="%s" poster="%s" ></video>',
					$has_video,
					$style,
					esc_url( $image['src'] )
				);
			} else {
				$inner_html = sprintf(
					'<iframe class="rtwpvg-lightbox-iframe" src="%s" style="%s" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
					Functions::get_simple_embed_url( $has_video ),
					$style
				);
			}
		}
		return $inner_html;
	}

	function image_html_class( $classes, $attachment_id, $image ) {
		$has_video = trim( Functions::gallery_has_video( $attachment_id ) );
		if ( $has_video ) {
			$classes[] = 'rtwpvg-gallery-video';
		}
		return $classes;
	}

	function thumbnail_image_html_class( $classes, $attachment_id, $image ) {
		$has_video = trim( Functions::gallery_has_video( $attachment_id ) );
		if ( $has_video ) {
			$classes[] = 'rtwpvg-thumbnail-video';
		}
		return $classes;
	}

	function add_video_props( $props, $attachment_id ) {
		$has_video = Functions::gallery_has_video( $attachment_id );
		if ( $has_video ) {
			$type                       = wp_check_filetype( $has_video );
			$video_width                = trim( get_post_meta( $attachment_id, 'rtwpvg_video_width', true ) );
			$video_height               = trim( get_post_meta( $attachment_id, 'rtwpvg_video_height', true ) );
			$props['rtwpvg_video_link'] = $has_video;
			// $props['rtwpvg_has_pro']    = rtwpvg()->active_pro();
			if ( ! empty( $has_video ) ) {
				if ( ! empty( $type['type'] ) ) {
					$props['rtwpvg_video_embed_type'] = 'video';
				} else {
					$props['rtwpvg_video_embed_type'] = 'iframe';
					$props['rtwpvg_video_embed_url']  = Functions::get_simple_embed_url( $has_video );
				}

				$props['rtwpvg_video_width'] = $video_width ? $video_width : 'auto';
				$props['rtwpvg_video_width'] = $video_height ? $video_height : '100%';
			}
		}

		return $props;

	}

}
