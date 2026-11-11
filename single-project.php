<?php
/**
 * Single project case study.
 *
 * The enquiry band and footer follow the project.
 *
 * @package StreamlineConnect
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
<main id="main" class="case-study">
	<!-- Introduction: marker, document label, outcome heading, then the tagline. -->
	<section class="section case-intro" aria-labelledby="<?php echo esc_attr( slc_content_id( 'case_heading', 'case_heading' ) ); ?>">
		<div class="wrap case-intro-copy">
			<div class="hero-mark" data-reveal style="--i:0" aria-hidden="true"><img class="stripes" src="<?php echo esc_url( slc_asset( 'icons/stripes.svg' ) ); ?>" alt=""></div>
			<p<?php echo slc_attr_html( 'case_kicker', 'case-kicker' ); ?> data-reveal style="--i:1"><?php echo esc_html( slc_plain( 'case_kicker', __( 'Project case study', 'streamlineconnect' ) ) ); ?></p>
			<h1<?php echo slc_attr_html( 'case_heading', 'section-heading', 'case_heading' ); ?> data-reveal style="--i:2"><?php echo slc_inline( 'case_heading', __( 'Use a clear, outcome-led heading of approximately 16 to 18 words.', 'streamlineconnect' ) ); ?></h1>
			<p<?php echo slc_attr_html( 'case_tagline', 'case-tagline' ); ?> data-reveal style="--i:3"><?php echo esc_html( slc_plain( 'case_tagline', __( 'Practical delivery. Proven capability. Connected outcomes.', 'streamlineconnect' ) ) ); ?></p>
		</div>
	</section>
	<!-- Snapshot: heading, then industry, client, project, and location. -->
	<section class="section section--grey" aria-labelledby="<?php echo esc_attr( slc_content_id( 'case_snapshot_heading', 'project_snapshot' ) ); ?>">
		<div class="wrap">
			<h2<?php echo slc_attr_html( 'case_snapshot_heading', 'section-heading', 'project_snapshot' ); ?> data-reveal style="--i:0"><?php echo esc_html( slc_plain( 'case_snapshot_heading', __( 'Project snapshot', 'streamlineconnect' ) ) ); ?></h2>
			<div class="project-spec" data-reveal style="--i:1">
				<dl>
					<?php foreach ( slc_case_snapshot() as $row ) : ?>
						<?php if ( isset( $row['terms'] ) && ! $row['terms'] ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<div><dt<?php echo slc_attr_html( $row['label_field'] ); ?>><?php echo esc_html( $row['label'] ); ?></dt><dd<?php echo $row['value_field'] ? slc_attr_html( $row['value_field'] ) : ''; ?>><?php
						if ( isset( $row['terms'] ) ) :
							$slc_first = true;
							foreach ( $row['terms'] as $slc_term ) :
								if ( ! $slc_first ) {
									echo ' / ';
								}
								$slc_first = false;
								?><a href="<?php echo esc_url( get_term_link( $slc_term ) ); ?>"><?php echo esc_html( $slc_term->name ); ?></a><?php
							endforeach;
						else :
							echo esc_html( $row['value'] );
						endif;
						?></dd></div>
					<?php endforeach; ?>
				</dl>
			</div>
		</div>
	</section>
	<!-- Photographs: three frames. An empty frame keeps the insert prompt. -->
	<section class="section" aria-labelledby="<?php echo esc_attr( slc_content_id( 'case_images_heading', 'project_images' ) ); ?>">
		<div class="wrap">
			<h2<?php echo slc_attr_html( 'case_images_heading', 'section-heading', 'project_images' ); ?> data-reveal style="--i:0"><?php echo esc_html( slc_plain( 'case_images_heading', __( 'Project images', 'streamlineconnect' ) ) ); ?></h2>
			<ul class="card-grid case-gallery">
				<?php foreach ( slc_case_images() as $image_index => $image ) : ?>
					<li data-reveal style="--i:<?php echo esc_attr( (string) ( $image_index + 1 ) ); ?>"><figure class="case-frame"><?php echo slc_case_figure( $image ); ?></figure></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php foreach ( slc_case_sections() as $section ) : ?>
		<!-- Narrative section: heading, then the approved copy. The solution also carries the detail grid. -->
		<section class="section<?php echo $section['grey'] ? ' section--grey' : ''; ?>" aria-labelledby="<?php echo esc_attr( slc_content_id( $section['heading_field'], $section['anchor'] ) ); ?>">
			<div class="wrap">
				<div class="case-copy">
					<h2<?php echo slc_attr_html( $section['heading_field'], 'section-heading', $section['anchor'] ); ?> data-reveal style="--i:0"><?php echo esc_html( $section['heading'] ); ?></h2>
					<div<?php echo slc_attr_html( $section['body_field'], 'case-prose' ); ?> data-reveal style="--i:1"><?php echo slc_rich( $section['body_field'], $section['body'] ); ?></div>
				</div>
				<?php if ( ! empty( $section['detail'] ) ) : ?>
					<div class="case-detail">
						<h2<?php echo slc_attr_html( 'case_detail_heading', 'section-heading', 'solution_detail' ); ?> data-reveal style="--i:2"><?php echo esc_html( slc_plain( 'case_detail_heading', __( 'Solution detail', 'streamlineconnect' ) ) ); ?></h2>
						<ul class="case-detail-grid">
							<?php foreach ( slc_case_details() as $detail_index => $detail ) : ?>
								<li data-reveal style="--i:<?php echo esc_attr( (string) ( $detail_index + 3 ) ); ?>">
									<h3<?php echo slc_attr_html( $detail['label_field'] ); ?>><?php echo esc_html( $detail['label'] ); ?></h3>
									<div<?php echo slc_attr_html( $detail['body_field'] ); ?>><?php echo slc_rich( $detail['body_field'], $detail['body'] ); ?></div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endforeach; ?>
	<!-- Quotations: client, then Streamline Connect. Each voice is the quote and the attribution. -->
	<section class="section section--navy case-voices" aria-labelledby="<?php echo esc_attr( slc_content_id( 'case_voices_heading', 'project_voices' ) ); ?>">
		<div class="wrap">
			<h2<?php echo slc_attr_html( 'case_voices_heading', 'section-heading', 'project_voices' ); ?> data-reveal style="--i:0"><?php echo esc_html( slc_plain( 'case_voices_heading', __( 'Project voices', 'streamlineconnect' ) ) ); ?></h2>
			<div class="voice-grid">
				<?php foreach ( slc_case_voices() as $voice_index => $voice ) : ?>
					<blockquote data-reveal style="--i:<?php echo esc_attr( (string) ( $voice_index + 1 ) ); ?>">
						<p<?php echo slc_attr_html( $voice['label_field'], 'voice-label' ); ?>><?php echo esc_html( $voice['label'] ); ?></p>
						<div<?php echo slc_attr_html( $voice['quote_field'], 'voice-text' ); ?>><?php echo slc_rich( $voice['quote_field'], $voice['quote'] ); ?></div>
						<p class="voice-by"><span<?php echo slc_attr_html( $voice['name_field'] ); ?>><?php echo esc_html( $voice['name'] ); ?></span><span<?php echo slc_attr_html( $voice['position_field'] ); ?>><?php echo esc_html( $voice['position'] ); ?></span><span<?php echo slc_attr_html( $voice['organisation_field'] ); ?>><?php echo esc_html( $voice['organisation'] ); ?></span></p>
					</blockquote>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>
	<?php
endwhile;

get_footer();
