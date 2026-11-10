<?php
/**
 * Industries page. Home sector rows land on these anchors.
 *
 * @package StreamlineConnect
 */

get_header();
?>
<main id="main" class="page-main">
	<!-- Industries: page title, optional editor content, then one anchored sector per industry. -->
	<div class="wrap narrow">
		<h1><?php the_title(); ?></h1>
		<?php
		while ( have_posts() ) :
			the_post();
			if ( get_the_content() ) {
				echo '<div class="entry">';
				the_content();
				echo '</div>';
			}
		endwhile;
		?>
		<ul class="industry-list">
			<?php foreach ( slc_sectors() as $sector ) : // Sector: anchor for the home row, heading, then the summary. ?>
				<li id="<?php echo esc_attr( $sector['id'] ); ?>" tabindex="-1"><h2><?php echo esc_html( $sector['title'] ); ?></h2><p><?php echo esc_html( $sector['text'] ); ?></p></li>
			<?php endforeach; ?>
		</ul>
	</div>
</main>
<?php
get_footer();
