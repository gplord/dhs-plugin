<?php
/*
Plugin Name: Digital Humanities Scholarship Plugin
Description: Customized functionality for DHS theme collections, originally built for mina-loy.com
Author: Greg Lord
Version: 0.1
Author URI: http://www.gplord.com
*/

/* Begin DHS Plugin functions */
 
function dhs_widgets_init() {

    register_sidebar( array(
        'name'          => 'Main Content Area',
        'id'            => 'main-content-widget',
        'before_widget' => '<div class="main-content-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="main-content-title">',
        'after_title'   => '</h2>',
    ) );

}
add_action( 'widgets_init', 'dhs_widgets_init' );

function register_dhs_menus() {
    register_nav_menus(
        array(
            'footer-menu' => __( 'Footer Menu' )//,
            //'extra-menu' => __( 'Extra Menu' )
        )
    );
}
add_action( 'init', 'register_dhs_menus' );


class dhs_split_text {

    function __construct() {
        add_action('init',array($this,'create_post_type'));
        add_action('init',array($this,'create_taxonomies'));
    }

    function create_post_type() {
        $labels = array(
            'name'               => 'Close Readings',
            'singular_name'      => 'Close Reading',
            'menu_name'          => 'Close Readings',
            'name_admin_bar'     => 'Close Reading',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Close Reading',
            'new_item'           => 'New Close Reading',
            'edit_item'          => 'Edit Close Reading',
            'view_item'          => 'View Close Reading',
            'all_items'          => 'All Close Readings',
            'search_items'       => 'Search Close Readings',
            'parent_item_colon'  => 'Parent Close Reading',
            'not_found'          => 'No Close Readings Found',
            'not_found_in_trash' => 'No Close Readings Found in Trash'
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_nav_menus'   => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-image-flip-horizontal',
            'capability_type'     => 'page',
            'hierarchical'        => true,
            'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
            'has_archive'         => true,
            'rewrite'             => array( 'slug' => 'closereadings' ),
            'query_var'           => true
        );

        register_post_type( 'dhs_split_text', $args );
    }    

    function create_taxonomies() {
        
        // Add a taxonomy like categories
        $labels = array(
            'name'              => 'Types',
            'singular_name'     => 'Type',
            'search_items'      => 'Search Types',
            'all_items'         => 'All Types',
            'parent_item'       => 'Parent Type',
            'parent_item_colon' => 'Parent Type:',
            'edit_item'         => 'Edit Type',
            'update_item'       => 'Update Type',
            'add_new_item'      => 'Add New Type',
            'new_item_name'     => 'New Type Name',
            'menu_name'         => 'Types',
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'type' ),
        );

        register_taxonomy('dhs_split_text_type',array('dhs_split_text'),$args);

        // Add a taxonomy like tags
        $labels = array(
            'name'                       => 'Tags',
            'singular_name'              => 'Tag',
            'search_items'               => 'Tags',
            'popular_items'              => 'Popular Tags',
            'all_items'                  => 'All Tags',
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => 'Edit Tag',
            'update_item'                => 'Update Tag',
            'add_new_item'               => 'Add New Tag',
            'new_item_name'              => 'New Tag Name',
            'separate_items_with_commas' => 'Separate Tags with commas',
            'add_or_remove_items'        => 'Add or remove Tag',
            'choose_from_most_used'      => 'Choose from most used Tags',
            'not_found'                  => 'No Tags found',
            'menu_name'                  => 'Tags',
        );

        $args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'tag' ),
        );

        register_taxonomy('dhs_split_text_tag','dhs_split_text',$args);
    }

}
new dhs_split_text ();


// Custom Shortcode for creating a Split-Text Viewer Section

function splittext_shortcode ( $atts, $content = null ) {
    extract( shortcode_atts( array (
            "id" => '',
            "inline" => ''
        ),
        $atts)
    );
    $breakclass = '';
    if ($atts['inline'] != null) {
        if ($atts['inline']) {
            $breakclass = ' closereading-inline';
        }
    }
	return '<a id="{this}section-' . $atts['id'] . '" class="closereading-section' . $breakclass . '" data-this="{this}" data-other="#{other}section-' . $atts['id'] . '" title="Section: ' . $atts['id'] .'">' . $content . '</a>';
}
add_shortcode("splittext", "splittext_shortcode");

function imageframe_shortcode ( $atts, $content = null ) {
    extract( shortcode_atts( array (
            "url" => '',
			"width" => '',
			"height" => ''
        ),
        $atts)
    );
    return '<div class="imageframe"><div class="imageframe-image" style="width: ' . $atts['width'] . 'px; height: ' . $atts['height'] . 'px"><img src="' . $atts['url'] . ' style="max-width: 100%; max-height: 100%;"></div><div style="clear:both"></div><div class="imageframe-caption">' . $content . '</div></div>';
}
add_shortcode("imageframe", "imageframe_shortcode");


// Custom Shortcode for creating a Parallax Spacer
function spacer_shortcode ( $atts ) {
    extract( shortcode_atts( array (
            "height" => ''
        ),
        $atts)
    );
    return '<div class="parallax-spacer" style="height: ' . $atts['height'] . 'px"></div>';
}
add_shortcode("spacer", "spacer_shortcode");

$collapsible_index = 1;

// Custom Shortcode for creating a Parallax Spacer
function collapsible_shortcode ( $atts, $content = null ) {
    extract( shortcode_atts( array (
            "label" => ''
        ),
        $atts)
    );
	if ($atts['label'] == "") {
		$atts['label'] = "Read More";
	}
	$GLOBALS[collapsible_index]++;
    return '<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse'.$GLOBALS[collapsible_index].'" aria-expanded="false" aria-controls="collapse1">' . $atts['label'] . '</button><div class="collapse" id="collapse'.$GLOBALS[collapsible_index].'">' . $content . '</div>';
}
add_shortcode("collapsible", "collapsible_shortcode");

// Custom Shortcode for creating a Link Button
function linkbutton_shortcode ( $atts, $content = null ) {
    extract( shortcode_atts( array (
            "url" => ''
        ),
        $atts)
    );
    return '<a class="btn btn-primary linkbutton" href="' . $atts['url'] . '">' . $content . '</a>';
}
add_shortcode("linkbutton", "linkbutton_shortcode");


// ----------------------------------------------------- //

remove_filter ('acf_the_content', 'wpautop');

function add_theme_scripts() {
    wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/dhs.js', array ( 'jquery' ), 1.1, true);
}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );


add_action('init', 'add_button');
function add_button() {
    if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {
        if (get_user_option('rich_editing') == 'true') {
            global $posttype;
            if (empty($posttype) && !empty($_GET['post'])) {
                $post = get_post($_GET['post']);
                $posttype = $post->post_type;
            }
            
            /* To Do: Make these two splittext buttons conditional to certain editor pages */
            add_filter('mce_external_plugins', 'add_splittext_plugin');
            add_filter('mce_buttons', 'register_splittext_button');

			add_filter('mce_external_plugins', 'add_spacer_plugin');
			add_filter('mce_buttons', 'register_spacer_button');

			add_filter('mce_external_plugins', 'add_twocolumns_plugin');
			add_filter('mce_buttons', 'register_twocolumns_button');

			add_filter('mce_external_plugins', 'add_imageframe_plugin');
			add_filter('mce_buttons', 'register_imageframe_button');

			add_filter('mce_external_plugins', 'add_collapsible_plugin');
			add_filter('mce_buttons', 'register_collapsible_button');

			add_filter('mce_external_plugins', 'add_linkbutton_plugin');
			add_filter('mce_buttons', 'register_linkbutton_button');

			add_filter('mce_external_plugins', 'add_footnotebutton_plugin');
			add_filter('mce_buttons', 'register_footnotebutton_button');
        }
    }
 }
 function register_splittext_button($buttons) {
    array_push($buttons, "splittext");
    return $buttons;
 }
 function add_splittext_plugin($plugin_array) {
    $plugin_array['splittext'] = get_bloginfo('stylesheet_directory').'/js/dhs-tinymce.js';
    return $plugin_array;
 } 
 function register_spacer_button($buttons) {
    array_push($buttons, "spacer");
    return $buttons;
 }
 function add_spacer_plugin($plugin_array) {
    $plugin_array['spacer'] = get_bloginfo('stylesheet_directory').'/js/dhs-tinymce.js';
    return $plugin_array;
 }
  function register_twocolumns_button($buttons) {
    array_push($buttons, "twocolumns");
    return $buttons;
 }
 function add_twocolumns_plugin($plugin_array) {
    $plugin_array['twocolumns'] = get_bloginfo('stylesheet_directory').'/js/dhs-tinymce.js';
    return $plugin_array;
 }

 function register_imageframe_button($buttons) {
    array_push($buttons, "imageframe");
    return $buttons;
 }
 function add_imageframe_plugin($plugin_array) {
    $plugin_array['imageframe'] = get_bloginfo('stylesheet_directory').'/js/dhs-tinymce.js';
	//error_log($plugin_array['imageframe']);
    return $plugin_array;
 }

 function register_collapsible_button($buttons) {
    array_push($buttons, "collapsible");
    return $buttons; }

 function add_collapsible_plugin($plugin_array) {
    $plugin_array['collapsible'] = get_bloginfo('stylesheet_directory').'/js/dhs-tinymce.js';
	//error_log($plugin_array['collapsible']);
    return $plugin_array;
 }

 function register_linkbutton_button($buttons) {
    array_push($buttons, "linkbutton");
    return $buttons; }

 function add_linkbutton_plugin($plugin_array) {
	$plugin_array['linkbutton'] = get_bloginfo('stylesheet_directory').'/js/dhs-tinymce.js';
    return $plugin_array;
 } 

 function register_footnotebutton_button($buttons) {
    array_push($buttons, "footnotebutton");
    return $buttons; }

 function add_footnotebutton_plugin($plugin_array) {
	$plugin_array['footnotebutton'] = get_bloginfo('stylesheet_directory').'/js/dhs-tinymce.js';
    return $plugin_array;
 } 

/* End DHS Plugin functions */








?>