<?php
/**
 * The template for displaying the blog index (latest posts)
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tni
 */

get_header();

// Get Theme Options from Database.
$theme_options = gridbox_theme_options();

// Display Blog Title.
if ( '' !== $theme_options['blog_title'] ) : ?>

	<header class="page-header clearfix">

		<h1 class="blog-title page-title"><?php echo wp_kses_post( $theme_options['blog_title'] ); ?></h1>
		<p class="blog-description"><?php echo wp_kses_post( $theme_options['blog_description'] ); ?></p>

	</header>

<?php endif; ?>

	<section id="primary" class="content-archive content-area">
		<main id="main" class="site-main" role="main">

			<?php
			if ( have_posts() ) : ?>

				<div id="post-wrapper" class="post-wrapper clearfix">

					<?php $count = 1; ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php if( 1 == $count ) : ?>

                            <?php get_template_part( 'template-parts/content', 'featured' ); ?>

                        <?php elseif ( 5 == $count ) : ?>

                            <?php $post = get_posts(array('post_type'=>'magazines'))[1]; // should be 0, but testing ?>
						    <?php get_template_part( 'template-parts/content', 'latest-issue' ); ?>

						<?php else : ?>

						    <?php get_template_part( 'template-parts/content' ); ?>

                        <?php endif; ?>

						<?php $count++; ?>

					<?php endwhile; ?>

				</div>

				<?php gridbox_pagination(); ?>

			<?php
			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>

		</main><!-- #main -->

	</section><!-- #primary -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
