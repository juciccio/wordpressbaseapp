<?php

// Register Style
function custom_styles() {
	wp_register_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.1.0/css/all.css', [], false, false );
  
	if (@file_get_contents('http://localhost:3000/')) {
	  wp_enqueue_style( 'fontawesome' );
	} else {
	  wp_register_style( 'custom_style', asset('app.css'), ['fontawesome'], false, false );
  
	  wp_enqueue_style( 'custom_style' );
	}
}

// Register Script
function custom_scripts() {
	if (@file_get_contents('http://localhost:3000/')) {
		wp_register_script( 'custom_script', 'http://localhost:3000/app/themes/custom-theme/assets/dist/app.js',
		['jquery'],
		false,
		false );
	} else {
		wp_register_script( 'custom_script', asset('app.js'),
		['jquery'],
		false,
		false );
	}

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

	add_image_size('hoo_post', 330, 220, TRUE);
	add_image_size('hoo_post_big', 1600, 900, TRUE);

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
 * Get the path to a versioned asset file.
 *
 * @param  string  $path
 * @param  string  $manifestDirectory
 * @return string
 *
 * @throws \Exception
 */
function asset($path, $manifestDirectory = '/app/themes/custom-theme/assets/dist/') {
	static $manifest;
  
	$assetPath = $path;
	$manifestPath = realpath(PUBLICPATH . $manifestDirectory) . '/manifest.json';

	if (!$manifest && file_exists($manifestPath)) {
		$manifest = json_decode(file_get_contents($manifestPath), true);
	}

	if ($manifest && array_key_exists($path, $manifest)) {
		$assetPath = $manifest[$path];
	}

	return WP_HOME . $manifestDirectory . $assetPath;
}