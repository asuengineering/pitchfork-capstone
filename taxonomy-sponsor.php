<?php
/**
 * CAPSTONE: Sponsors
 * - Template file expressing sponsor details + project listing
 *
 * @package Pitchfork_Capstone
 */

get_header();
$term = get_queried_object();

$sponsor_logo = get_field('sponsor_image', $term);
$sponsor_url = get_field('sponsor_url', $term);
?>

<main id="skip-to-content" <?php post_class(); ?>>
	<header id="sponsor-header">
		<div class="subtitle"><span class="highlight-gold">Project sponsor</span></div>
		<h1 class="page-title entry-title"><?php echo $term->name; ?></h1>

		<?php
			$details = '<div class="details">';

			$description = get_the_archive_description();
			// $description = '';

			if ( $description ) {
				if ($sponsor_logo) {
					$details .= '<img class="img-fluid" src="' . esc_url( $sponsor_logo['url'] ) . '" alt="' . esc_attr( $sponsor_logo['alt'] ) . '" />';
				}

				$details .= '<div class="archive-description lead fw-normal">' . wp_kses_post( wpautop( $description )) . '</div>';

				if ($sponsor_url) {
					$details .= '<a class="sponsor-link btn btn-maroon" href="' . esc_html( $sponsor_url ) . '" target="_blank"><span class="fa-regular fa-globe-pointer"></span>Website</a>';
				}
			}

			$details .= '</div>';

			echo $details;
		?>
	</header>

	<?php
		$program_dates = get_terms(
			'program_date',
			array(
				'orderby' => 'ID',
				'order' => 'DESC',
			)
		);

		foreach ( $program_dates as $program_date )  {

			$program_slug = $program_date->slug;

			$related = new WP_Query(
				array(
					'post_type' => 'project',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'program_date',
							'field' => 'slug',
							'terms'    => $program_slug,
						),
						array(
							'taxonomy' => 'sponsor',
							'field'    => 'slug',
							'terms'    => $term->slug,
						),
					),
					'orderby' => '',
				)
			);

			if ( $related->have_posts() ) :
				?>

				<section class="related-projects alignfull">
					<div class="container">
						<div class="row mb-4">
							<div class="col-md-12">
								<h2><span class="highlight-black"><?php echo esc_html( $program_date->name ); ?></span></h2>
							</div>
						</div>
						<div class="row">

							<?php

							while ( $related->have_posts() ) :

								$related->the_post();
								get_template_part( 'template-parts/content-card' );

							endwhile;

							?>
						</div><!-- end .row -->
					</div><!-- end .container -->
				</section><!-- end #related-projects-->

				<?php

			endif;

		wp_reset_postdata();
		};

	?>

</main>

<?php
get_footer();
