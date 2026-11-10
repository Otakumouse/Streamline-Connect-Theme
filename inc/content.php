<?php
/**
 * Approved home-page content and shared destinations.
 *
 * @package StreamlineConnect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a site URL, keeping a #fragment intact.
 *
 * @param string $path Path beginning with /.
 */
function slc_url( $path = '/' ) {
	$fragment = '';
	$hash     = strpos( $path, '#' );

	if ( false !== $hash ) {
		$fragment = substr( $path, $hash );
		$path     = substr( $path, 0, $hash );
	}

	$path = trim( $path, '/' );

	if ( '' === $path ) {
		return home_url( '/' ) . $fragment;
	}

	return home_url( user_trailingslashit( $path ) ) . $fragment;
}

/**
 * Every "Discuss your project" control uses this destination.
 */
function slc_discuss_url() {
	return slc_href( 'discuss_url', slc_url( '/contact/' ) );
}

/**
 * Visible label for one Discuss control.
 *
 * @param string $field    Label field.
 * @param string $fallback Theme label.
 * @return string
 */
function slc_discuss_label( $field, $fallback = 'Discuss your project' ) {
	return slc_plain( $field, $fallback );
}

/**
 * Overlay numbered text fields onto a list of rows.
 *
 * @param array<int, array<string, mixed>> $rows      Theme rows.
 * @param string                            $prefix    Field prefix.
 * @param string[]                          $text_keys String keys.
 * @param string[]                          $url_keys  URL keys.
 * @return array<int, array<string, mixed>>
 */
function slc_bind_list( $rows, $prefix, $text_keys, $url_keys = array() ) {
	foreach ( $rows as $index => &$row ) {
		$n = $index + 1;
		foreach ( $text_keys as $key ) {
			$field                   = $prefix . '_' . $n . '_' . $key;
			$row[ $key . '_field' ]  = $field;
			if ( isset( $row[ $key ] ) && is_string( $row[ $key ] ) ) {
				$row[ $key ] = slc_plain( $field, $row[ $key ] );
			}
		}
		foreach ( $url_keys as $key ) {
			$field                  = $prefix . '_' . $n . '_' . $key;
			$row[ $key . '_field' ] = $field;
			$row[ $key ]            = slc_href( $field, $row[ $key ] );
		}
	}
	unset( $row );

	return $rows;
}

/**
 * Theme file URL under assets/.
 *
 * @param string $path Path relative to assets/.
 */
function slc_asset( $path ) {
	return get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) );
}

/**
 * Print a decorative icon from the design file.
 *
 * @param string $file Icon filename.
 */
function slc_icon( $file ) {
	printf(
		'<img class="icon" src="%s" alt="" decoding="async" />',
		esc_url( slc_asset( 'icons/' . $file ) )
	);
}

/**
 * Primary navigation. Only Capabilities has a designed panel.
 */
function slc_nav_items() {
	$items = array(
		array(
			'label'   => __( 'Capabilities', 'streamlineconnect' ),
			'field'   => 'nav_capabilities_label',
			'url'     => slc_url( '/capabilities/' ),
			'menu'    => true,
			'chevron' => true,
		),
		array(
			'label'   => __( 'Products', 'streamlineconnect' ),
			'field'   => 'nav_products_label',
			'url'     => slc_url( '/products/' ),
			'menu'    => false,
			'chevron' => true,
		),
		array(
			'label'   => __( 'Projects', 'streamlineconnect' ),
			'field'   => 'nav_projects_label',
			'url'     => slc_url( '/projects/' ),
			'menu'    => false,
			'chevron' => true,
		),
		array(
			'label'   => __( 'About', 'streamlineconnect' ),
			'field'   => 'nav_about_label',
			'url'     => slc_url( '/about/' ),
			'menu'    => false,
			'chevron' => true,
		),
		array(
			'label'   => __( 'Contact', 'streamlineconnect' ),
			'field'   => 'nav_contact_label',
			'url'     => slc_url( '/contact/' ),
			'menu'    => false,
			'chevron' => false,
		),
	);

	foreach ( $items as &$item ) {
		$item['label'] = slc_plain( $item['field'], $item['label'] );
	}
	unset( $item );

	return $items;
}

/**
 * Capabilities menu, using the page taxonomy rather than the Latin stand-ins.
 */
function slc_capability_menu() {
	return array(
		'intro'   => __( 'Streamline Connect delivers communications networks, power systems and modular facilities using its own crews, specialist plant and manufacturing capability.', 'streamlineconnect' ),
		'columns' => array(
			array(
				'title'   => __( 'Communications', 'streamlineconnect' ),
				'url'     => slc_url( '/capabilities/communications-infrastructure/' ),
				'summary' => __( 'Fibre, wireless and critical communications, including the civil construction and technical installation that support them.', 'streamlineconnect' ),
				'links'   => array(
					array(
						'label' => __( 'Fibre Networks', 'streamlineconnect' ),
						'url'   => slc_url( '/capabilities/communications-infrastructure/#fibre_networks' ),
					),
					array(
						'label' => __( 'Wireless Infrastructure', 'streamlineconnect' ),
						'url'   => slc_url( '/capabilities/communications-infrastructure/#wireless_infrastructure' ),
					),
					array(
						'label' => __( 'Critical Communications', 'streamlineconnect' ),
						'url'   => slc_url( '/capabilities/communications-infrastructure/#critical_communications' ),
					),
					array(
						'label' => __( 'Communications Facilities', 'streamlineconnect' ),
						'url'   => slc_url( '/capabilities/communications-infrastructure/#communications_facilities' ),
					),
				),
			),
			array(
				'title'   => __( 'Modular', 'streamlineconnect' ),
				'url'     => slc_url( '/capabilities/modular-infrastructure/' ),
				'summary' => __( 'Data Rooms for communications and IT equipment, and Facilities for project crews, designed and manufactured by Streamline.', 'streamlineconnect' ),
				'links'   => array(
					array(
						'label' => __( 'Data Rooms', 'streamlineconnect' ),
						'url'   => slc_url( '/products/data-rooms/' ),
					),
					array(
						'label' => __( 'Facilities', 'streamlineconnect' ),
						'url'   => slc_url( '/products/facilities/' ),
					),
				),
			),
			array(
				'title'   => __( 'Power & Energy', 'streamlineconnect' ),
				'url'     => slc_url( '/capabilities/power-and-energy/' ),
				'summary' => __( 'Electrical installations and upgrades, with renewable, hybrid and stand-alone power systems for networks and facilities.', 'streamlineconnect' ),
				'links'   => array(
					array(
						'label' => __( 'Renewable & Hybrid Energy', 'streamlineconnect' ),
						'url'   => slc_url( '/capabilities/power-and-energy/#renewable_and_hybrid_energy' ),
					),
					array(
						'label' => __( 'Remote Power Systems', 'streamlineconnect' ),
						'url'   => slc_url( '/capabilities/power-and-energy/#remote_power_systems' ),
					),
					array(
						'label' => __( 'Power Monitoring and Control', 'streamlineconnect' ),
						'url'   => slc_url( '/capabilities/power-and-energy/#power_monitoring_and_control' ),
					),
				),
			),
		),
	);
}

/**
 * Hero jump links.
 */
function slc_jumps() {
	$rows = array(
		array(
			'label' => __( 'Where we deliver', 'streamlineconnect' ),
			'field' => 'jump_1_label',
			'url'   => '#where_we_deliver',
		),
		array(
			'label' => __( 'What we deliver', 'streamlineconnect' ),
			'field' => 'jump_2_label',
			'url'   => '#what_we_deliver',
		),
		array(
			'label' => __( 'Why Streamline', 'streamlineconnect' ),
			'field' => 'jump_3_label',
			'url'   => '#why_streamline',
		),
		array(
			'label' => __( 'How we deliver', 'streamlineconnect' ),
			'field' => 'jump_4_label',
			'url'   => '#how_we_deliver',
		),
		array(
			'label' => __( 'Related projects', 'streamlineconnect' ),
			'field' => 'jump_5_label',
			'url'   => '#related_projects',
		),
	);

	foreach ( $rows as &$row ) {
		$row['label'] = slc_plain( $row['field'], $row['label'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Industry rows. Destinations are the anchors named on the Home frame.
 */
function slc_sectors() {
	$rows = array(
		array(
			'id'    => 'mining',
			'title' => __( 'Mining and resources', 'streamlineconnect' ),
			'text'  => __( 'Communications and supporting infrastructure for remote projects and operating mine sites.', 'streamlineconnect' ),
			'url'   => slc_url( '/projects/industries/#mining' ),
		),
		array(
			'id'    => 'telco',
			'title' => __( 'Telecommunications', 'streamlineconnect' ),
			'text'  => __( 'Fibre and wireless construction for carriers and private network operators.', 'streamlineconnect' ),
			'url'   => slc_url( '/projects/industries/#telco' ),
		),
		array(
			'id'    => 'energy',
			'title' => __( 'Energy and utilities', 'streamlineconnect' ),
			'text'  => __( 'Communications and power infrastructure supporting utility assets and energy developments.', 'streamlineconnect' ),
			'url'   => slc_url( '/projects/industries/#energy' ),
		),
		array(
			'id'    => 'rail',
			'title' => __( 'Rail and transport', 'streamlineconnect' ),
			'text'  => __( 'Network construction and upgrades around live rail operations.', 'streamlineconnect' ),
			'url'   => slc_url( '/projects/industries/#rail' ),
		),
		array(
			'id'    => 'government',
			'title' => __( 'Government', 'streamlineconnect' ),
			'text'  => __( 'Communications, power and modular facilities for government projects.', 'streamlineconnect' ),
			'url'   => slc_url( '/projects/industries/#government' ),
		),
	);

	foreach ( $rows as $index => &$row ) {
		$n                 = $index + 1;
		$row['title_field'] = "sector_{$n}_title";
		$row['text_field']  = "sector_{$n}_text";
		$row['title']       = slc_plain( $row['title_field'], $row['title'] );
		$row['text']        = slc_plain( $row['text_field'], $row['text'] );
		$row['url']         = slc_href( "sector_{$n}_url", $row['url'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Capability cards.
 */
function slc_capabilities() {
	$rows = array(
		array(
			'title' => __( 'Communications infrastructure', 'streamlineconnect' ),
			'text'  => __( 'Fibre, wireless and critical communications, including the civil construction and technical installation that support them.', 'streamlineconnect' ),
			'link'  => __( 'Explore communications infrastructure.', 'streamlineconnect' ),
			'url'   => slc_url( '/capabilities/communications-infrastructure/' ),
			'image' => 'images/capability-communications.jpg',
			'alt'   => __( 'Lattice communications tower with antennas against a cloudy sky.', 'streamlineconnect' ),
		),
		array(
			'title' => __( 'Power and energy', 'streamlineconnect' ),
			'text'  => __( 'Electrical installations and upgrades, with renewable, hybrid and stand-alone power systems for networks and facilities.', 'streamlineconnect' ),
			'link'  => __( 'Explore power and energy.', 'streamlineconnect' ),
			'url'   => slc_url( '/capabilities/power-and-energy/' ),
			'image' => 'images/capability-power.jpg',
			'alt'   => __( 'Solar array beside a communications tower and equipment container.', 'streamlineconnect' ),
		),
		array(
			'title' => __( 'Modular infrastructure', 'streamlineconnect' ),
			'text'  => __( 'Data Rooms for communications and IT equipment, and Facilities for project crews, designed and manufactured by Streamline.', 'streamlineconnect' ),
			'link'  => __( 'Explore modular infrastructure.', 'streamlineconnect' ),
			'url'   => slc_url( '/capabilities/modular-infrastructure/' ),
			'image' => 'images/capability-modular.png',
			'alt'   => __( 'Welder working inside a fabricated modular room.', 'streamlineconnect' ),
		),
	);

	foreach ( $rows as $index => &$row ) {
		$n                  = $index + 1;
		$row['title_field'] = "capability_{$n}_title";
		$row['text_field']  = "capability_{$n}_text";
		$row['link_field']  = "capability_{$n}_link";
		$row['alt_field']   = "capability_{$n}_alt";
		$row['image_field'] = "capability_{$n}_image";
		$row['title']       = slc_plain( $row['title_field'], $row['title'] );
		$row['text']        = slc_plain( $row['text_field'], $row['text'] );
		$row['link']        = slc_plain( $row['link_field'], $row['link'] );
		$row['alt']         = slc_plain( $row['alt_field'], $row['alt'] );
		$row['url']         = slc_href( "capability_{$n}_url", $row['url'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Why Streamline tiles.
 */
function slc_reasons() {
	return slc_bind_list(
		array(
		array(
			'index' => '01',
			'title' => __( 'One Accountable Team', 'streamlineconnect' ),
			'text'  => __( 'Our electricians and civil crews work together when changes affect power connections and cable routes. You can agree those changes with one team responsible for the result.', 'streamlineconnect' ),
		),
		array(
			'index' => '02',
			'title' => __( 'Self-Performed Delivery', 'streamlineconnect' ),
			'text'  => __( 'Our crews use Streamline’s cable ploughs, rock saws and directional drills. Owning that plant lets us schedule it around your construction programme.', 'streamlineconnect' ),
		),
		array(
			'index' => '03',
			'title' => __( 'Telecommunications Heritage', 'streamlineconnect' ),
			'text'  => __( 'Our fibre and underground construction roots go back to Streamline Underground Services in 1996.', 'streamlineconnect' ),
		),
		array(
			'index' => '04',
			'title' => __( 'Built Here', 'streamlineconnect' ),
			'text'  => __( 'We manufacture our modular rooms at Embleton in Western Australia for use on remote infrastructure projects and operating sites across the country.', 'streamlineconnect' ),
		),
		array(
			'index' => '05',
			'title' => __( 'Proven in Remote and Live Environments', 'streamlineconnect' ),
			'text'  => __( 'Our crews have delivered communications infrastructure on live mine sites, along rail corridors and across telecommunications networks.', 'streamlineconnect' ),
		),
		array(
			'index' => '06',
			'title' => __( 'Built to Perform', 'streamlineconnect' ),
			'text'  => __( 'We draw on what our installers have learned on site to plan cable entries and leave technicians room to access equipment for maintenance.', 'streamlineconnect' ),
		),
		),
		'reason',
		array( 'index', 'title', 'text' )
	);
}

/**
 * Stat strip. Figures are the ones drawn on Home and still need business sign-off.
 */
function slc_stats() {
	return slc_bind_list(
		array(
		array(
			'label'  => __( 'Years in the field', 'streamlineconnect' ),
			'figure' => '30',
			'suffix' => '+',
		),
		array(
			'label'  => __( 'Modules delivered', 'streamlineconnect' ),
			'figure' => '1,200',
			'suffix' => '+',
		),
		array(
			'label'  => __( 'Capability areas', 'streamlineconnect' ),
			'figure' => '3',
			'suffix' => '',
		),
		array(
			'label'  => __( 'Built in Western Australia', 'streamlineconnect' ),
			'figure' => '100',
			'suffix' => '%',
		),
		array(
			'label'  => __( 'Quote response', 'streamlineconnect' ),
			'figure' => '24',
			'suffix' => 'h',
		),
		),
		'stat',
		array( 'label', 'figure', 'suffix' )
	);
}

/**
 * Delivery stages. Cards are content, not links.
 */
function slc_stages() {
	return slc_bind_list(
		array(
		array(
			'index'    => '01',
			'title'    => __( 'Design', 'streamlineconnect' ),
			'parallel' => false,
			'text'     => __( 'We work with you from concept through to detailed design. Our engineers review site conditions, cable routes, equipment access and maintenance requirements with the construction and installation teams, addressing practical constraints before work begins on site.', 'streamlineconnect' ),
		),
		array(
			'index'    => '02',
			'title'    => __( 'Site construction', 'streamlineconnect' ),
			'parallel' => true,
			'text'     => __( 'Our crews prepare the ground through earthworks, trenching, foundations, pads and access. They also install cable and conduit, deliver site services and reinstate disturbed ground, coordinating the civil works with the electrical and communications installations that follow.', 'streamlineconnect' ),
		),
		array(
			'index'    => '03',
			'title'    => __( 'Manufacture and test', 'streamlineconnect' ),
			'parallel' => true,
			'text'     => __( 'Our factory team fabricates and assembles modular rooms, completes the electrical and equipment fit-out, and integrates the systems specified for the project. Factory Acceptance Testing checks how a Data Room\'s power, cooling and controls operate together before dispatch.', 'streamlineconnect' ),
		),
		array(
			'index'    => '04',
			'title'    => __( 'Install', 'streamlineconnect' ),
			'parallel' => false,
			'text'     => __( 'Our teams coordinate the transport and installation of networks, equipment and modular facilities. On operating sites, electrical works and fibre cutovers are planned around existing services to minimise disruption.', 'streamlineconnect' ),
		),
		array(
			'index'    => '05',
			'title'    => __( 'Integrate and commission', 'streamlineconnect' ),
			'parallel' => false,
			'text'     => __( 'We connect the installed infrastructure to your communications, power and operational systems. Our teams test the connections and verify the operation of the completed installation, including environmental controls and monitoring where specified, before handover.', 'streamlineconnect' ),
		),
		array(
			'index'    => '06',
			'title'    => __( 'Support', 'streamlineconnect' ),
			'parallel' => false,
			'text'     => __( 'Our teams provide maintenance, repairs and upgrades after handover. As your requirements change, you continue working with people familiar with the infrastructure and the conditions under which it was installed.', 'streamlineconnect' ),
		),
		),
		'stage',
		array( 'index', 'title', 'text' )
	);
}

/**
 * Featured projects. Summaries in the file are Latin and are omitted.
 * PDF files are not in the design; drop them into assets/documents/.
 */
function slc_projects() {
	$rows = array(
		array(
			'title'    => __( 'Regional comms network upgrade', 'streamlineconnect' ),
			'chips'    => array(
				__( 'Communications', 'streamlineconnect' ),
				__( 'Power', 'streamlineconnect' ),
			),
			'sector'   => __( 'Telecommunications', 'streamlineconnect' ),
			'client'   => __( 'Network operator', 'streamlineconnect' ),
			'location' => __( 'Pilbara, WA', 'streamlineconnect' ),
			'image'    => 'images/project-regional.jpg',
			'alt'      => __( 'Cell tower and antennas against a clear sky.', 'streamlineconnect' ),
			'pdf'      => 'regional-comms-network-upgrade.pdf',
		),
		array(
			'title'    => __( 'Edge data room programme', 'streamlineconnect' ),
			'chips'    => array(
				__( 'Data Rooms', 'streamlineconnect' ),
				__( 'Modular', 'streamlineconnect' ),
			),
			'sector'   => __( 'Government', 'streamlineconnect' ),
			'client'   => __( 'State agency', 'streamlineconnect' ),
			'location' => __( 'Kalgoorlie, WA', 'streamlineconnect' ),
			'image'    => 'images/project-edge.jpg',
			'alt'      => __( 'Sparks from fabrication work inside a workshop.', 'streamlineconnect' ),
			'pdf'      => 'edge-data-room-programme.pdf',
		),
	);

	foreach ( $rows as $index => &$row ) {
		$n                  = $index + 1;
		$row['title_field'] = "project_{$n}_title";
		$row['alt_field']   = "project_{$n}_alt";
		$row['image_field'] = "project_{$n}_image";
		$row['title']       = slc_plain( $row['title_field'], $row['title'] );
		$row['alt']         = slc_plain( $row['alt_field'], $row['alt'] );
		$row['sector']      = slc_plain( "project_{$n}_sector", $row['sector'] );
		$row['client']      = slc_plain( "project_{$n}_client", $row['client'] );
		$row['location']    = slc_plain( "project_{$n}_location", $row['location'] );
		$row['chips'][0]    = slc_plain( "project_{$n}_chip_1", $row['chips'][0] );
		$row['chips'][1]    = slc_plain( "project_{$n}_chip_2", $row['chips'][1] );
		$row['chip_fields'] = array( "project_{$n}_chip_1", "project_{$n}_chip_2" );
		$row['sector_field']   = "project_{$n}_sector";
		$row['client_field']   = "project_{$n}_client";
		$row['location_field'] = "project_{$n}_location";
	}
	unset( $row );

	return $rows;
}

/**
 * Client marks, in the order drawn on Home.
 */
function slc_clients() {
	$rows = array(
		array(
			'name'   => 'BHP',
			'file'   => 'images/clients/bhp.svg',
			'width'  => 93,
			'height' => 35,
		),
		array(
			'name'   => 'Rio Tinto',
			'file'   => 'images/clients/rio-tinto.svg',
			'width'  => 148,
			'height' => 30,
		),
		array(
			'name'   => 'Fortescue',
			'file'   => 'images/clients/fortescue.svg',
			'width'  => 128,
			'height' => 39,
		),
		array(
			'name'   => 'Telstra',
			'file'   => 'images/clients/telstra.svg',
			'width'  => 45,
			'height' => 52,
		),
		array(
			'name'   => 'South32',
			'file'   => 'images/clients/south32.png',
			'width'  => 45,
			'height' => 56,
		),
		array(
			'name'   => 'Roy Hill',
			'file'   => 'images/clients/roy-hill.png',
			'width'  => 46,
			'height' => 48,
		),
		array(
			'name'   => 'Chevron',
			'file'   => 'images/clients/chevron.png',
			'width'  => 50,
			'height' => 56,
		),
		array(
			'name'   => 'Fulton Hogan',
			'file'   => 'images/clients/fulton-hogan.svg',
			'width'  => 176,
			'height' => 26,
		),
		array(
			'name'   => 'Ventia',
			'file'   => 'images/clients/ventia.svg',
			'width'  => 130,
			'height' => 38,
		),
		array(
			'name'   => 'Optus',
			'file'   => 'images/clients/optus.png',
			'width'  => 142,
			'height' => 28,
		),
		array(
			'name'   => 'nbn',
			'file'   => 'images/clients/nbn.svg',
			'width'  => 122,
			'height' => 49,
		),
		array(
			'name'   => 'ElectraNet',
			'file'   => 'images/clients/electranet.png',
			'width'  => 176,
			'height' => 35,
		),
		array(
			'name'   => 'Service Stream',
			'file'   => 'images/clients/service-stream.svg',
			'width'  => 148,
			'height' => 39,
		),
		array(
			'name'   => 'Nokia',
			'file'   => 'images/clients/nokia.svg',
			'width'  => 145,
			'height' => 25,
		),
		array(
			'name'   => 'Kent',
			'file'   => 'images/clients/kent.png',
			'width'  => 107,
			'height' => 47,
		),
	);

	foreach ( $rows as $index => &$row ) {
		$n               = $index + 1;
		$row['field']   = "client_{$n}_image";
		$row['name']    = slc_plain( "client_{$n}_name", $row['name'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Built here product links.
 */
function slc_products() {
	$rows = array(
		array(
			'title' => __( 'Communication Equipment Rooms', 'streamlineconnect' ),
			'text'  => __( 'House your fibre, wireless and private LTE equipment.', 'streamlineconnect' ),
			'url'   => slc_url( '/products/data-rooms/#communication_equipment_rooms' ),
		),
		array(
			'title' => __( 'Modular Data Centres', 'streamlineconnect' ),
			'text'  => __( 'A modular facility for servers and other IT equipment.', 'streamlineconnect' ),
			'url'   => slc_url( '/products/data-rooms/#modular_data_centres' ),
		),
		array(
			'title' => __( 'Project and operational facilities', 'streamlineconnect' ),
			'text'  => __( 'Offices and amenities for site teams, with off-grid Zero Emission Rooms and grid-connected options.', 'streamlineconnect' ),
			'url'   => slc_url( '/products/facilities/#zer' ),
		),
	);

	foreach ( $rows as $index => &$row ) {
		$n                  = $index + 1;
		$row['title_field'] = "product_{$n}_title";
		$row['text_field']  = "product_{$n}_text";
		$row['title']       = slc_plain( $row['title_field'], $row['title'] );
		$row['text']        = slc_plain( $row['text_field'], $row['text'] );
		$row['url']         = slc_href( "product_{$n}_url", $row['url'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Footer columns. Placeholder slots and the unpublished phone number are omitted.
 */
function slc_footer_columns() {
	return array(
		array(
			'heading' => __( 'Capabilities', 'streamlineconnect' ),
			'links'   => array(
				array(
					'label' => __( 'Communications', 'streamlineconnect' ),
					'url'   => slc_url( '/capabilities/communications-infrastructure/' ),
				),
				array(
					'label' => __( 'Modular', 'streamlineconnect' ),
					'url'   => slc_url( '/capabilities/modular-infrastructure/' ),
				),
				array(
					'label' => __( 'Power & Energy', 'streamlineconnect' ),
					'url'   => slc_url( '/capabilities/power-and-energy/' ),
				),
			),
		),
		array(
			'heading' => __( 'Products', 'streamlineconnect' ),
			'links'   => array(
				array(
					'label' => __( 'Data Rooms', 'streamlineconnect' ),
					'url'   => slc_url( '/products/data-rooms/' ),
				),
				array(
					'label' => __( 'Facilities', 'streamlineconnect' ),
					'url'   => slc_url( '/products/facilities/' ),
				),
			),
		),
		array(
			'heading' => __( 'Company', 'streamlineconnect' ),
			'links'   => array(
				array(
					'label' => __( 'About', 'streamlineconnect' ),
					'url'   => slc_url( '/about/' ),
				),
				array(
					'label' => __( 'How we deliver', 'streamlineconnect' ),
					'url'   => slc_url( '/about/how-we-deliver/' ),
				),
				array(
					'label' => __( 'Industries', 'streamlineconnect' ),
					'url'   => slc_url( '/projects/industries/' ),
				),
				array(
					'label' => __( 'Projects', 'streamlineconnect' ),
					'url'   => slc_url( '/projects/' ),
				),
			),
		),
	);
}
