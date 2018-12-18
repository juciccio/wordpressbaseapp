<?php

// Register Style
function custom_styles() {
	$cssFilePath = glob( get_template_directory() . '/assets/css/build/main.min.*' );
	$cssFileURI = get_template_directory_uri() . '/assets/css/build/' . basename($cssFilePath[0]);

    wp_register_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css', [], false, false );
	wp_register_style( 'custom_style', $cssFileURI, ['fontawesome'], false, false );

	wp_enqueue_style( 'custom_style' );
}

// Register Script
function custom_scripts() {
	$jsFilePath = glob( get_template_directory() . '/assets/js/build/app.min.*.js' );
    $jsFileURI = get_template_directory_uri() . '/assets/js/build/' . basename($jsFilePath[0]);
    
	wp_register_script( 'custom_script', $jsFileURI,
		['jquery'],
		false,
		false 
    );

	wp_enqueue_script('custom_script');
}

// Hook into the 'wp_enqueue_scripts' action
add_action( 'wp_enqueue_scripts', 'custom_styles' );

// Hook into the 'wp_enqueue_scripts' action
add_action( 'wp_enqueue_scripts', 'custom_scripts' );

/**
 * Registers required WP features and needed image sizes
 */
add_action('after_setup_theme', function(){
	// Images
	//
	add_theme_support('post-thumbnails');

	add_image_size('custom_post', 330, 220, TRUE);
	add_image_size('custom_post_big', 1600, 900, TRUE);

	// Menus
	//
	add_theme_support('menus');

	register_nav_menus(array(
		'header' => 'Menu at header'
	));
});

/**
 * Hide wordpress admin bar
 */
add_filter('show_admin_bar', '__return_false');

/**
 * Bootstrap 4.0.0-alpha2 nav walker extension class
 */

class bootstrap_4_walker_nav_menu extends Walker_Nav_menu {
    
    function start_lvl( &$output, $depth = 0, $args = [] ){ // ul
        $indent = str_repeat("\t",$depth); // indents the outputted HTML
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
    }
  
  	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){ // li a span
        
		$indent = ( $depth ) ? str_repeat("\t",$depth) : '';
		
		$li_attributes = '';
        $class_names = $value = '';
    
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
        $classes[] = ($item->current || $item->current_item_anchestor) ? 'active' : '';
        $classes[] = 'nav-item';
        $classes[] = 'nav-item-' . $item->ID;
        if( $depth && $args->walker->has_children ){
            $classes[] = 'dropdown-menu';
        }
        
        $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="' . esc_attr($class_names) . '"';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
        
        $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';
        
        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr($item->url) . '"' : '';
        
        $attributes .= ( $args->walker->has_children ) ? ' class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="nav-link"';
        
        $item_output = $args->before;
        $item_output .= ( $depth > 0 ) ? '<a class="dropdown-item"' . $attributes . '>' : '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}