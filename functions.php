<?php

function load_assets() {

  // Add stylesheets using a handle and their URL

  $styles = [
    'base' => get_stylesheet_uri()
  ];

  // It's the same with scripts!
  // But only place internal ones in this place.

  $scripts = [
    'plugins' => 'plugins.js',
    'base' => 'app.js'
  ];

  // Register all scripts and make them depend of jQuery

  foreach( $scripts as $handle => $file ) {
    wp_enqueue_script( $handle, get_stylesheet_directory_uri() . '/js/' . $file, [ 'jquery' ], null, true );
  }

  foreach( $styles as $handle => $file ) {

    // If a stylesheets URL is internal, prefix it with the theme's URL

    if( strpos( $file, 'wp-content' ) == false ) {
      $file = get_stylesheet_directory_uri() . '/css/' . $file;
    }

    wp_enqueue_style( $handle, $file );

  }

  // Retrieve jQuery from its CDN and move it to the footer

  wp_deregister_script( 'jquery' );
  wp_enqueue_script( 'jquery', '//code.jquery.com/jquery-latest.min.js', false, null, true );

}

add_action( 'wp_enqueue_scripts', 'load_assets' );


function remove_menu_items() {

  // Remove unused menu items

  $pages = [
    'edit-comments.php',
    'edit.php'
  ];

  foreach( $pages as $page ) {
    remove_menu_page( $page );
  }

  // Use the stylesheet containing the typo within the WYSIWYG editor
  add_editor_style( 'css/typo.css' );

}

add_action( 'admin_init', 'remove_menu_items' );


function remove_admin_bar_items( $bar ) {

  // Hide some things from the admin bar

  $hide = [
    'comments',
    'new-post'
  ];

  foreach( $hide as $page ) {
    $bar->remove_node( $page );
  }

}

add_action( 'admin_bar_menu', 'remove_admin_bar_items', 90 );


function disable_everything_useless() {

  // Hide all meta tags that aren't necessary

  $tags = [
    'feed_links_extra' => 3,
    'feed_links' => 2,
    'wp_generator' => 0
  ];

  foreach( $tags as $tag => $priority ) {
    remove_action( 'wp_head', $tag, $priority );
  }

  // Remove support for post tags
  register_taxonomy( 'post_tag', [] );

}

add_action( 'init', 'disable_everything_useless' );


function disable_post_type_features() {

  // Which post type has features which can be removed?

  $features = [

    'page' => [
      'comments',
      'custom-fields'
    ],

    'post' => [
      'trackbacks',
      'custom-fields',
      'comments',
      'excerpt'
    ]

  ];

  // Remove all features stated in the object above

  foreach( $features as $type => $list ) {

    foreach( $list as $feature ) {
      remove_post_type_support( $type, $feature );
    }

  }

  // Generate the <title> tag automatically
  add_theme_support( 'title-tag' );

}

add_action( 'init', 'disable_post_type_features' );

?>
