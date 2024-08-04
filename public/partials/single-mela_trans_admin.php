<?php

/**
 * The template for displaying mela_trans_admin single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @since      1.0.0
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/public/partials
 * @author     Luca Visciola <info@melasistema.com>
 */

get_header();

do_action( 'melatransadmin_breadcrumbs' );

/* Start the Loop */
while ( have_posts() ) :
    the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <?php if ( is_singular() ) : ?>
                <?php the_title( '<h1 class="entry-title default-max-width">', '</h1>' ); ?>
            <?php else : ?>
                <?php the_title( sprintf( '<h2 class="entry-title default-max-width"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php  the_content();  ?>
        </div><!-- .entry-content -->

    </article><!-- #post-<?php the_ID(); ?> -->

<?php endwhile; // End of the loop.

get_footer();
