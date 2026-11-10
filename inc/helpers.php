<?php
/**
 * Field helpers. Empty ACF values fall back to the Home copy.
 *
 * Classes use hyphens. A single id uses underscores.
 *
 * @package StreamlineConnect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The page whose fields feed the home page.
 *
 * @return int
 */
function slc_context_id() {
	$front = (int) get_option( 'page_on_front' );
	if ( $front ) {
		return $front;
	}

	$queried = (int) get_queried_object_id();
	if ( $queried ) {
		return $queried;
	}

	return 0;
}

/**
 * Raw ACF value for this home page.
 *
 * @param string $key Field name.
 * @return mixed
 */
function slc_raw( $key ) {
	if ( ! function_exists( 'get_field' ) ) {
		return null;
	}

	$post_id = function_exists( 'slc_is_theme_setting' ) && slc_is_theme_setting( $key ) ? slc_settings_post_id() : slc_context_id();
	if ( ! $post_id ) {
		return null;
	}

	return get_field( $key, $post_id );
}

/**
 * Plain text, or the theme fallback when the field is empty.
 *
 * @param string $key      Field name.
 * @param string $fallback Theme copy.
 * @return string
 */
function slc_plain( $key, $fallback = '' ) {
	$value = slc_raw( $key );
	if ( ! is_string( $value ) ) {
		return $fallback;
	}

	$text = trim( wp_strip_all_tags( $value ) );
	return '' === $text ? $fallback : $text;
}

/**
 * Heading or label. Italic, bold, links, and line breaks are kept.
 *
 * @param string $key      Field name.
 * @param string $fallback Theme copy.
 * @return string
 */
function slc_inline( $key, $fallback = '' ) {
	$value = slc_raw( $key );
	$html  = is_string( $value ) && '' !== trim( wp_strip_all_tags( $value ) ) ? $value : $fallback;
	$html  = trim( $html );
	$html  = preg_replace( '#</p>\s*<p[^>]*>#i', '<br>', $html );
	$html  = preg_replace( '#</?p[^>]*>#i', '', (string) $html );

	return wp_kses(
		$html,
		array(
			'em'     => array(),
			'strong' => array(),
			'b'      => array(),
			'i'      => array(),
			'br'     => array(),
			'a'      => array(
				'href'   => true,
				'target' => true,
				'rel'    => true,
			),
		)
	);
}

/**
 * Body copy. Plain text becomes paragraphs.
 *
 * @param string $key      Field name.
 * @param string $fallback Theme copy.
 * @return string
 */
function slc_rich( $key, $fallback = '' ) {
	$value = slc_raw( $key );
	$html  = is_string( $value ) && '' !== trim( wp_strip_all_tags( $value ) ) ? $value : $fallback;

	if ( ! preg_match( '/<(?:p|br|ul|ol|div|h[1-6]|blockquote)\b/i', $html ) ) {
		$html = wpautop( $html );
	}

	return wp_kses_post( $html );
}

/**
 * URL from a text field, or the theme destination.
 *
 * @param string $key      Field name.
 * @param string $fallback Theme URL.
 * @return string
 */
function slc_href( $key, $fallback ) {
	$value = slc_plain( $key, '' );
	if ( '' === $value ) {
		return $fallback;
	}

	if ( preg_match( '#^(https?:)?//#i', $value ) || '#' === substr( $value, 0, 1 ) || 0 === strpos( $value, 'mailto:' ) || 0 === strpos( $value, 'tel:' ) ) {
		return $value;
	}

	return slc_url( $value );
}

/**
 * @param mixed $value Raw class value.
 * @return string
 */
function slc_sanitize_classes( $value ) {
	$value  = strtolower( str_replace( '_', '-', (string) $value ) );
	$tokens = preg_split( '/\s+/', $value );
	$clean  = array();

	if ( ! is_array( $tokens ) ) {
		return '';
	}

	foreach ( $tokens as $token ) {
		$token = preg_replace( '/[^a-z0-9-]/', '', $token );
		$token = trim( (string) $token, '-' );
		if ( '' !== $token && ! preg_match( '/^[0-9]/', $token ) ) {
			$clean[] = $token;
		}
	}

	return implode( ' ', array_unique( $clean ) );
}

/**
 * @param mixed $value Raw id value.
 * @return string
 */
function slc_sanitize_id( $value ) {
	$value = strtolower( trim( str_replace( '-', '_', (string) $value ) ) );
	$value = preg_replace( '/[^a-z0-9_]/', '', (string) $value );
	$value = trim( (string) $value, '_' );

	if ( '' === $value || preg_match( '/^[0-9]/', $value ) ) {
		return '';
	}

	return $value;
}

/**
 * Saved class and id for one text or image field.
 *
 * @param string $name Content field name.
 * @return array{class: string, id: string}
 */
function slc_content_attrs( $name ) {
	$class = slc_sanitize_classes( slc_raw( $name . '_class' ) );
	$id    = slc_sanitize_id( slc_raw( $name . '_id' ) );

	return array(
		'class' => $class,
		'id'    => $id,
	);
}

/**
 * Id that will be rendered, or the theme id when the field is empty.
 *
 * @param string $name     Content field name.
 * @param string $fallback Theme id.
 * @return string
 */
function slc_content_id( $name, $fallback = '' ) {
	$attrs = slc_content_attrs( $name );
	return '' !== $attrs['id'] ? $attrs['id'] : $fallback;
}

/**
 * Class and id attributes. Extra classes are added after the theme class.
 *
 * @param string $name       Content field name.
 * @param string $base_class Theme class names.
 * @param string $base_id    Theme id.
 * @return string
 */
function slc_attr_html( $name, $base_class = '', $base_id = '' ) {
	$attrs = slc_content_attrs( $name );
	$class = trim( $base_class . ' ' . $attrs['class'] );
	$id    = '' !== $attrs['id'] ? $attrs['id'] : $base_id;
	$html  = '';

	if ( '' !== $class ) {
		$html .= ' class="' . esc_attr( $class ) . '"';
	}
	if ( '' !== $id ) {
		$html .= ' id="' . esc_attr( $id ) . '"';
	}

	return $html;
}

/**
 * Attachment id from an image field.
 *
 * @param mixed $value Field value.
 * @return int
 */
function slc_attachment_id( $value ) {
	if ( is_array( $value ) ) {
		return (int) ( $value['ID'] ?? $value['id'] ?? 0 );
	}

	return (int) $value;
}

/**
 * @param int $id Attachment id.
 * @return bool
 */
function slc_attachment_exists( $id ) {
	$id = (int) $id;
	if ( ! $id ) {
		return false;
	}

	$file = get_attached_file( $id );
	return is_string( $file ) && '' !== $file && file_exists( $file );
}

/**
 * Media field, or a theme file when the field is empty.
 *
 * @param array<string, mixed> $args Image arguments.
 * @return string
 */
function slc_picture( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'field'         => '',
			'file'          => '',
			'width'         => 0,
			'height'        => 0,
			'alt'           => '',
			'class'         => '',
			'id'            => '',
			'attr_field'    => '',
			'loading'       => 'lazy',
			'fetchpriority' => '',
			'decorative'    => false,
		)
	);

	$attachment = 0;
	if ( $args['field'] ) {
		$attachment = slc_attachment_id( slc_raw( $args['field'] ) );
	}

	if ( $args['attr_field'] ) {
		$extra         = slc_content_attrs( $args['attr_field'] );
		$args['class'] = trim( $args['class'] . ' ' . $extra['class'] );
		if ( '' !== $extra['id'] ) {
			$args['id'] = $extra['id'];
		}
	}

	if ( $attachment && slc_attachment_exists( $attachment ) ) {
		$attr = array(
			'class'    => $args['class'],
			'loading'  => $args['loading'],
			'decoding' => 'async',
		);
		if ( $args['id'] ) {
			$attr['id'] = $args['id'];
		}
		if ( $args['fetchpriority'] ) {
			$attr['fetchpriority'] = $args['fetchpriority'];
		}
		if ( $args['decorative'] ) {
			$attr['alt'] = '';
		} elseif ( '' !== $args['alt'] && '' === (string) get_post_meta( $attachment, '_wp_attachment_image_alt', true ) ) {
			$attr['alt'] = $args['alt'];
		}

		$html = wp_get_attachment_image( $attachment, 'full', false, $attr );
		if ( $html ) {
			return $html;
		}
	}

	$src = get_theme_file_uri( 'assets/' . ltrim( (string) $args['file'], '/' ) );
	$alt = $args['decorative'] ? '' : (string) $args['alt'];

	return sprintf(
		'<img src="%1$s" alt="%2$s" width="%3$d" height="%4$d" class="%5$s" loading="%6$s" decoding="async"%7$s%8$s%9$s>',
		esc_url( $src ),
		esc_attr( $alt ),
		(int) $args['width'],
		(int) $args['height'],
		esc_attr( $args['class'] ),
		esc_attr( $args['loading'] ),
		$args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : '',
		$args['fetchpriority'] ? ' fetchpriority="' . esc_attr( $args['fetchpriority'] ) . '"' : '',
		$args['decorative'] ? ' aria-hidden="true"' : ''
	);
}
