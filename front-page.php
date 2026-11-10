<?php
/**
 * Home page.
 *
 * Scroll reveal uses the Alpha Solar Tech pattern:
 * data-reveal          fade up when the block scrolls into view
 * data-reveal-on="load" fade up as the page opens (hero only)
 * style="--i:N"        stagger, 80ms times N
 *
 * @package StreamlineConnect
 */

get_header();
?>
<main id="main">
	<section class="hero" aria-label="<?php esc_attr_e( 'Introduction', 'streamlineconnect' ); ?>">
		<?php
		echo slc_picture(
			array(
				'field'         => 'hero_image',
				'file'          => 'images/hero.jpg',
				'width'         => 2752,
				'height'        => 1536,
				'alt'           => slc_plain( 'hero_image_alt', __( 'Tracked trencher laying cable beside a reel and site containers.', 'streamlineconnect' ) ),
				'class'         => 'hero-photo',
				'attr_field'    => 'hero_image',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
			)
		);
		?>
		<div class="hero-scrim" aria-hidden="true"></div>
		<div class="hero-body">
			<!-- Hero plays on load: marker, headline, supporting text, then the jump panel. -->
			<div class="hero-mark" data-reveal data-reveal-on="load" style="--i:0" aria-hidden="true">
				<img class="stripes" src="<?php echo esc_url( slc_asset( 'icons/stripes.svg' ) ); ?>" alt="">
			</div>
			<div class="hero-copy">
				<div class="hero-text">
					<h1<?php echo slc_attr_html( 'hero_heading', '', 'hero_heading' ); ?> data-reveal data-reveal-on="load" style="--i:1"><?php echo slc_inline( 'hero_heading', __( 'A more direct way to<br>deliver critical infrastructure', 'streamlineconnect' ) ); ?></h1>
					<div<?php echo slc_attr_html( 'hero_body', 'hero-intro' ); ?> data-reveal data-reveal-on="load" style="--i:2"><?php echo slc_rich( 'hero_body', __( 'Streamline Connect delivers communications networks, power systems and modular facilities using its own crews, specialist plant and manufacturing capability. You deal directly with people who understand the work on site and can make decisions about how it gets done.', 'streamlineconnect' ) ); ?></div>
				</div>
				<nav class="jump" data-reveal data-reveal-on="load" style="--i:3" aria-label="<?php esc_attr_e( 'On this page', 'streamlineconnect' ); ?>">
					<p<?php echo slc_attr_html( 'hero_jump_label' ); ?>><?php echo esc_html( slc_plain( 'hero_jump_label', __( 'On this page', 'streamlineconnect' ) ) ); ?></p>
					<ul>
						<?php foreach ( slc_jumps() as $jump ) : ?>
							<li>
								<a<?php echo slc_attr_html( $jump['field'] ); ?> href="<?php echo esc_url( $jump['url'] ); ?>">
									<?php echo esc_html( $jump['label'] ); ?>
									<?php slc_icon( 'arrow.svg' ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<a<?php echo slc_attr_html( 'discuss_hero_label', 'btn btn--block' ); ?> href="<?php echo esc_url( slc_discuss_url() ); ?>">
						<?php echo esc_html( slc_discuss_label( 'discuss_hero_label' ) ); ?>
						<?php slc_icon( 'plus.svg' ); ?>
					</a>
				</nav>
			</div>
		</div>
	</section>

	<!-- Where we deliver: heading, then each sector row in order. -->
	<section class="section sectors" aria-labelledby="where-we-deliver">
		<div class="wrap sectors-layout">
			<h2<?php echo slc_attr_html( 'sectors_heading', 'section-heading', 'where_we_deliver' ); ?> data-reveal style="--i:0" tabindex="-1"><?php echo slc_inline( 'sectors_heading', __( 'Where we deliver', 'streamlineconnect' ) ); ?></h2>
			<ul class="sector-list">
				<?php foreach ( slc_sectors() as $sector_index => $sector ) : ?>
					<li data-reveal style="--i:<?php echo esc_attr( (string) ( $sector_index + 1 ) ); ?>">
						<a class="sector" href="<?php echo esc_url( $sector['url'] ); ?>">
							<span<?php echo slc_attr_html( $sector['title_field'], 'sector-name' ); ?>><?php echo esc_html( $sector['title'] ); ?></span>
							<span<?php echo slc_attr_html( $sector['text_field'], 'sector-text' ); ?>><?php echo esc_html( $sector['text'] ); ?></span>
							<?php slc_icon( 'arrow-sector.svg' ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- What we deliver: heading, then the three capability cards. -->
	<section class="section section--grey capabilities" aria-labelledby="what-we-deliver">
		<div class="wrap">
			<h2<?php echo slc_attr_html( 'capabilities_heading', 'section-heading', 'what_we_deliver' ); ?> data-reveal style="--i:0" tabindex="-1"><?php echo slc_inline( 'capabilities_heading', __( 'What we deliver', 'streamlineconnect' ) ); ?></h2>
			<ul class="card-grid">
				<?php foreach ( slc_capabilities() as $card_index => $card ) : ?>
					<li class="card" data-reveal style="--i:<?php echo esc_attr( (string) ( $card_index + 1 ) ); ?>">
						<a class="card-media" href="<?php echo esc_url( $card['url'] ); ?>">
							<?php
							echo slc_picture(
								array(
									'field'      => $card['image_field'],
									'file'       => $card['image'],
									'width'      => 1200,
									'height'     => 800,
									'alt'        => $card['alt'],
									'attr_field' => $card['image_field'],
								)
							);
							?>
						</a>
						<div class="card-body">
							<h3<?php echo slc_attr_html( $card['title_field'] ); ?>><a href="<?php echo esc_url( $card['url'] ); ?>"><?php echo esc_html( $card['title'] ); ?></a></h3>
							<div<?php echo slc_attr_html( $card['text_field'] ); ?>><?php echo slc_rich( $card['text_field'], $card['text'] ); ?></div>
							<a<?php echo slc_attr_html( $card['link_field'], 'text-link' ); ?> href="<?php echo esc_url( $card['url'] ); ?>">
								<?php echo esc_html( $card['link'] ); ?>
								<?php slc_icon( 'arrow-link.svg' ); ?>
							</a>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- Why Streamline: heading, six tiles, then the stat strip. -->
	<section class="section section--navy why" aria-labelledby="why-streamline">
		<div class="wrap">
			<h2<?php echo slc_attr_html( 'why_heading', 'section-heading', 'why_streamline' ); ?> data-reveal style="--i:0" tabindex="-1"><?php echo slc_inline( 'why_heading', __( 'Why Streamline', 'streamlineconnect' ) ); ?></h2>
			<ol class="reason-grid">
				<?php foreach ( slc_reasons() as $reason_index => $reason ) : ?>
					<li data-reveal style="--i:<?php echo esc_attr( (string) ( $reason_index + 1 ) ); ?>">
						<p<?php echo slc_attr_html( $reason['index_field'], 'reason-index' ); ?>><span aria-hidden="true">›</span> <?php echo esc_html( $reason['index'] ); ?></p>
						<h3<?php echo slc_attr_html( $reason['title_field'] ); ?>><?php echo esc_html( $reason['title'] ); ?></h3>
						<div<?php echo slc_attr_html( $reason['text_field'] ); ?>><?php echo slc_rich( $reason['text_field'], $reason['text'] ); ?></div>
					</li>
				<?php endforeach; ?>
			</ol>
			<dl class="stats" data-reveal style="--i:7">
				<?php foreach ( slc_stats() as $stat ) : ?>
					<div>
						<dt<?php echo slc_attr_html( $stat['label_field'] ); ?>><?php echo esc_html( $stat['label'] ); ?></dt>
						<dd>
							<span<?php echo slc_attr_html( $stat['figure_field'], 'stat-figure' ); ?>><?php echo esc_html( $stat['figure'] ); ?></span>
							<?php if ( '' !== $stat['suffix'] ) : ?>
								<span<?php echo slc_attr_html( $stat['suffix_field'], 'stat-suffix' ); ?>><?php echo esc_html( $stat['suffix'] ); ?></span>
							<?php endif; ?>
						</dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	</section>

	<!-- How we deliver: heading and intro, then stages 01–06, then the enquiry bar. -->
	<section class="section delivery" aria-labelledby="how-we-deliver">
		<div class="wrap">
			<div class="section-intro">
				<h2<?php echo slc_attr_html( 'delivery_heading', 'section-heading', 'how_we_deliver' ); ?> data-reveal style="--i:0" tabindex="-1"><?php echo slc_inline( 'delivery_heading', __( 'How We Deliver', 'streamlineconnect' ) ); ?></h2>
				<div<?php echo slc_attr_html( 'delivery_intro' ); ?> data-reveal style="--i:1"><?php echo slc_rich( 'delivery_intro', __( 'From a defined construction package to a broader infrastructure project, Streamline involves the people responsible for delivery in planning the work. Our own crews undertake the core stages, supported by trusted contractors under our management where required. On projects involving both site construction and manufacturing, these activities proceed in parallel.', 'streamlineconnect' ) ); ?></div>
			</div>
			<ol class="stage-grid">
				<?php foreach ( slc_stages() as $stage_index => $stage ) : ?>
					<li class="stage" data-reveal style="--i:<?php echo esc_attr( (string) ( $stage_index + 2 ) ); ?>" tabindex="0">
						<div class="stage-top">
							<p>
								<span<?php echo slc_attr_html( $stage['index_field'], 'stage-index' ); ?>><?php echo esc_html( $stage['index'] ); ?></span>
								<?php if ( $stage['parallel'] ) : ?>
									<span class="parallel">
										<?php slc_icon( 'parallel.svg' ); ?>
										<?php esc_html_e( 'Runs in parallel', 'streamlineconnect' ); ?>
									</span>
								<?php endif; ?>
							</p>
							<?php slc_icon( 'arrow-stage.svg' ); ?>
						</div>
						<h3<?php echo slc_attr_html( $stage['title_field'] ); ?>><?php echo esc_html( $stage['title'] ); ?></h3>
						<div<?php echo slc_attr_html( $stage['text_field'] ); ?>><?php echo slc_rich( $stage['text_field'], $stage['text'] ); ?></div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
		<div class="section-bar" data-reveal style="--i:8">
			<a<?php echo slc_attr_html( 'discuss_delivery_label', 'bar-link' ); ?> href="<?php echo esc_url( slc_discuss_url() ); ?>">
				<?php echo esc_html( slc_discuss_label( 'discuss_delivery_label' ) ); ?>
				<?php slc_icon( 'plus-orange.svg' ); ?>
			</a>
		</div>
	</section>

	<!-- Related projects: heading and intro, then each project row. -->
	<section class="section section--grey projects" aria-labelledby="related-projects">
		<div class="wrap">
			<div class="section-intro">
				<h2<?php echo slc_attr_html( 'projects_heading', 'section-heading', 'related_projects' ); ?> data-reveal style="--i:0" tabindex="-1"><?php echo slc_inline( 'projects_heading', __( 'Related projects', 'streamlineconnect' ) ); ?></h2>
				<div<?php echo slc_attr_html( 'projects_intro' ); ?> data-reveal style="--i:1"><?php echo slc_rich( 'projects_intro', __( 'Clients keep our teams on between scopes and talk to us early about their next project.', 'streamlineconnect' ) ); ?></div>
			</div>
			<?php foreach ( slc_projects() as $project_index => $project ) : ?>
				<?php
				$pdf_path = get_theme_file_path( 'assets/documents/' . $project['pdf'] );
				$pdf_url  = slc_asset( 'documents/' . $project['pdf'] );
				?>
				<article class="project" data-reveal style="--i:<?php echo esc_attr( (string) ( $project_index + 2 ) ); ?>">
					<?php
					echo slc_picture(
						array(
							'field'      => $project['image_field'],
							'file'       => $project['image'],
							'width'      => 1600,
							'height'     => 1067,
							'alt'        => $project['alt'],
							'attr_field' => $project['image_field'],
						)
					);
					?>
					<div class="project-copy">
						<h3<?php echo slc_attr_html( $project['title_field'] ); ?>><?php echo esc_html( $project['title'] ); ?></h3>
						<ul class="chips">
							<?php foreach ( $project['chips'] as $chip_index => $chip ) : ?>
								<li<?php echo slc_attr_html( $project['chip_fields'][ $chip_index ] ); ?>><?php echo esc_html( $chip ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="project-spec">
						<dl>
							<div>
								<dt><?php esc_html_e( 'Sector', 'streamlineconnect' ); ?></dt>
								<dd<?php echo slc_attr_html( $project['sector_field'] ); ?>><?php echo esc_html( $project['sector'] ); ?></dd>
							</div>
							<div>
								<dt><?php esc_html_e( 'Client', 'streamlineconnect' ); ?></dt>
								<dd<?php echo slc_attr_html( $project['client_field'] ); ?>><?php echo esc_html( $project['client'] ); ?></dd>
							</div>
							<div>
								<dt><?php esc_html_e( 'Location', 'streamlineconnect' ); ?></dt>
								<dd<?php echo slc_attr_html( $project['location_field'] ); ?>><?php echo esc_html( $project['location'] ); ?></dd>
							</div>
						</dl>
						<?php if ( file_exists( $pdf_path ) ) : ?>
							<a class="text-link" href="<?php echo esc_url( $pdf_url ); ?>" download>
								<?php esc_html_e( 'Download project PDF', 'streamlineconnect' ); ?>
								<?php slc_icon( 'arrow-link.svg' ); ?>
							</a>
						<?php else : ?>
							<p class="pdf-pending"><?php esc_html_e( 'Project PDF to be added.', 'streamlineconnect' ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<div class="section-bar" data-reveal style="--i:4">
			<a<?php echo slc_attr_html( 'projects_link_label', 'bar-link' ); ?> href="<?php echo esc_url( slc_url( '/projects/' ) ); ?>">
				<?php echo esc_html( slc_plain( 'projects_link_label', __( 'Explore our projects.', 'streamlineconnect' ) ) ); ?>
				<?php slc_icon( 'plus-orange.svg' ); ?>
			</a>
		</div>
	</section>

	<!-- Clients: heading, then the logo grid. -->
	<section class="section clients" aria-labelledby="clients-heading">
		<div class="wrap clients-layout">
			<h2<?php echo slc_attr_html( 'clients_heading', '', 'clients_heading' ); ?> data-reveal style="--i:0"><?php echo slc_inline( 'clients_heading', __( 'Trusted by operators, accredited to deliver', 'streamlineconnect' ) ); ?></h2>
			<div class="client-proof" data-reveal style="--i:1">
				<p<?php echo slc_attr_html( 'clients_label' ); ?>><?php echo esc_html( slc_plain( 'clients_label', __( 'Selected clients', 'streamlineconnect' ) ) ); ?></p>
				<ul class="logo-grid">
					<?php foreach ( slc_clients() as $client ) : ?>
						<li>
							<?php
							echo slc_picture(
								array(
									'field'      => $client['field'],
									'file'       => $client['file'],
									'width'      => $client['width'],
									'height'     => $client['height'],
									'alt'        => $client['name'],
									'attr_field' => $client['field'],
								)
							);
							?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>

	<!-- Built here: statement on the left, photograph and product links on the right. -->
	<section class="section section--navy built" aria-labelledby="built-heading">
		<div class="wrap built-layout">
			<div class="built-copy" data-reveal style="--i:0">
				<h2<?php echo slc_attr_html( 'built_heading', '', 'built_heading' ); ?>><?php echo slc_inline( 'built_heading', __( 'Built here.<br>Delivered with confidence.', 'streamlineconnect' ) ); ?></h2>
				<div<?php echo slc_attr_html( 'built_body' ); ?>><?php echo slc_rich( 'built_body', __( 'At our Embleton factory in Western Australia, you can inspect your Data Room and witness Factory Acceptance Testing. The people who assembled it are on hand to investigate and resolve issues before dispatch. Our site crews can prepare the foundations and connections while the factory team completes your room.', 'streamlineconnect' ) ); ?></div>
				<a<?php echo slc_attr_html( 'built_link_label', 'text-link text-link--light' ); ?> href="<?php echo esc_url( slc_href( 'built_link_url', slc_url( '/capabilities/modular-infrastructure/#design_manufacturing_and_delivery' ) ) ); ?>">
					<?php echo esc_html( slc_plain( 'built_link_label', __( 'See how we manufacture and deliver.', 'streamlineconnect' ) ) ); ?>
					<?php slc_icon( 'plus.svg' ); ?>
				</a>
			</div>
			<div class="built-aside" data-reveal style="--i:1">
				<?php
				echo slc_picture(
					array(
						'field'      => 'built_image',
						'file'       => 'images/built-here.png',
						'width'      => 3496,
						'height'     => 2752,
						'alt'        => slc_plain( 'built_image_alt', __( 'Fabrication bay at the Embleton factory, with a welder at work.', 'streamlineconnect' ) ),
						'attr_field' => 'built_image',
					)
				);
				?>
				<ul class="product-links">
					<?php foreach ( slc_products() as $product ) : ?>
						<li>
							<a href="<?php echo esc_url( $product['url'] ); ?>">
								<span>
									<strong<?php echo slc_attr_html( $product['title_field'] ); ?>><?php echo esc_html( $product['title'] ); ?></strong>
									<span<?php echo slc_attr_html( $product['text_field'] ); ?>><?php echo esc_html( $product['text'] ); ?></span>
								</span>
								<?php slc_icon( 'arrow-light.svg' ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
