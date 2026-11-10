<?php
/**
 * Fallback loop.
 *
 * @package StreamlineConnect
 */

get_header();
?>
<main id="main" class="page-main">
	<!-- Fallback: each post title and content, or the empty heading. -->
	<div class="wrap narrow">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article<?php post_class(); ?>>
					<h1><?php the_title(); ?></h1>
					<div class="entry"><?php the_content(); ?></div>
				</article>
				<?php
			endwhile;
			?>
		<?php else : ?>
			<h1><?php esc_html_e( 'Nothing found', 'streamlineconnect' ); ?></h1>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
