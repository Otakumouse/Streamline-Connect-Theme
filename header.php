<?php
/**
 * Site header and primary navigation.
 *
 * @package StreamlineConnect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$slc_menu = slc_capability_menu();
?><!DOCTYPE html>
<html<?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>document.documentElement.className=document.documentElement.className.replace('no-js','js');</script>
<?php wp_head(); ?>
</head>
<body<?php body_class(); ?>>
<?php wp_body_open(); ?>
<!-- Skip link: first focusable control, jumps to #main. -->
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'streamlineconnect' ); ?></a>
<!-- Header: logo, the mobile menu button, then the primary navigation. -->
<header class="site-header" id="top" tabindex="-1" data-header>
	<div class="header-bar">
		<a class="logo" href="<?php echo esc_url( slc_url( '/' ) ); ?>"><?php
			echo slc_picture(
				array(
					'field'      => 'header_logo',
					'file'       => 'images/logo.svg',
					'width'      => 240,
					'height'     => 31,
					'alt'        => 'Streamline Connect',
					'attr_field' => 'header_logo',
					'loading'    => 'eager',
				)
			);
		?></a>
		<button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="site_nav"><?php esc_html_e( 'Menu', 'streamlineconnect' ); ?></button>
		<!-- Primary nav: menu Discuss button, each item, then the bar Discuss button. -->
		<nav class="site-nav" id="site_nav" aria-label="<?php esc_attr_e( 'Primary', 'streamlineconnect' ); ?>">
			<a<?php echo slc_attr_html( 'discuss_menu_label', 'btn nav-cta nav-cta--menu' ); ?> href="<?php echo esc_url( slc_discuss_url() ); ?>"><?php echo esc_html( slc_discuss_label( 'discuss_menu_label' ) ); ?><?php slc_icon( 'plus.svg' ); ?></a>
			<?php foreach ( slc_nav_items() as $item ) : ?>
				<?php if ( ! empty( $item['menu'] ) ) : ?>
					<div class="nav-group">
						<button<?php echo slc_attr_html( $item['field'], 'nav-button' ); ?> type="button" data-capabilities-toggle aria-expanded="false" aria-controls="capabilities_menu"><?php echo esc_html( $item['label'] ); ?><?php slc_icon( 'chevron.svg' ); ?></button>
						<!-- Capabilities menu: intro column, then one column per capability. -->
						<div class="mega" id="capabilities_menu" hidden>
							<div class="mega-intro">
								<p<?php echo slc_attr_html( 'menu_title', 'mega-title' ); ?>><?php echo slc_inline( 'menu_title', __( 'Capabilities', 'streamlineconnect' ) ); ?></p>
								<div<?php echo slc_attr_html( 'menu_intro' ); ?>><?php echo slc_rich( 'menu_intro', $slc_menu['intro'] ); ?></div>
								<a<?php echo slc_attr_html( 'menu_all_label', 'mega-all' ); ?> href="<?php echo esc_url( slc_url( '/capabilities/' ) ); ?>"><?php echo esc_html( slc_plain( 'menu_all_label', __( 'All capabilities', 'streamlineconnect' ) ) ); ?><?php slc_icon( 'arrow-link.svg' ); ?></a>
							</div>
							<?php foreach ( $slc_menu['columns'] as $column_index => $column ) : ?>
								<?php $column_number = $column_index + 1; // Capability column: heading, summary, then the child links. ?>
								<div class="mega-col">
									<a<?php echo slc_attr_html( 'menu_' . $column_number . '_title', 'mega-heading' ); ?> href="<?php echo esc_url( slc_href( 'menu_' . $column_number . '_url', $column['url'] ) ); ?>"><?php echo esc_html( slc_plain( 'menu_' . $column_number . '_title', $column['title'] ) ); ?></a>
									<div<?php echo slc_attr_html( 'menu_' . $column_number . '_summary' ); ?>><?php echo slc_rich( 'menu_' . $column_number . '_summary', $column['summary'] ); ?></div>
									<ul class="mega-list">
										<?php foreach ( $column['links'] as $link_index => $link ) : ?>
											<?php $link_field = 'menu_' . $column_number . '_link_' . ( $link_index + 1 ); ?>
											<li><a<?php echo slc_attr_html( $link_field ); ?> href="<?php echo esc_url( slc_href( $link_field . '_url', $link['url'] ) ); ?>"><span aria-hidden="true">›</span><?php echo esc_html( slc_plain( $link_field, $link['label'] ) ); ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php else : ?>
					<a<?php echo slc_attr_html( $item['field'], 'nav-link' . ( empty( $item['chevron'] ) ? ' nav-link--plain' : '' ) ); ?> href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?><?php
					if ( ! empty( $item['chevron'] ) ) {
						slc_icon( 'chevron.svg' );
					}
					?></a>
				<?php endif; ?>
			<?php endforeach; ?>
			<a<?php echo slc_attr_html( 'discuss_header_label', 'btn nav-cta nav-cta--bar' ); ?> href="<?php echo esc_url( slc_discuss_url() ); ?>"><?php echo esc_html( slc_discuss_label( 'discuss_header_label' ) ); ?><?php slc_icon( 'plus.svg' ); ?></a>
		</nav>
	</div>
</header>
<!-- Scrim: covers the page while the menu is open. -->
<div class="scrim" data-scrim hidden></div>
