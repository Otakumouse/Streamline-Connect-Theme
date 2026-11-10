<?php
/**
 * Missing page.
 *
 * @package StreamlineConnect
 */

get_header();
?>
<main id="main" class="page-main">
	<div class="wrap narrow">
		<h1><?php esc_html_e( 'Page not found', 'streamlineconnect' ); ?></h1>
		<p><?php esc_html_e( 'That page is not on the site. You can return home or talk to us about a project.', 'streamlineconnect' ); ?></p>
		<p><a class="text-link" href="<?php echo esc_url( slc_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'streamlineconnect' ); ?></a></p>
	</div>
</main>
<?php
get_footer();
