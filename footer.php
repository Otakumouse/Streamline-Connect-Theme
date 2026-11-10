<?php
/**
 * Shared enquiry band and footer.
 *
 * @package StreamlineConnect
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- Enquiry band: headline, then the note and button. -->
<section class="enquiry" aria-labelledby="<?php echo esc_attr( slc_content_id( 'enquiry_heading', 'enquiry_heading' ) ); ?>">
	<div class="wrap enquiry-layout">
		<h2<?php echo slc_attr_html( 'enquiry_heading', '', 'enquiry_heading' ); ?> data-reveal style="--i:0"><?php echo slc_inline( 'enquiry_heading', __( 'Tell us about your project', 'streamlineconnect' ) ); ?></h2>
		<div class="enquiry-copy">
			<div<?php echo slc_attr_html( 'enquiry_body' ); ?> data-reveal style="--i:1"><?php echo slc_rich( 'enquiry_body', __( 'Tell us what you\'re planning, where the site is and the programme you\'re working on.', 'streamlineconnect' ) ); ?></div>
			<a<?php echo slc_attr_html( 'discuss_enquiry_label', 'btn' ); ?> data-reveal style="--i:2" href="<?php echo esc_url( slc_discuss_url() ); ?>"><?php echo esc_html( slc_discuss_label( 'discuss_enquiry_label', 'Discuss your project.' ) ); ?><?php slc_icon( 'plus.svg' ); ?></a>
		</div>
	</div>
</section>
<!-- Footer: brand column, then the link columns and contact. -->
<footer class="site-footer">
	<div class="wrap footer-top">
		<!-- Brand: logo, then the accreditation label and the three ISO marks. -->
		<div class="footer-brand">
			<a class="logo" href="<?php echo esc_url( slc_url( '/' ) ); ?>"><?php
				echo slc_picture(
					array(
						'field'      => 'footer_logo',
						'file'       => 'images/logo-footer.svg',
						'width'      => 240,
						'height'     => 31,
						'alt'        => 'Streamline Connect',
						'attr_field' => 'footer_logo',
					)
				);
			?></a>
			<div class="accreditations">
				<p<?php echo slc_attr_html( 'accreditations_label' ); ?>><?php echo esc_html( slc_plain( 'accreditations_label', __( 'Accreditations & licences', 'streamlineconnect' ) ) ); ?></p>
				<ul>
					<li><?php echo slc_picture( array( 'field' => 'iso_9001', 'file' => 'images/iso-9001.png', 'width' => 58, 'height' => 128, 'alt' => __( 'ISO 9001 quality management', 'streamlineconnect' ), 'attr_field' => 'iso_9001' ) ); ?></li>
					<li><?php echo slc_picture( array( 'field' => 'iso_45001', 'file' => 'images/iso-45001.png', 'width' => 58, 'height' => 128, 'alt' => __( 'ISO 45001 occupational health and safety', 'streamlineconnect' ), 'attr_field' => 'iso_45001' ) ); ?></li>
					<li><?php echo slc_picture( array( 'field' => 'iso_14001', 'file' => 'images/iso-14001.png', 'width' => 58, 'height' => 128, 'alt' => __( 'ISO 14001 environmental management', 'streamlineconnect' ), 'attr_field' => 'iso_14001' ) ); ?></li>
				</ul>
			</div>
		</div>
		<!-- Link columns from the sitemap, with contact as the last column. -->
		<div class="footer-columns">
			<?php foreach ( slc_footer_columns() as $column ) : ?>
				<nav class="footer-col" aria-label="<?php echo esc_attr( $column['heading'] ); ?>">
					<h2><?php echo esc_html( $column['heading'] ); ?></h2>
					<ul>
						<?php foreach ( $column['links'] as $link ) : ?>
							<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endforeach; ?>
			<div class="footer-col">
				<h2><?php esc_html_e( 'Contact', 'streamlineconnect' ); ?></h2>
				<ul>
					<li><a<?php echo slc_attr_html( 'footer_email' ); ?> href="mailto:<?php echo esc_attr( slc_plain( 'footer_email', 'hello@streamlineconnect.com.au' ) ); ?>"><?php echo esc_html( slc_plain( 'footer_email', 'hello@streamlineconnect.com.au' ) ); ?></a></li>
					<li<?php echo slc_attr_html( 'footer_location' ); ?>><?php echo esc_html( slc_plain( 'footer_location', __( 'Perth, Western Australia', 'streamlineconnect' ) ) ); ?></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- Legal bar: copyright, policy links, then back to top. -->
	<div class="legal">
		<p<?php echo slc_attr_html( 'footer_copyright', 'legal-copy' ); ?>><?php echo esc_html( slc_plain( 'footer_copyright', __( '© 2026 Streamline Connect Pty Ltd', 'streamlineconnect' ) ) ); ?></p>
		<a<?php echo slc_attr_html( 'legal_privacy' ); ?> href="<?php echo esc_url( slc_url( '/privacy-policy/' ) ); ?>"><?php echo esc_html( slc_plain( 'legal_privacy', __( 'Privacy Policy', 'streamlineconnect' ) ) ); ?></a>
		<a<?php echo slc_attr_html( 'legal_terms' ); ?> href="<?php echo esc_url( slc_url( '/terms/' ) ); ?>"><?php echo esc_html( slc_plain( 'legal_terms', __( 'Terms', 'streamlineconnect' ) ) ); ?></a>
		<a<?php echo slc_attr_html( 'legal_accessibility' ); ?> href="<?php echo esc_url( slc_url( '/accessibility/' ) ); ?>"><?php echo esc_html( slc_plain( 'legal_accessibility', __( 'Accessibility', 'streamlineconnect' ) ) ); ?></a>
		<a<?php echo slc_attr_html( 'legal_top' ); ?> href="#top"><?php echo esc_html( slc_plain( 'legal_top', __( 'Back to top ↑', 'streamlineconnect' ) ) ); ?></a>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
