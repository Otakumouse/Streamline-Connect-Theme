<?php
/**
 * Interior pages.
 *
 * @package StreamlineConnect
 */

get_header();
?>
<main id="main" class="page-main">
	<!-- Interior page: title, then the classic editor content. -->
	<div class="wrap narrow">
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
	</div>
</main>
<?php
get_footer();
