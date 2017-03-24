<?php
/**
 * TNI Set-up Functions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package gridbox
 * @subpackage tni
 * @since 0.1.0
 */

function tni_setup() {
    register_sidebar( array(
        'name' => esc_html__( 'Header', 'tni' ),
        'id' => 'header',
        'description' => esc_html__( 'Header widget', 'tni' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div><!-- .header -->',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
        )
    );

    register_sidebar( array(
        'name' => esc_html__( 'Footer', 'tni' ),
        'id' => 'footer',
        'description' => esc_html__( 'Footer widget', 'tni' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div><!-- .footer -->',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
        )
    );

    register_sidebar( array(
        'name' => esc_html__( 'Copyright', 'tni' ),
        'id' => 'copyleft',
        'description' => esc_html__( 'Copyright information in footer', 'tni' ),
        'before_widget' => '<div id="%1$s" class="copyright">',
        'after_widget' => '</div>',
        'before_title' => '<div class="screen-reader-text">',
        'after_title' => '</div>'
        )
    );

    register_sidebar( array(
        'name' => esc_html__( 'Above Page', 'tni' ),
        'id' => 'page-header',
        'description' => esc_html__( 'Displays above pages. Can be used for vertical ad space or other purposes.', 'tni' ),
        'before_widget' => '<div id="%1$s" class="page-header-widget container">',
        'after_widget' => '</div>',
        'before_title' => '<h3 title="widget-title">',
        'after_title' => '</h3>'
        )
    );

    register_sidebar( array(
        'name' => esc_html__( 'Below Content', 'tni' ),
        'id' => 'content-footer',
        'description' => esc_html__( 'Displays under articles. Can be used as a promo space.', 'tni' ),
        'before_widget' => '<div id="%1$s" class="article-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 title="widget-title">',
        'after_title' => '</h3>'
        )
    );

    register_sidebar( array(
        'name' => esc_html__( 'Magazine Sidebar', 'tni' ),
        'id' => 'magazine-single',
        'description' => esc_html__( 'Displays on single magazine issues.', 'tni' ),
        'before_widget' => '<div id="%1$s" class="page-header-widget container">',
        'after_widget' => '</div>',
        'before_title' => '<h3 title="widget-title">',
        'after_title' => '</h3>'
        )
    );

    remove_theme_support( 'infinite-scroll' );

    add_theme_support( 'infinite-scroll', array(
      'type'           => 'click',
      'container'      => 'post-wrapper',
      'footer_widgets' => 'footer',
      'wrapper'        => false,
      'render'         => 'gridbox_infinite_scroll_render',
      'posts_per_page' => 8,
  	) );

    register_nav_menus( array(
    	'social'   => esc_html__( 'Social Menu', 'tni' ),
      'mobile'   => esc_html__( 'Mobile Menu', 'tni' ),
    	'footer'   => esc_html__( 'Footer Menu', 'tni' ),
    ) );

    load_theme_textdomain( 'tni', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'tni_setup', 20 );
