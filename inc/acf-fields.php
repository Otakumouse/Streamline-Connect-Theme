<?php
/**
 * Home field group. Each text and image has its own class and id fields.
 *
 * @package StreamlineConnect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'slc_register_fields' );
add_filter( 'acf/fields/wysiwyg/toolbars', 'slc_wysiwyg_toolbars' );

/**
 * @param array<string, array<int, string[]>> $toolbars Toolbars.
 * @return array<string, array<int, string[]>>
 */
function slc_wysiwyg_toolbars( $toolbars ) {
	$toolbars['Inline'] = array(
		1 => array( 'italic', 'bold', 'link', 'unlink', 'removeformat' ),
	);

	return $toolbars;
}

/**
 * Import the field group when the definition changes.
 */
function slc_register_fields() {
	if ( ! function_exists( 'acf_import_field_group' ) || ! function_exists( 'acf_get_field_group' ) ) {
		return;
	}

	$version  = '2';
	$existing = acf_get_field_group( 'group_slc_front_page' );
	$settings = acf_get_field_group( 'group_slc_theme_settings' );
	if ( get_option( 'slc_field_group_version' ) === $version && ! empty( $existing['ID'] ) && ! empty( $settings['ID'] ) ) {
		return;
	}

	$group = slc_front_page_field_group();
	if ( ! empty( $existing['ID'] ) ) {
		$group['ID'] = (int) $existing['ID'];
	}
	acf_import_field_group( $group );

	$theme = slc_theme_settings_field_group();
	if ( ! empty( $settings['ID'] ) ) {
		$theme['ID'] = (int) $settings['ID'];
	}
	acf_import_field_group( $theme );

	update_option( 'slc_field_group_version', $version, false );
}

/**
 * @return array<string, mixed>
 */
function slc_collect_fields( $scope ) {
	$fields = array();
	foreach ( slc_field_catalog() as $item ) {
		if ( $scope !== $item['scope'] ) {
			continue;
		}
		if ( 'tab' === $item['type'] ) {
			$fields[] = array(
				'key'   => 'field_slc_tab_' . sanitize_key( $item['label'] ),
				'label' => $item['label'],
				'type'  => 'tab',
			);
			continue;
		}

		$key = 'field_slc_' . $item['name'];
		if ( 'image' === $item['type'] ) {
			$fields[] = array(
				'key'           => $key,
				'label'         => $item['label'],
				'name'          => $item['name'],
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'library'       => 'all',
				'mime_types'    => 'jpg, jpeg, png, svg, webp',
			);
		} elseif ( 'wysiwyg' === $item['type'] ) {
			$fields[] = array(
				'key'           => $key,
				'label'         => $item['label'],
				'name'          => $item['name'],
				'type'          => 'wysiwyg',
				'default_value' => $item['default'],
				'tabs'          => 'all',
				'toolbar'       => $item['toolbar'],
				'media_upload'  => 0,
				'instructions'  => 'inline' === $item['toolbar'] ? 'Use italic for emphasis. The Text tab accepts HTML.' : 'Format the copy here, or use the Text tab for HTML.',
			);
		} else {
			$fields[] = array(
				'key'           => $key,
				'label'         => $item['label'],
				'name'          => $item['name'],
				'type'          => 'text',
				'default_value' => $item['default'],
			);
		}

		if ( empty( $item['plain'] ) ) {
			$fields = array_merge( $fields, slc_class_id_fields( $key, $item['name'], $item['label'] ) );
		}
	}

	return $fields;
}

/**
 * Home page fields. Header and footer live in Theme settings.
 *
 * @return array<string, mixed>
 */
function slc_front_page_field_group() {
	return array(
		'key'                   => 'group_slc_front_page',
		'title'                 => 'Home',
		'fields'                => slc_collect_fields( 'home' ),
		'location'              => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
		'hide_on_screen'        => array( 'the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'slug', 'author', 'format', 'featured_image', 'categories', 'tags', 'send-trackbacks' ),
		'position'              => 'acf_after_title',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	);
}

/**
 * Header, menu, enquiry band, and footer. Shared by every page.
 *
 * @return array<string, mixed>
 */
function slc_theme_settings_field_group() {
	return array(
		'key'                   => 'group_slc_theme_settings',
		'title'                 => 'Header and footer',
		'fields'                => slc_collect_fields( 'theme' ),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'slc_settings',
				),
			),
		),
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	);
}

/**
 * @param string $key   Field key prefix.
 * @param string $name  Content field name.
 * @param string $label Content label.
 * @return array<int, array<string, mixed>>
 */
function slc_class_id_fields( $key, $name, $label ) {
	$class = array(
		'key'          => $key . '_class',
		'label'        => $label . ' class',
		'name'         => $name . '_class',
		'type'         => 'text',
		'instructions' => 'Optional extra classes, separated by spaces. Use hyphens, such as pull-quote.',
		'wrapper'      => array( 'width' => '50' ),
	);
	$id    = array(
		'key'          => $key . '_id',
		'label'        => $label . ' id',
		'name'         => $name . '_id',
		'type'         => 'text',
		'instructions' => 'Optional. One id. Use underscores, such as pull_quote.',
		'wrapper'      => array( 'width' => '50' ),
	);

	return array( $class, $id );
}

/**
 * Every home text and image, in page order.
 *
 * @return array<int, array<string, string>>
 */
function slc_field_catalog() {
	$items = array();
	$add   = function ( $type, $name, $label, $default = '', $toolbar = 'basic', $plain = false, $scope = 'home' ) use ( &$items ) {
		$items[] = array(
			'type'    => $type,
			'name'    => $name,
			'label'   => $label,
			'default' => $default,
			'toolbar' => $toolbar,
			'plain'   => $plain,
			'scope'   => $scope,
		);
	};

	$add( 'tab', '', 'Header', '', 'basic', false, 'theme' );
	$add( 'image', 'header_logo', 'Logo', '', 'basic', false, 'theme' );
	foreach ( slc_nav_items() as $item ) {
		$add( 'text', $item['field'], $item['label'], $item['label'], 'basic', false, 'theme' );
	}
	$add( 'text', 'discuss_menu_label', 'Menu button', 'Discuss your project', 'basic', false, 'theme' );
	$add( 'text', 'discuss_header_label', 'Header button', 'Discuss your project', 'basic', false, 'theme' );
	$add( 'text', 'discuss_url', 'Discuss your project link', '/contact/', 'basic', true, 'theme' );

	$add( 'tab', '', 'Menu', '', 'basic', false, 'theme' );
	$add( 'text', 'menu_title', 'Title', 'Capabilities', 'basic', false, 'theme' );
	$menu = slc_capability_menu();
	$add( 'wysiwyg', 'menu_intro', 'Intro', $menu['intro'], 'basic', false, 'theme' );
	$add( 'text', 'menu_all_label', 'All capabilities label', 'All capabilities', 'basic', false, 'theme' );
	foreach ( $menu['columns'] as $index => $column ) {
		$n = $index + 1;
		$add( 'text', "menu_{$n}_title", $column['title'], $column['title'], 'basic', false, 'theme' );
		$add( 'wysiwyg', "menu_{$n}_summary", $column['title'] . ' summary', $column['summary'], 'basic', false, 'theme' );
		foreach ( $column['links'] as $link_index => $link ) {
			$add( 'text', 'menu_' . $n . '_link_' . ( $link_index + 1 ), $link['label'], $link['label'], 'basic', false, 'theme' );
			$add( 'text', 'menu_' . $n . '_link_' . ( $link_index + 1 ) . '_url', $link['label'] . ' link', '', 'basic', true, 'theme' );
		}
	}

	$add( 'tab', '', 'Hero' );
	$add( 'wysiwyg', 'hero_heading', 'Heading', 'A more direct way to<br>deliver critical infrastructure', 'inline' );
	$add( 'wysiwyg', 'hero_body', 'Body', 'Streamline Connect delivers communications networks, power systems and modular facilities using its own crews, specialist plant and manufacturing capability. You deal directly with people who understand the work on site and can make decisions about how it gets done.' );
	$add( 'image', 'hero_image', 'Photograph' );
	$add( 'text', 'hero_image_alt', 'Photograph description', 'Tracked trencher laying cable beside a reel and site containers.' );
	$add( 'text', 'hero_jump_label', 'Jump panel label', 'On this page' );
	foreach ( slc_jumps() as $jump ) {
		$add( 'text', $jump['field'], $jump['label'], $jump['label'] );
	}
	$add( 'text', 'discuss_hero_label', 'Hero button', 'Discuss your project' );

	$add( 'tab', '', 'Where we deliver' );
	$add( 'wysiwyg', 'sectors_heading', 'Heading', 'Where we deliver', 'inline' );
	foreach ( slc_sectors() as $sector ) {
		$add( 'text', $sector['title_field'], $sector['title'], $sector['title'] );
		$add( 'text', $sector['text_field'], $sector['title'] . ' description', $sector['text'] );
		$add( 'text', str_replace( '_title', '_url', $sector['title_field'] ), $sector['title'] . ' link', '', 'basic', true );
	}

	$add( 'tab', '', 'What we deliver' );
	$add( 'wysiwyg', 'capabilities_heading', 'Heading', 'What we deliver', 'inline' );
	foreach ( slc_capabilities() as $card ) {
		$add( 'image', $card['image_field'], $card['title'] . ' photo' );
		$add( 'text', $card['alt_field'], $card['title'] . ' photo description', $card['alt'] );
		$add( 'text', $card['title_field'], $card['title'], $card['title'] );
		$add( 'wysiwyg', $card['text_field'], $card['title'] . ' description', $card['text'] );
		$add( 'text', $card['link_field'], $card['title'] . ' link label', $card['link'] );
		$add( 'text', str_replace( '_title', '_url', $card['title_field'] ), $card['title'] . ' link', '', 'basic', true );
	}

	$add( 'tab', '', 'Why Streamline' );
	$add( 'wysiwyg', 'why_heading', 'Heading', 'Why Streamline', 'inline' );
	foreach ( slc_reasons() as $reason ) {
		$add( 'text', $reason['index_field'], $reason['title'] . ' number', $reason['index'] );
		$add( 'text', $reason['title_field'], $reason['title'], $reason['title'] );
		$add( 'wysiwyg', $reason['text_field'], $reason['title'] . ' body', $reason['text'] );
	}
	foreach ( slc_stats() as $stat ) {
		$add( 'text', $stat['label_field'], $stat['label'], $stat['label'] );
		$add( 'text', $stat['figure_field'], $stat['label'] . ' figure', $stat['figure'] );
		$add( 'text', $stat['suffix_field'], $stat['label'] . ' suffix', $stat['suffix'] );
	}

	$add( 'tab', '', 'How we deliver' );
	$add( 'wysiwyg', 'delivery_heading', 'Heading', 'How We Deliver', 'inline' );
	$add( 'wysiwyg', 'delivery_intro', 'Intro', 'From a defined construction package to a broader infrastructure project, Streamline involves the people responsible for delivery in planning the work. Our own crews undertake the core stages, supported by trusted contractors under our management where required. On projects involving both site construction and manufacturing, these activities proceed in parallel.' );
	foreach ( slc_stages() as $stage ) {
		$add( 'text', $stage['index_field'], $stage['title'] . ' number', $stage['index'] );
		$add( 'text', $stage['title_field'], $stage['title'], $stage['title'] );
		$add( 'wysiwyg', $stage['text_field'], $stage['title'] . ' body', $stage['text'] );
	}
	$add( 'text', 'discuss_delivery_label', 'Section button', 'Discuss your project' );

	$add( 'tab', '', 'Projects' );
	$add( 'wysiwyg', 'projects_heading', 'Heading', 'Related projects', 'inline' );
	$add( 'wysiwyg', 'projects_intro', 'Intro', 'Clients keep our teams on between scopes and talk to us early about their next project.' );
	foreach ( slc_projects() as $project ) {
		$add( 'image', $project['image_field'], $project['title'] . ' photo' );
		$add( 'text', $project['alt_field'], $project['title'] . ' photo description', $project['alt'] );
		$add( 'text', $project['title_field'], $project['title'], $project['title'] );
		$add( 'text', $project['chip_fields'][0], $project['title'] . ' chip 1', $project['chips'][0] );
		$add( 'text', $project['chip_fields'][1], $project['title'] . ' chip 2', $project['chips'][1] );
		$add( 'text', $project['sector_field'], $project['title'] . ' sector', $project['sector'] );
		$add( 'text', $project['client_field'], $project['title'] . ' client', $project['client'] );
		$add( 'text', $project['location_field'], $project['title'] . ' location', $project['location'] );
	}
	$add( 'text', 'projects_link_label', 'Section button', 'Explore our projects.' );

	$add( 'tab', '', 'Clients' );
	$add( 'wysiwyg', 'clients_heading', 'Heading', 'Trusted by operators, accredited to deliver', 'inline' );
	$add( 'text', 'clients_label', 'Logo label', 'Selected clients' );
	foreach ( slc_clients() as $client ) {
		$add( 'image', $client['field'], $client['name'] . ' logo' );
		$add( 'text', str_replace( '_image', '_name', $client['field'] ), $client['name'] . ' name', $client['name'] );
	}

	$add( 'tab', '', 'Built here' );
	$add( 'wysiwyg', 'built_heading', 'Heading', 'Built here.<br>Delivered with confidence.', 'inline' );
	$add( 'wysiwyg', 'built_body', 'Body', 'At our Embleton factory in Western Australia, you can inspect your Data Room and witness Factory Acceptance Testing. The people who assembled it are on hand to investigate and resolve issues before dispatch. Our site crews can prepare the foundations and connections while the factory team completes your room.' );
	$add( 'text', 'built_link_label', 'Link label', 'See how we manufacture and deliver.' );
	$add( 'text', 'built_link_url', 'Link', '/capabilities/modular-infrastructure/#design_manufacturing_and_delivery', 'basic', true );
	$add( 'image', 'built_image', 'Photograph' );
	$add( 'text', 'built_image_alt', 'Photograph description', 'Fabrication bay at the Embleton factory, with a welder at work.' );
	foreach ( slc_products() as $product ) {
		$add( 'text', $product['title_field'], $product['title'], $product['title'] );
		$add( 'text', $product['text_field'], $product['title'] . ' description', $product['text'] );
		$add( 'text', str_replace( '_title', '_url', $product['title_field'] ), $product['title'] . ' link', '', 'basic', true );
	}

	$add( 'tab', '', 'Enquiry', '', 'basic', false, 'theme' );
	$add( 'wysiwyg', 'enquiry_heading', 'Heading', 'Tell us about your project', 'inline', false, 'theme' );
	$add( 'wysiwyg', 'enquiry_body', 'Body', "Tell us what you're planning, where the site is and the programme you're working on.", 'basic', false, 'theme' );
	$add( 'text', 'discuss_enquiry_label', 'Button', 'Discuss your project.', 'basic', false, 'theme' );

	$add( 'tab', '', 'Footer', '', 'basic', false, 'theme' );
	$add( 'image', 'footer_logo', 'Logo', '', 'basic', false, 'theme' );
	$add( 'text', 'accreditations_label', 'Accreditations label', 'Accreditations & licences', 'basic', false, 'theme' );
	$add( 'image', 'iso_9001', 'ISO 9001 mark', '', 'basic', false, 'theme' );
	$add( 'image', 'iso_45001', 'ISO 45001 mark', '', 'basic', false, 'theme' );
	$add( 'image', 'iso_14001', 'ISO 14001 mark', '', 'basic', false, 'theme' );
	$add( 'text', 'footer_email', 'Email', 'hello@streamlineconnect.com.au', 'basic', false, 'theme' );
	$add( 'text', 'footer_location', 'Location', 'Perth, Western Australia', 'basic', false, 'theme' );
	$add( 'text', 'footer_copyright', 'Copyright', '© 2026 Streamline Connect Pty Ltd', 'basic', false, 'theme' );
	$add( 'text', 'legal_privacy', 'Privacy label', 'Privacy Policy', 'basic', false, 'theme' );
	$add( 'text', 'legal_terms', 'Terms label', 'Terms', 'basic', false, 'theme' );
	$add( 'text', 'legal_accessibility', 'Accessibility label', 'Accessibility', 'basic', false, 'theme' );
	$add( 'text', 'legal_top', 'Back to top label', 'Back to top ↑', 'basic', false, 'theme' );

	return $items;
}

add_action( 'init', 'slc_register_settings_type' );
add_action( 'admin_menu', 'slc_theme_settings_menu' );
add_action( 'load-appearance_page_slc-theme-settings', 'slc_theme_settings_form_head' );
add_action( 'acf/init', 'slc_seed_theme_logos', 30 );
add_filter( 'upload_mimes', 'slc_allow_svg_upload' );
add_filter( 'wp_check_filetype_and_ext', 'slc_allow_svg_filetype', 10, 4 );

/**
 * Private post that stores the shared header and footer fields.
 */
function slc_register_settings_type() {
	register_post_type(
		'slc_settings',
		array(
			'labels'  => array( 'name' => 'Theme settings' ),
			'public'  => false,
			'show_ui' => false,
			'supports'=> array( 'title' ),
		)
	);
}

/**
 * The single settings record.
 *
 * @return int
 */
function slc_settings_post_id() {
	$id = (int) get_option( 'slc_theme_settings_id' );
	if ( $id && 'slc_settings' === get_post_type( $id ) ) {
		return $id;
	}

	$id = wp_insert_post(
		array(
			'post_type'   => 'slc_settings',
			'post_status' => 'publish',
			'post_title'  => 'Theme settings',
		)
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}

	update_option( 'slc_theme_settings_id', (int) $id, false );
	return (int) $id;
}

/**
 * Header, menu, enquiry, and footer fields are read from Theme settings.
 *
 * @param string $key Field name.
 * @return bool
 */
function slc_is_theme_setting( $key ) {
	$key = preg_replace( '/_(class|id)$/', '', (string) $key );
	$exact = array(
		'header_logo',
		'discuss_menu_label',
		'discuss_header_label',
		'discuss_url',
		'footer_logo',
		'accreditations_label',
		'iso_9001',
		'iso_45001',
		'iso_14001',
		'footer_email',
		'footer_location',
		'footer_copyright',
		'legal_privacy',
		'legal_terms',
		'legal_accessibility',
		'legal_top',
		'enquiry_heading',
		'enquiry_body',
		'discuss_enquiry_label',
	);

	if ( in_array( $key, $exact, true ) ) {
		return true;
	}

	return 0 === strpos( $key, 'nav_' ) || 0 === strpos( $key, 'menu_' );
}

/**
 * @param array<string, string> $mimes Mime types.
 * @return array<string, string>
 */
function slc_allow_svg_upload( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}

/**
 * @param array<string, string> $data     File data.
 * @param string                $file     File path.
 * @param string                $filename File name.
 * @param array<string, string> $mimes    Mime types.
 * @return array<string, string>
 */
function slc_allow_svg_filetype( $data, $file, $filename, $mimes ) {
	unset( $file, $mimes );
	if ( 'svg' === strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	return $data;
}

/**
 * Copy a theme asset into the media library once.
 *
 * @param string $relative Path under assets/.
 * @param string $title    Attachment title.
 * @return int
 */
function slc_import_theme_asset( $relative, $title ) {
	$found = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'meta_key'       => '_slc_theme_asset',
			'meta_value'     => $relative,
			'fields'         => 'ids',
		)
	);
	if ( $found ) {
		return (int) $found[0];
	}

	$path = get_theme_file_path( 'assets/' . ltrim( $relative, '/' ) );
	if ( ! is_readable( $path ) ) {
		return 0;
	}

	$uploaded = wp_upload_bits( basename( $path ), null, file_get_contents( $path ) );
	if ( ! empty( $uploaded['error'] ) ) {
		return 0;
	}

	$filetype = wp_check_filetype( basename( $path ), null );
	$id       = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/svg+xml',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$uploaded['file']
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}

	update_post_meta( $id, '_slc_theme_asset', $relative );
	return (int) $id;
}

/**
 * Put the theme logos into the Header and Footer fields when those fields are empty.
 */
function slc_seed_theme_logos() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$settings = slc_settings_post_id();
	if ( ! $settings ) {
		return;
	}

	$assets = array(
		'header_logo' => array( 'images/logo.svg', 'Streamline Connect logo' ),
		'footer_logo' => array( 'images/logo-footer.svg', 'Streamline Connect footer logo' ),
	);

	foreach ( $assets as $field => $asset ) {
		$current = get_field( $field, $settings );
		if ( slc_attachment_exists( slc_attachment_id( $current ) ) ) {
			continue;
		}
		$id = slc_import_theme_asset( $asset[0], $asset[1] );
		if ( $id ) {
			update_field( $field, $id, $settings );
		}
	}
}

/**
 * Appearance → Theme settings.
 */
function slc_theme_settings_menu() {
	add_theme_page(
		__( 'Theme settings', 'streamlineconnect' ),
		__( 'Theme settings', 'streamlineconnect' ),
		'edit_theme_options',
		'slc-theme-settings',
		'slc_render_theme_settings'
	);
}

/**
 * ACF has to prepare the form before the page is printed.
 */
function slc_theme_settings_form_head() {
	if ( function_exists( 'acf_form_head' ) ) {
		acf_form_head();
	}
}

/**
 * Header and footer editor.
 */
function slc_render_theme_settings() {
	if ( ! function_exists( 'acf_form' ) ) {
		echo '<div class="wrap"><h1>' . esc_html__( 'Theme settings', 'streamlineconnect' ) . '</h1><p>' . esc_html__( 'Advanced Custom Fields needs to be active.', 'streamlineconnect' ) . '</p></div>';
		return;
	}

	$settings = slc_settings_post_id();
	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Theme settings', 'streamlineconnect' ) . '</h1>';
	echo '<p>' . esc_html__( 'The header and footer are shared by every page.', 'streamlineconnect' ) . '</p>';
	acf_form(
		array(
			'post_id'         => $settings,
			'field_groups'    => array( 'group_slc_theme_settings' ),
			'submit_value'    => __( 'Save theme settings', 'streamlineconnect' ),
			'updated_message' => __( 'Theme settings saved.', 'streamlineconnect' ),
		)
	);
	echo '</div>';
}
