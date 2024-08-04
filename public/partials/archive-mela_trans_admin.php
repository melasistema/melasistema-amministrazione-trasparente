<?php

/**
 * Template archive for custom_post_type "mela_trans_admin".
 *
 * @link       https://lucavisciola.com
 *
 * @since      1.0.0
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/public/partials
 * @author     Luca Visciola <info@melasistema.com>
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
/**
 * @return false|string|void
 */
function build_category_list() {
	$top_level_terms = get_terms(array(
		'taxonomy' => 'mela_trans_section',
		'hide_empty' => true,
		'parent' => 0,
	));

	if (empty($top_level_terms)) {
		return;
	}

	ob_start(); ?>
    <ul>
		<?php foreach ($top_level_terms as $term) : ?>
            <li>
                <a href="#category-<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></a>
				<?php
				$children = get_terms(array(
					'taxonomy' => 'mela_trans_section',
					'hide_empty' => true,
					'parent' => $term->term_id,
				));
				if (!empty($children)) : ?>
                    <ul>
						<?php foreach ($children as $child) : ?>
                            <li>
                                <a href="#category-<?php echo esc_attr($child->slug); ?>"><?php echo esc_html($child->name); ?></a>
                            </li>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </li>
		<?php endforeach; ?>
    </ul>
	<?php
	return ob_get_clean();
}

/**
 * @return false|string|void
 */
function build_category_content() {
	$top_level_terms = get_terms(array(
		'taxonomy' => 'mela_trans_section',
		'hide_empty' => true,
		'parent' => 0,
	));

	if (empty($top_level_terms)) {
		return;
	}

	ob_start();
	foreach ($top_level_terms as $term) {
		echo create_category_content_item($term);
	}
	return ob_get_clean();
}

/**
 * @param $term
 *
 * @return string
 */
function create_category_content_item($term): string {
	$output = '';
	$term_slug = esc_attr($term->slug);
	$anchor_id = 'category-' . $term_slug;

	$output .= '<h4 id="' . $anchor_id . '">' . esc_html($term->name) . '</h4>';

    if ($term->description) {
	    $output .= '<p class="mela-trans-section-description">' . esc_html($term->description) . '</p>';
    }

	$post_args = array(
		'post_type' => 'mela_trans_admin',
		'tax_query' => array(
			array(
				'taxonomy' => 'mela_trans_section',
				'field' => 'id',
				'terms' => $term->term_id,
			),
		),
	);

	$post_query = new WP_Query($post_args);

	if ($post_query->have_posts()) :
		$output .= '<ul>';
		while ($post_query->have_posts()) :
			$post_query->the_post();

			// Check if the custom field 'mela_trans_pdf_file' exists and has a value
			$pdf_file = get_post_meta(get_the_ID(), 'mela_trans_pdf_file', true);

			if (!empty($pdf_file)) :
				$icon_class = 'pdf-icon';
				$link = $pdf_file;
				$target = '_blank';
			else :
				$icon_class = 'custom-icon'; // Replace with your desired icon class
				$link = get_the_permalink();
				$target = '';
			endif;

			$output .= '<li class="' . $icon_class . '"><a href="' . $link . '" ' . ($target ? 'target="' . $target . '"' : '') . '>' . get_the_title() . '</a></li>';

		endwhile;
		$output .= '</ul>';
	endif;

	wp_reset_postdata();

	$child_terms = get_terms(array(
		'taxonomy' => 'mela_trans_section',
		'hide_empty' => false,
		'parent' => $term->term_id,
	));

	if (!empty($child_terms)) :
		$output .= '<ul>';
		foreach ($child_terms as $child_term) :
			$output .= '<li class="mela-trans-sublevel-section-heading">';
			$output .= create_category_content_item($child_term);
			$output .= '</li>';
		endforeach;
		$output .= '</ul>';
	endif;

	return $output;
}

echo get_template_part( 'header' );  ?>

    <div class="container-wrap">
		<?php do_action('melatransadmin_breadcrumbs'); ?>
        <div class="container main-content flex-container mela-trans-admin-wrapper">
            <div class="index-column mela-trans-summary">
                <h4><?php _e('Index', 'melasistema-amministrazione-trasparente'); ?></h4>
				<?php echo build_category_list(); ?>
            </div>
            <div class="content-column mela-trans-content">
				<?php echo build_category_content(); ?>
            </div>
        </div>
    </div>

<?php echo get_template_part( 'footer' ); ?>

