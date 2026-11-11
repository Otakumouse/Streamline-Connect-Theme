<?php
/**
 * Project category archive.
 *
 * @package StreamlineConnect
 */

get_header();

$slc_term = get_queried_object();
?>
<main id="main" class="page-main">
	<div class="wrap narrow">
		<p class="case-kicker"><?php esc_html_e( 'Industry', 'streamlineconnect' ); ?></p>
		<h1><?php echo esc_html( $slc_term instanceof WP_Term ? $slc_term->name : '' ); ?></h1>
		<?php if ( $slc_term instanceof WP_Term && '' !== trim( $slc_term->description ) ) : ?>
			<div class="entry"><?php echo wp_kses_post( wpautop( $slc_term->description ) ); ?></div>
		<?php endif; ?>
		<?php if ( have_posts() ) : ?>
			<ul class="project-index">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li>
						<a class="project-index-title" href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
						<?php $slc_categories = slc_project_categories( get_the_ID() ); ?>
						<?php if ( $slc_categories ) : ?>
							<p class="project-index-cats">
								<?php foreach ( $slc_categories as $slc_category ) : ?>
									<a href="<?php echo esc_url( get_term_link( $slc_category ) ); ?>"><?php echo esc_html( $slc_category->name ); ?></a>
								<?php endforeach; ?>
							</p>
						<?php endif; ?>
					</li>
					<?php
				endwhile;
				?>
			</ul>
		<?php else : ?>
			<p class="entry"><?php esc_html_e( 'No projects in this category.', 'streamlineconnect' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
