<?php
/**
 * TNI Custom Functions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package gridbox
 * @subpackage tni
 * @since 0.1.0
 */

/**
 * Add Search to Main Nav
 *
 * @since 0.0.1
 */
function tni_add_top_search_menu( $items, $args ) {
    if ( 'main_nav' == $args->theme_location ) {
        $items .= '<li class="top-search-menu">'  . get_search_form( false ) . '</li>';
    }
    return $items;
}
//add_filter( 'wp_nav_menu_items', 'tni_add_top_search_menu', 10, 2 );

/**
 * Related Posts for Blog Posts
 *
 * @since 0.0.1
 */
add_filter( 'jetpack_relatedposts_filter_post_context', '__return_empty_string' );

/**
 * Modify Archive Title
 *
 * @since 1.0.0
 *
 * @uses get_the_archive_title filter
 *
 * @param  string $title
 * @return string $title
 */
function tni_modify_the_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>' ;
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    } else {
        $title = __( 'Archives', 'tni' );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'tni_modify_the_archive_title' );

/**
 * Include Blog Post Types on Author Pages
 *
 * @since 1.0.0
 *
 * @uses pre_get_posts filter
 *
 * @param  array $query
 * @return void
 */
function tni_custom_post_author_archive( $query ) {
    if ( !is_admin() && $query->is_author && $query->is_main_query() ) {
        $query->set( 'post_type', array( 'post', 'blogs' ) );
    }
}
add_action( 'pre_get_posts', 'tni_custom_post_author_archive' );

/**
 * Change Button Text
 * @param  array $settings
 * @return array $settings
 */
function tni_jetpack_infinite_scroll_button_text( $settings ) {

    if( is_post_type_archive( 'magazines' ) ) {
        $settings['text'] = __( 'Load More Issues', 'tni' );
    } else {
        $settings['text'] = __( 'Load More Articles', 'tni' );
    }
	return $settings;
}
add_filter( 'infinite_scroll_js_settings', 'tni_jetpack_infinite_scroll_button_text' );

/**
 * Disable Infinite Scroll on Blogs Listing
 *
 * @since 0.1.2
 *
 * @uses infinite_scroll_archive_supported filter
 */
function tni_remove_infinite_scroll_blogs_archive() {
	return current_theme_supports( 'infinite-scroll' ) && ( !is_post_type_archive( 'blogs' ) );
}
add_filter( 'infinite_scroll_archive_supported', 'tni_remove_infinite_scroll_blogs_archive' );


/**
 * Modify Date Display for Magazines
 * @param  obj $the_date
 * @param  string $d
 * @param  obj $post
 * @return obj modified date || $the_date
 */
function tni_filter_publish_dates( $the_date, $d, $post ) {
	if ( is_int( $post) ) {
		$post_id = $post;
	} else {
		$post_id = $post->ID;
	}

	if ( 'magazines' == get_post_type( $post_id ) )
		return date( 'F Y', strtotime( $the_date ) );

	return $the_date;
}
add_action( 'get_the_date', 'tni_filter_publish_dates', 10, 3 );

/**
 * Add Class to Nav Items
 *
 * @since 0.1.5
 *
 * @param  array $classes
 * @param  string $item
 * @return array $classes
 */
function tni_nav_class( $classes, $item ){
    $item_post_name = $item->post_name;
    if( !empty( $item_post_name ) ) {
        $classes[] = $item_post_name;
    }
    return $classes;
}
add_filter( 'nav_menu_css_class' , 'tni_nav_class' , 10 , 2 );

/**
 * Customize Related Post Options
 * Change number of posts
 * Hide date
 *
 * @since
 * @param  array $options
 * @return array $options modified
 */
function tni_jetpack_customize_related_posts( $options ) {
    $options['size'] = 4;
    $options['show_date'] = false;
    return $options;
}
add_filter( 'jetpack_relatedposts_filter_options', 'tni_jetpack_customize_related_posts' );

/**
 * Remove Auto-inserted Related Posts
 *
 * @since 0.0.6
 *
 * @uses Jetpack_RelatedPosts
 * @link https://jetpack.com/support/related-posts/customize-related-posts/
 *
 * @return void
 */
function tni_remove_jetpack_related_posts() {
    if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
        $jprp = Jetpack_RelatedPosts::init();
        $callback = array( $jprp, 'filter_add_target_to_dom' );
        remove_filter( 'the_content', $callback, 40 );
    }
}
add_filter( 'wp', 'tni_remove_jetpack_related_posts', 20 );

/**
 * Modify Default JetPack Related Posts Default Image
 * @param  array $media
 * @param  int $post_id
 * @param  array $args
 * @return array $media
 */
function tni_jetpack_related_posts_default_image( $media, $post_id, $args ) {
    if ( $media ) {
        return $media;
    } else {
        $permalink = get_permalink( $post_id );
        $image = get_stylesheet_directory_uri() . '/images/tni-placeholder.jpg';
        $url = apply_filters( 'jetpack_photon_url', $image );

        return array( array(
            'type'  => 'image',
            'from'  => 'custom_fallback',
            'src'   => esc_url( $url ),
            'href'  => $permalink,
        ) );
    }
}
add_filter( 'jetpack_images_get_images', 'tni_jetpack_related_posts_default_image', 10, 3 );

/**
 * Modify Image Markup
 * When image is inserted into post content without a caption, alter markup produced
 *
 * @since
 *
 * @param  string $html
 * @param  int $id
 * @param  string $caption
 * @param  string $title
 * @param  string $align
 * @param  string $url
 * @param  string $size
 * @param  string $alt
 * @return string $html
 */
function tni_modify_embedded_image_markup( $html, $id, $caption, $title, $align, $url, $size, $alt ) {
  if( current_theme_supports( 'html5' )  && ! $caption ) {
    $html = sprintf( '<figure class="single-post-thumbnail">%s</figure>',
      $html
    );
  }
  return $html;
}
add_filter( 'image_send_to_editor', 'tni_modify_embedded_image_markup', 10, 8 );

/**
 * Modify Post Thumbnail Markup
 *
 * @since
 *
 * @param  string $html
 * @param  int $id
 * @param  int $thumbnail_id
 * @param  string $size
 * @param  string $attr
 * @return string
 */
function tni_modify_thumbnail_markup( $html, $id, $thumbnail_id, $size, $attr ) {
  if( current_theme_supports( 'html5' ) ) {
    $html = sprintf( '<figure class="single-post-thumbnail">%s</figure>',
      $html
    );
  }
  return $html;
}
apply_filters( 'post_thumbnail_html', 'tni_modify_thumbnail_markup' );


/**
 * Modify Image Caption Markup
 * When image is inserted into post content with a caption, alter markup produced by caption shortcode
 *
 * @since
 *
 * @param  $empty
 * @param  array $attr
 * @param string $content
 * @return string $content
 */
function tni_modify_image_caption_markup( $empty, $attr, $content ){
	$attr = shortcode_atts( array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => ''
	), $attr );

	if ( 1 > (int) $attr['width'] || empty( $attr['caption'] ) ) {
		return '';
	}

	if ( $attr['id'] ) {
		$attr['id'] = 'id="' . esc_attr( $attr['id'] ) . '" ';
	}

	return '<figure ' . $attr['id']
	. 'class="single-post-thumbnail has-caption ' . esc_attr( $attr['align'] ) . '" '
	. 'style="max-width: ' . ( 10 + (int) $attr['width'] ) . 'px;">'
	. do_shortcode( $content )
	. '<figcaption class="wp-caption-text">' . $attr['caption'] . '</figcaption>'
	. '</figure>';
}
add_filter( 'img_caption_shortcode', 'tni_modify_image_caption_markup', 10, 3 );

/**
 * Add Filters to `meta_content`
 * Ensures that `meta_content` is return like `the_content`
 *
 * @since 0.0.1
 */
add_filter( 'meta_content', 'wptexturize'        );
add_filter( 'meta_content', 'convert_smilies'    );
add_filter( 'meta_content', 'convert_chars'      );
add_filter( 'meta_content', 'wpautop'            );
add_filter( 'meta_content', 'shortcode_unautop'  );
add_filter( 'meta_content', 'prepend_attachment' );
