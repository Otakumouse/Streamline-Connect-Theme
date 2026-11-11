<?php
/**
 * Projects page. Lists project case studies.
 *
 * @package StreamlineConnect
 */

get_header();

$slc_projects = slc_project_posts();
?>
<main id="main" class="page-main">
	<div class="wrap narrow">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<h1><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="entry"><?php the_content(); ?></div>
			<?php endif; ?>
			<?php
		endwhile;

		if ( $slc_projects ) :
			?>
			<ul class="project-index">
				<?php foreach ( $slc_projects as $slc_project ) : ?>
					<li>
						<a class="project-index-title" href="<?php echo esc_url( get_permalink( $slc_project ) ); ?>"><?php echo esc_html( get_the_title( $slc_project ) ); ?></a>
						<?php $slc_categories = slc_project_categories( $slc_project->ID ); ?>
						<?php if ( $slc_categories ) : ?>
							<p class="project-index-cats">
								<?php foreach ( $slc_categories as $slc_category ) : ?>
									<a href="<?php echo esc_url( get_term_link( $slc_category ) ); ?>"><?php echo esc_html( $slc_category->name ); ?></a>
								<?php endforeach; ?>
							</p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;
		?>
	</div>
</main>
<?php
get_footer();
