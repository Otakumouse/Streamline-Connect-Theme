<?php
/**
 * Streamline Connect theme setup.
 *
 * @package StreamlineConnect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/acf-fields.php';

/**
 * Theme supports and assets.
 */
function slc_setup() {
	load_theme_textdomain( 'streamlineconnect', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'slc_setup' );

add_filter( 'use_block_editor_for_post', '__return_false', 100 );
add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
add_filter( 'use_widgets_block_editor', '__return_false' );
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

/**
 * This theme does not render blocks, so the block library stays off the front.
 */
function slc_disable_block_assets() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'slc_disable_block_assets', 100 );

/**
 * Styles and the menu script.
 */
function slc_assets() {
	$css = get_theme_file_path( 'assets/css/theme.css' );
	$js  = get_theme_file_path( 'assets/js/theme.js' );

	wp_enqueue_style(
		'slc-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'slc-theme',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array( 'slc-fonts' ),
		file_exists( $css ) ? (string) filemtime( $css ) : '1.0.0'
	);

	wp_enqueue_script(
		'slc-theme',
		get_theme_file_uri( 'assets/js/theme.js' ),
		array(),
		file_exists( $js ) ? (string) filemtime( $js ) : '1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'slc_assets' );

/**
 * Create the pages Home links to, once an editor is present.
 */
function slc_ensure_page( $title, $slug, $content, $parent_id = 0 ) {
	$path = $slug;

	if ( $parent_id ) {
		$parent = get_post( $parent_id );
		if ( $parent instanceof WP_Post ) {
			$path = $parent->post_name . '/' . $slug;
		}
	}

	$existing = get_page_by_path( $path );
	if ( $existing instanceof WP_Post ) {
		wp_update_post(
			array(
				'ID'           => $existing->ID,
				'post_title'   => $title,
				'post_parent'  => $parent_id,
				'post_content' => $content,
				'post_status'  => 'publish',
			)
		);
		return (int) $existing->ID;
	}

	$created = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_parent'  => $parent_id,
			'post_content' => $content,
		),
		true
	);

	if ( is_wp_error( $created ) ) {
		return 0;
	}

	return (int) $created;
}

/**
 * Publish the approved sitemap and remove every other page.
 */
function slc_section_html( $sections ) {
	$html = '';
	foreach ( $sections as $id => $label ) {
		$html .= '<h2 id="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</h2>';
	}
	return $html;
}

/**
 * Publish the approved sitemap and remove every other page.
 */
function slc_create_pages() {
	if ( '3' === get_option( 'slc_content_version' ) ) {
		return;
	}

	$can_publish = current_user_can( 'publish_pages' ) || ( defined( 'WP_CLI' ) && WP_CLI );
	if ( ! $can_publish ) {
		return;
	}

	$home = (int) get_option( 'page_on_front' );
	if ( ! $home ) {
		$existing = get_page_by_path( 'home' );
		$home     = $existing instanceof WP_Post ? (int) $existing->ID : slc_ensure_page( 'Home', 'home', '' );
	}
	if ( $home ) {
		wp_update_post(
			array(
				'ID'         => $home,
				'post_title' => 'Home',
				'post_name'  => 'home',
			)
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home );
	}

	$keep = array( $home );

	$capabilities = slc_ensure_page( 'Capabilities', 'capabilities', '<p>' . esc_html__( 'Streamline Connect delivers communications networks, power systems and modular facilities using its own crews, specialist plant and manufacturing capability.', 'streamlineconnect' ) . '</p>' );
	$keep[]       = $capabilities;
	$keep[]       = slc_ensure_page( 'Communications Infrastructure', 'communications-infrastructure', slc_section_html( array( 'fibre_networks' => 'Fibre Networks', 'wireless_infrastructure' => 'Wireless Infrastructure', 'critical_communications' => 'Critical Communications', 'communications_facilities' => 'Communications Facilities' ) ), $capabilities );
	$keep[]       = slc_ensure_page( 'Modular Infrastructure', 'modular-infrastructure', '<p>' . esc_html__( 'Data Rooms for communications and IT equipment, and Facilities for project crews, designed and manufactured by Streamline.', 'streamlineconnect' ) . '</p>' . slc_section_html( array( 'design_manufacturing_and_delivery' => 'Design, manufacturing and delivery capability', 'data_rooms' => 'Data Rooms', 'facilities' => 'Facilities' ) ), $capabilities );
	$keep[]       = slc_ensure_page( 'Power and Energy', 'power-and-energy', slc_section_html( array( 'renewable_and_hybrid_energy' => 'Renewable & Hybrid Energy', 'remote_power_systems' => 'Remote Power Systems', 'power_monitoring_and_control' => 'Power Monitoring and Control' ) ), $capabilities );

	$products = slc_ensure_page( 'Products', 'products', '<p>' . esc_html__( 'Data Rooms for communications and IT equipment, and Facilities for project crews, designed and manufactured by Streamline.', 'streamlineconnect' ) . '</p>' );
	$keep[]   = $products;
	$keep[]   = slc_ensure_page( 'Data Rooms', 'data-rooms', slc_section_html( array( 'communication_equipment_rooms' => 'Communication Equipment Rooms', 'modular_data_centres' => 'Modular Data Centres' ) ), $products );
	$keep[]   = slc_ensure_page( 'Facilities', 'facilities', slc_section_html( array( 'zer' => 'ZER' ) ), $products );

	$about = slc_ensure_page( 'About', 'about', '<p>' . esc_html__( 'Our fibre and underground construction roots go back to Streamline Underground Services in 1996.', 'streamlineconnect' ) . '</p>' );
	$keep[] = $about;
	$keep[] = slc_ensure_page( 'Our Story', 'our-story', slc_section_html( array( 'about' => 'About', 'our_story' => 'Our Story' ) ), $about );
	$keep[] = slc_ensure_page( 'Leadership', 'leadership', slc_section_html( array( 'management' => 'Management', 'team_capabilities' => 'Team Capabilities' ) ), $about );
	$keep[] = slc_ensure_page( 'How we deliver', 'how-we-deliver', '<p>' . esc_html__( 'From a defined construction package to a broader infrastructure project, Streamline involves the people responsible for delivery in planning the work. Our own crews undertake the core stages, supported by trusted contractors under our management where required. On projects involving both site construction and manufacturing, these activities proceed in parallel.', 'streamlineconnect' ) . '</p>' . slc_section_html( array( 'the_delivery_model' => 'The Delivery Model', 'facilities' => 'Facilities' ) ), $about );

	$contact = slc_ensure_page( 'Contact', 'contact', '<p>' . esc_html__( 'Tell us what you\'re planning, where the site is and the programme you\'re working on.', 'streamlineconnect' ) . '</p><p><a href="mailto:hello@streamlineconnect.com.au">hello@streamlineconnect.com.au</a></p><p>' . esc_html__( 'Perth, Western Australia', 'streamlineconnect' ) . '</p>' );
	$keep[]  = $contact;
	$keep[]  = slc_ensure_page( 'Locations', 'locations', '<p>' . esc_html__( 'Perth, Western Australia', 'streamlineconnect' ) . '</p>', $contact );

	$projects = slc_ensure_page( 'Projects', 'projects', '<p>' . esc_html__( 'Clients keep our teams on between scopes and talk to us early about their next project.', 'streamlineconnect' ) . '</p>' );
	$keep[]   = $projects;
	$keep[]   = slc_ensure_page( 'Industries', 'industries', '', $projects );

	$keep[] = slc_ensure_page( 'Privacy Policy', 'privacy-policy', '<p>' . esc_html__( 'Privacy information for Streamline Connect Pty Ltd will be published here.', 'streamlineconnect' ) . '</p>' );

	$keep = array_filter( array_map( 'intval', $keep ) );
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);
	foreach ( $pages as $page_id ) {
		if ( ! in_array( (int) $page_id, $keep, true ) ) {
			wp_delete_post( (int) $page_id, true );
		}
	}

	update_option( 'slc_content_version', '3' );
	flush_rewrite_rules( false );
}
add_action( 'init', 'slc_create_pages', 20 );
