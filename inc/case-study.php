<?php
/**
 * Project case study template, fields, and the Western Turner sample page.
 *
 * Copy defaults follow the Streamline Connect case study template. Approved
 * project details replace those prompts in the field editor.
 *
 * @package StreamlineConnect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'slc_register_project_type', 5 );
add_action( 'acf/init', 'slc_register_project_taxonomy', 5 );
add_action( 'init', 'slc_seed_project_categories', 20 );
add_action( 'init', 'slc_migrate_project_posts', 30 );
add_action( 'template_redirect', 'slc_redirect_legacy_project_url' );

/**
 * Projects can be added, edited, and deleted from the admin.
 * Singles live at /project/name/ so they do not collide with the Projects page or Industries.
 */
function slc_register_project_type() {
	register_post_type(
		'project',
		array(
			'labels'              => array(
				'name'               => __( 'Projects', 'streamlineconnect' ),
				'singular_name'      => __( 'Project', 'streamlineconnect' ),
				'menu_name'          => __( 'Projects', 'streamlineconnect' ),
				'add_new'            => __( 'Add New', 'streamlineconnect' ),
				'add_new_item'       => __( 'Add New Project', 'streamlineconnect' ),
				'edit_item'          => __( 'Edit Project', 'streamlineconnect' ),
				'new_item'           => __( 'New Project', 'streamlineconnect' ),
				'view_item'          => __( 'View Project', 'streamlineconnect' ),
				'search_items'       => __( 'Search Projects', 'streamlineconnect' ),
				'not_found'          => __( 'No projects found.', 'streamlineconnect' ),
				'not_found_in_trash' => __( 'No projects found in Trash.', 'streamlineconnect' ),
				'all_items'          => __( 'All Projects', 'streamlineconnect' ),
			),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-portfolio',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'project',
				'with_front' => false,
			),
			'query_var'           => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
		)
	);
}

/**
 * Project categories, registered through ACF and attached to projects.
 * Hierarchical, like post categories. The edit screen uses the ACF field.
 *
 * @return array<string, mixed>
 */
function slc_project_category_taxonomy() {
	return array(
		'key'                    => 'taxonomy_slc_project_category',
		'title'                  => 'Project Category',
		'menu_order'             => 0,
		'active'                 => true,
		'taxonomy'               => 'project_category',
		'object_type'            => array( 'project' ),
		'advanced_configuration' => 1,
		'labels'                 => array(
			'name'                       => 'Project Categories',
			'singular_name'              => 'Project Category',
			'menu_name'                  => 'Project Categories',
			'all_items'                  => 'All Project Categories',
			'edit_item'                  => 'Edit Project Category',
			'view_item'                  => 'View Project Category',
			'update_item'                => 'Update Project Category',
			'add_new_item'               => 'Add New Project Category',
			'new_item_name'              => 'New Project Category Name',
			'parent_item'                => 'Parent Project Category',
			'parent_item_colon'          => 'Parent Project Category:',
			'search_items'               => 'Search Project Categories',
			'not_found'                  => 'No project categories found.',
			'no_terms'                   => 'No project categories',
			'filter_by_item'             => 'Filter by project category',
			'back_to_items'              => '← Go to project categories',
		),
		'description'            => '',
		'public'                 => true,
		'publicly_queryable'     => true,
		'hierarchical'           => true,
		'show_ui'                => true,
		'show_in_menu'           => true,
		'show_in_nav_menus'      => true,
		'show_in_rest'           => false,
		'show_admin_column'      => true,
		'meta_box'               => 'disabled',
		'rewrite'                => array(
			'permalink_rewrite'    => 'custom_permalink',
			'slug'                 => 'project-category',
			'with_front'           => false,
			'rewrite_hierarchical' => true,
		),
		'query_var'              => 'taxonomy_key',
	);
}

/**
 * Import the project category taxonomy before ACF registers taxonomies.
 */
function slc_register_project_taxonomy() {
	if ( ! function_exists( 'acf_import_taxonomy' ) || ! function_exists( 'acf_get_taxonomy' ) ) {
		return;
	}

	$existing = acf_get_taxonomy( 'taxonomy_slc_project_category' );
	$existing = is_array( $existing ) ? $existing : array();
	if ( ! empty( $existing['ID'] ) ) {
		return;
	}

	$taxonomy = slc_project_category_taxonomy();
	if ( ! empty( $existing['ID'] ) ) {
		$taxonomy['ID'] = (int) $existing['ID'];
	}
	acf_import_taxonomy( $taxonomy );
}

/**
 * Starter categories from the case study industry list, added once.
 */
function slc_seed_project_categories() {
	if ( '1' === get_option( 'slc_project_categories_seeded' ) || ! taxonomy_exists( 'project_category' ) ) {
		return;
	}

	$names = array( 'Government', 'Energy', 'Telecommunications', 'Resources', 'Infrastructure' );
	foreach ( $names as $name ) {
		if ( ! term_exists( $name, 'project_category' ) ) {
			wp_insert_term( $name, 'project_category' );
		}
	}

	update_option( 'slc_project_categories_seeded', '1', false );
	flush_rewrite_rules( false );
}

/**
 * Categories assigned to a project.
 *
 * @param int $post_id Project id.
 * @return WP_Term[]
 */
function slc_project_categories( $post_id ) {
	if ( ! taxonomy_exists( 'project_category' ) ) {
		return array();
	}

	$terms = get_the_terms( (int) $post_id, 'project_category' );
	if ( ! is_array( $terms ) ) {
		return array();
	}

	return $terms;
}

/**
 * Whether the current view is a project case study.
 *
 * @param int $post_id Project id. Defaults to the queried post.
 * @return bool
 */
function slc_is_case_study( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();
	return $post_id && 'project' === get_post_type( $post_id );
}

/**
 * Case study fields, in page order.
 *
 * @return array<int, array<string, string>>
 */
function slc_case_field_catalog() {
	$items = array();
	$add   = function ( $type, $name, $label, $default = '', $toolbar = 'basic', $instructions = '' ) use ( &$items ) {
		$items[] = array(
			'type'         => $type,
			'name'         => $name,
			'label'        => $label,
			'default'      => $default,
			'toolbar'      => $toolbar,
			'plain'        => true,
			'scope'        => 'case',
			'instructions' => $instructions,
		);
	};

	$add( 'tab', '', 'Introduction' );
	$add( 'text', 'case_kicker', 'Label', 'Project case study' );
	$add( 'text', 'case_tagline', 'Tagline', 'Practical delivery. Proven capability. Connected outcomes.' );
	$add( 'wysiwyg', 'case_heading', 'Heading', 'Use a clear, outcome-led heading of approximately 16 to 18 words.', 'inline', 'Outcome-led heading of approximately 16 to 18 words. Replace this prompt with approved case study content.' );

	$add( 'tab', '', 'Snapshot' );
	$add( 'taxonomy', 'case_category', 'Industry', '', 'basic', 'Select the industry for this project. Add a new industry here, or manage the list under Projects → Project Categories.' );
	$add( 'text', 'case_snapshot_heading', 'Heading', 'Project snapshot' );
	$add( 'text', 'case_industry_label', 'Industry label', 'Industry' );
	$add( 'text', 'case_client_label', 'Client label', 'Client' );
	$add( 'text', 'case_client', 'Client', 'Company name', 'basic', 'Approved client name.' );
	$add( 'text', 'case_project_label', 'Project label', 'Project' );
	$add( 'text', 'case_project', 'Project', 'Project name and reference', 'basic', 'Project name and reference.' );
	$add( 'text', 'case_location_label', 'Location label', 'Location' );
	$add( 'text', 'case_location', 'Location', 'Project location', 'basic', 'Project location.' );

	$add( 'tab', '', 'Images' );
	$add( 'text', 'case_images_heading', 'Heading', 'Project images' );
	foreach ( array( 1, 2, 3 ) as $image_number ) {
		$add( 'image', 'case_image_' . $image_number, 'Image ' . $image_number, '', 'basic', 'Approved project photograph.' );
		$add( 'text', 'case_image_' . $image_number . '_alt', 'Image ' . $image_number . ' description', 'Project image ' . $image_number );
	}

	$add( 'tab', '', 'Background' );
	$add( 'text', 'case_background_heading', 'Heading', 'Background' );
	$add( 'wysiwyg', 'case_background', 'Body', 'Briefly explain who the client is, the operating context and what the client needed to achieve.', 'basic', 'Target: approximately 150 words. Replace this prompt with approved case study content.' );

	$add( 'tab', '', 'Challenge' );
	$add( 'text', 'case_challenge_heading', 'Heading', 'The challenge' );
	$add( 'wysiwyg', 'case_challenge', 'Body', 'Describe the delivery challenge, constraints, risks and why Streamline Connect was selected or suited to assist.', 'basic', 'Target: approximately 200 words. Replace this prompt with approved case study content.' );

	$add( 'tab', '', 'Solution' );
	$add( 'text', 'case_solution_heading', 'Heading', 'The solution' );
	$add( 'wysiwyg', 'case_solution', 'Body', 'Explain what Streamline Connect designed, supplied, manufactured, constructed, installed, tested or commissioned.', 'basic', 'Replace this prompt with approved case study content.' );
	$add( 'text', 'case_detail_heading', 'Detail heading', 'Solution detail' );
	$add( 'text', 'case_programme_label', 'Programme label', 'Programme' );
	$add( 'wysiwyg', 'case_programme', 'Programme', 'What delivery timeframe or key milestones applied?', 'basic', 'Delivery timeframe or key milestones.' );
	$add( 'text', 'case_package_label', 'Delivery package label', 'Delivery package' );
	$add( 'wysiwyg', 'case_package', 'Delivery package', 'What package was tailored for the client?', 'basic', 'The package tailored for the client.' );
	$add( 'text', 'case_technical_label', 'Technical highlight label', 'Technical highlight' );
	$add( 'wysiwyg', 'case_technical', 'Technical highlight', 'What technical achievement, innovation or difficult interface can be evidenced?', 'basic', 'A technical achievement, innovation, or difficult interface that can be evidenced.' );
	$add( 'text', 'case_scope_label', 'Scope boundaries label', 'Scope boundaries' );
	$add( 'wysiwyg', 'case_scope', 'Scope boundaries', 'What was delivered by Streamline Connect and what remained by others?', 'basic', 'What Streamline Connect delivered, and what remained with others.' );

	$add( 'tab', '', 'Outcomes' );
	$add( 'text', 'case_outcomes_heading', 'Heading', 'Outcomes' );
	$add( 'wysiwyg', 'case_outcomes', 'Body', 'State measurable or verifiable results: safety, programme, operational reliability, reduced risk, improved connectivity or client value.', 'basic', 'Target: approximately 150 words. Replace this prompt with approved case study content.' );

	$add( 'tab', '', 'Voices' );
	$add( 'text', 'case_voices_heading', 'Heading', 'Project voices' );
	$add( 'text', 'case_client_quote_label', 'Client quote label', 'Client quote' );
	$add( 'wysiwyg', 'case_client_quote', 'Client quote', 'Insert an approved quote of up to approximately 60 words.', 'basic', 'Approved client quote, up to approximately 60 words.' );
	$add( 'text', 'case_client_name', 'Client name', 'Name' );
	$add( 'text', 'case_client_position', 'Client position', 'Position' );
	$add( 'text', 'case_client_organisation', 'Client organisation', 'Organisation' );
	$add( 'text', 'case_streamline_quote_label', 'Streamline Connect quote label', 'Streamline Connect quote' );
	$add( 'wysiwyg', 'case_streamline_quote', 'Streamline Connect quote', 'Insert an approved quote of up to approximately 60 words.', 'basic', 'Approved Streamline Connect quote, up to approximately 60 words.' );
	$add( 'text', 'case_streamline_name', 'Streamline Connect name', 'Name' );
	$add( 'text', 'case_streamline_position', 'Streamline Connect position', 'Position' );
	$add( 'text', 'case_streamline_organisation', 'Streamline Connect organisation', 'Organisation' );

	$add( 'tab', '', 'Approval' );

	return $items;
}

/**
 * Snapshot rows: label, then value.
 *
 * @return array<int, array<string, string>>
 */
function slc_case_snapshot() {
	$rows = array(
		array(
			'label_field' => 'case_industry_label',
			'label'       => __( 'Industry', 'streamlineconnect' ),
			'value_field' => '',
			'value'       => '',
			'terms'       => true,
		),
		array(
			'label_field' => 'case_client_label',
			'label'       => __( 'Client', 'streamlineconnect' ),
			'value_field' => 'case_client',
			'value'       => __( 'Company name', 'streamlineconnect' ),
		),
		array(
			'label_field' => 'case_project_label',
			'label'       => __( 'Project', 'streamlineconnect' ),
			'value_field' => 'case_project',
			'value'       => __( 'Project name and reference', 'streamlineconnect' ),
		),
		array(
			'label_field' => 'case_location_label',
			'label'       => __( 'Location', 'streamlineconnect' ),
			'value_field' => 'case_location',
			'value'       => __( 'Project location', 'streamlineconnect' ),
		),
	);

	foreach ( $rows as &$row ) {
		$row['label'] = slc_plain( $row['label_field'], $row['label'] );
		if ( ! empty( $row['terms'] ) ) {
			$row['terms'] = slc_project_categories( get_the_ID() ? get_the_ID() : get_queried_object_id() );
			continue;
		}
		$row['value'] = slc_plain( $row['value_field'], $row['value'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Three project photographs. Empty slots keep the template prompt.
 *
 * @return array<int, array<string, string>>
 */
function slc_case_images() {
	$rows = array();

	foreach ( array( 1, 2, 3 ) as $image_number ) {
		$rows[] = array(
			'field'       => 'case_image_' . $image_number,
			'alt'         => slc_plain( 'case_image_' . $image_number . '_alt', __( 'Project image', 'streamlineconnect' ) . ' ' . $image_number ),
			'placeholder' => __( 'Insert image', 'streamlineconnect' ) . ' ' . $image_number,
		);
	}

	return $rows;
}

/**
 * Narrative sections between the images and the quotes.
 *
 * @return array<int, array<string, mixed>>
 */
function slc_case_sections() {
	$rows = array(
		array(
			'anchor'        => 'background',
			'heading_field' => 'case_background_heading',
			'heading'       => __( 'Background', 'streamlineconnect' ),
			'body_field'    => 'case_background',
			'body'          => __( 'Briefly explain who the client is, the operating context and what the client needed to achieve.', 'streamlineconnect' ),
			'grey'          => true,
			'detail'        => false,
		),
		array(
			'anchor'        => 'the_challenge',
			'heading_field' => 'case_challenge_heading',
			'heading'       => __( 'The challenge', 'streamlineconnect' ),
			'body_field'    => 'case_challenge',
			'body'          => __( 'Describe the delivery challenge, constraints, risks and why Streamline Connect was selected or suited to assist.', 'streamlineconnect' ),
			'grey'          => false,
			'detail'        => false,
		),
		array(
			'anchor'        => 'the_solution',
			'heading_field' => 'case_solution_heading',
			'heading'       => __( 'The solution', 'streamlineconnect' ),
			'body_field'    => 'case_solution',
			'body'          => __( 'Explain what Streamline Connect designed, supplied, manufactured, constructed, installed, tested or commissioned.', 'streamlineconnect' ),
			'grey'          => true,
			'detail'        => true,
		),
		array(
			'anchor'        => 'outcomes',
			'heading_field' => 'case_outcomes_heading',
			'heading'       => __( 'Outcomes', 'streamlineconnect' ),
			'body_field'    => 'case_outcomes',
			'body'          => __( 'State measurable or verifiable results: safety, programme, operational reliability, reduced risk, improved connectivity or client value.', 'streamlineconnect' ),
			'grey'          => false,
			'detail'        => false,
		),
	);

	foreach ( $rows as &$row ) {
		$row['heading'] = slc_plain( $row['heading_field'], $row['heading'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Solution detail rows.
 *
 * @return array<int, array<string, string>>
 */
function slc_case_details() {
	$rows = array(
		array(
			'label_field' => 'case_programme_label',
			'label'       => __( 'Programme', 'streamlineconnect' ),
			'body_field'  => 'case_programme',
			'body'        => __( 'What delivery timeframe or key milestones applied?', 'streamlineconnect' ),
		),
		array(
			'label_field' => 'case_package_label',
			'label'       => __( 'Delivery package', 'streamlineconnect' ),
			'body_field'  => 'case_package',
			'body'        => __( 'What package was tailored for the client?', 'streamlineconnect' ),
		),
		array(
			'label_field' => 'case_technical_label',
			'label'       => __( 'Technical highlight', 'streamlineconnect' ),
			'body_field'  => 'case_technical',
			'body'        => __( 'What technical achievement, innovation or difficult interface can be evidenced?', 'streamlineconnect' ),
		),
		array(
			'label_field' => 'case_scope_label',
			'label'       => __( 'Scope boundaries', 'streamlineconnect' ),
			'body_field'  => 'case_scope',
			'body'        => __( 'What was delivered by Streamline Connect and what remained by others?', 'streamlineconnect' ),
		),
	);

	foreach ( $rows as &$row ) {
		$row['label'] = slc_plain( $row['label_field'], $row['label'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Client and Streamline Connect quotations.
 *
 * @return array<int, array<string, string>>
 */
function slc_case_voices() {
	$quote = __( 'Insert an approved quote of up to approximately 60 words.', 'streamlineconnect' );
	$rows  = array(
		array(
			'label_field'         => 'case_client_quote_label',
			'label'               => __( 'Client quote', 'streamlineconnect' ),
			'quote_field'         => 'case_client_quote',
			'quote'               => $quote,
			'name_field'          => 'case_client_name',
			'name'                => __( 'Name', 'streamlineconnect' ),
			'position_field'      => 'case_client_position',
			'position'            => __( 'Position', 'streamlineconnect' ),
			'organisation_field'  => 'case_client_organisation',
			'organisation'        => __( 'Organisation', 'streamlineconnect' ),
		),
		array(
			'label_field'         => 'case_streamline_quote_label',
			'label'               => __( 'Streamline Connect quote', 'streamlineconnect' ),
			'quote_field'         => 'case_streamline_quote',
			'quote'               => $quote,
			'name_field'          => 'case_streamline_name',
			'name'                => __( 'Name', 'streamlineconnect' ),
			'position_field'      => 'case_streamline_position',
			'position'            => __( 'Position', 'streamlineconnect' ),
			'organisation_field'  => 'case_streamline_organisation',
			'organisation'        => __( 'Organisation', 'streamlineconnect' ),
		),
	);

	foreach ( $rows as &$row ) {
		$row['label']        = slc_plain( $row['label_field'], $row['label'] );
		$row['name']         = slc_plain( $row['name_field'], $row['name'] );
		$row['position']     = slc_plain( $row['position_field'], $row['position'] );
		$row['organisation'] = slc_plain( $row['organisation_field'], $row['organisation'] );
	}
	unset( $row );

	return $rows;
}

/**
 * Photograph, or the template prompt when the image field is empty.
 *
 * @param array<string, string> $image Image row from slc_case_images().
 * @return string
 */
function slc_case_figure( $image ) {
	$attachment = slc_attachment_id( slc_raw( $image['field'] ) );
	if ( $attachment && slc_attachment_exists( $attachment ) ) {
		return slc_picture(
			array(
				'field'   => $image['field'],
				'width'   => 1600,
				'height'  => 1067,
				'alt'     => $image['alt'],
				'attr_field' => $image['field'],
			)
		);
	}

	return sprintf(
		'<p%s>%s</p>',
		slc_attr_html( $image['field'], 'case-placeholder' ),
		esc_html( $image['placeholder'] )
	);
}

/**
 * Published projects, by title.
 *
 * @return WP_Post[]
 */
function slc_project_posts() {
	return get_posts(
		array(
			'post_type'      => 'project',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
}

/**
 * A project with this slug, including drafts and trash.
 *
 * @param string $slug Post name.
 * @return WP_Post|null
 */
function slc_project_by_slug( $slug ) {
	$posts = get_posts(
		array(
			'post_type'      => 'project',
			'name'           => $slug,
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'trash' ),
			'posts_per_page' => 1,
		)
	);

	return isset( $posts[0] ) && $posts[0] instanceof WP_Post ? $posts[0] : null;
}

/**
 * Turn the Western Turner page into a project, once.
 */
function slc_migrate_project_posts() {
	if ( '1' === get_option( 'slc_project_cpt' ) ) {
		return;
	}

	$page = get_page_by_path( 'projects/western-turner' );
	if ( ! $page instanceof WP_Post ) {
		$stored = (int) get_option( 'slc_western_turner_id' );
		if ( $stored && 'page' === get_post_type( $stored ) ) {
			$page = get_post( $stored );
		}
	}

	$project = slc_project_by_slug( 'western-turner' );
	if ( ! $project instanceof WP_Post ) {
		$created = wp_insert_post(
			array(
				'post_type'   => 'project',
				'post_status' => 'publish',
				'post_title'  => $page instanceof WP_Post ? $page->post_title : 'Western Turner',
				'post_name'   => 'western-turner',
				'post_author' => $page instanceof WP_Post ? (int) $page->post_author : 0,
			),
			true
		);
		if ( is_wp_error( $created ) || ! $created ) {
			return;
		}
		$project = get_post( (int) $created );
	}

	if ( ! $project instanceof WP_Post ) {
		return;
	}

	if ( $page instanceof WP_Post ) {
		slc_copy_project_meta( (int) $page->ID, (int) $project->ID );
		wp_delete_post( (int) $page->ID, true );
	} elseif ( function_exists( 'update_field' ) ) {
		$current = get_field( 'case_project', $project->ID );
		if ( ! is_string( $current ) || '' === trim( wp_strip_all_tags( $current ) ) ) {
			update_field( 'case_project', 'Western Turner', $project->ID );
		}
	}

	slc_strip_project_page_link();
	update_option( 'slc_western_turner_id', (int) $project->ID, false );
	update_option( 'slc_project_cpt', '1', false );
	flush_rewrite_rules( false );
}

/**
 * Copy case study values onto the project. Class and id meta stay behind.
 *
 * @param int $from Source post id.
 * @param int $to   Project id.
 */
function slc_copy_project_meta( $from, $to ) {
	$all = get_post_meta( $from );
	if ( ! is_array( $all ) ) {
		return;
	}

	foreach ( $all as $key => $values ) {
		if ( ! is_string( $key ) || ! is_array( $values ) ) {
			continue;
		}
		if ( in_array( $key, array( '_wp_page_template', '_edit_lock', '_edit_last' ), true ) ) {
			continue;
		}
		if ( preg_match( '/_(class|id)$/', ltrim( $key, '_' ) ) ) {
			continue;
		}
		foreach ( $values as $value ) {
			if ( ! get_post_meta( $to, $key, true ) ) {
				add_post_meta( $to, $key, $value );
			}
		}
	}
}

/**
 * The Projects page lists projects itself, so the old sample link is removed.
 */
function slc_strip_project_page_link() {
	$projects = get_page_by_path( 'projects' );
	if ( ! $projects instanceof WP_Post ) {
		return;
	}

	$content = (string) $projects->post_content;
	$updated = preg_replace( '#<p>\s*<a[^>]*western-turner[^>]*>.*?</a>\s*</p>#i', '', $content );
	if ( ! is_string( $updated ) || $updated === $content ) {
		return;
	}

	wp_update_post(
		array(
			'ID'           => $projects->ID,
			'post_content' => $updated,
		)
	);
}

/**
 * Send the old page address to the project.
 */
function slc_redirect_legacy_project_url() {
	if ( ! is_404() ) {
		return;
	}

	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path    = trim( (string) wp_parse_url( $request, PHP_URL_PATH ), '/' );
	$home    = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( '' !== $home && 0 === strpos( $path, $home . '/' ) ) {
		$path = substr( $path, strlen( $home ) + 1 );
	}

	if ( ! preg_match( '#^projects/([^/]+)/?$#', $path, $matches ) || 'industries' === $matches[1] ) {
		return;
	}

	$project = slc_project_by_slug( $matches[1] );
	if ( ! $project instanceof WP_Post || 'publish' !== $project->post_status ) {
		return;
	}

	wp_safe_redirect( get_permalink( $project ), 301 );
	exit;
}
