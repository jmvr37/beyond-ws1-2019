<?php
/**
 * RED Starter Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Beyond The Conversation
 */

if ( ! function_exists( 'red_starter_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function red_starter_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html( 'Primary Menu' ),
	) );

	// Switch search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

}
endif; // red_starter_setup
add_action( 'after_setup_theme', 'red_starter_setup' );

function enqueue_fa(){
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
}
add_action('wp_enqueue_scripts','enqueue_fa');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * @global int $content_width
 */
function red_starter_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'red_starter_content_width', 640 );
}
add_action( 'after_setup_theme', 'red_starter_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function red_starter_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html( 'Sidebar' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html( 'Footer' ),
		'id'            => 'footer-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="footer-widget">',
		'after_title'   => '</div>',
	) );
}
add_action( 'widgets_init', 'red_starter_widgets_init' );

/**
 * Filter the stylesheet_uri to output the minified CSS file.
 */
function red_starter_minified_css( $stylesheet_uri, $stylesheet_dir_uri ) {
	if ( file_exists( get_template_directory() . '/build/css/style.min.css' ) ) {
		$stylesheet_uri = $stylesheet_dir_uri . '/build/css/style.min.css';
	}

	return $stylesheet_uri;
}
add_filter( 'stylesheet_uri', 'red_starter_minified_css', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function red_starter_scripts() {
	wp_enqueue_style( 'red-starter-style', get_stylesheet_uri() );

	wp_enqueue_script( 'red-starter-navigation', get_template_directory_uri() . '/build/js/navigation.min.js', array(), '20151215', true );
	wp_enqueue_script( 'red-starter-skip-link-focus-fix', get_template_directory_uri() . '/build/js/skip-link-focus-fix.min.js', array(), '20151215', true );
	wp_enqueue_script( 'red-starter-loadMore', get_template_directory_uri() . '/build/js/loadMore.min.js', array('jquery'), 1.1, true );
	wp_enqueue_script( 'red-starter-teamMembers', get_template_directory_uri() . '/build/js/teamMembers.min.js', array('jquery'), 1.1, true );
	wp_enqueue_script( 'red-starter-news', get_template_directory_uri() . '/build/js/news.min.js', array('jquery'), 1.1, true );

	wp_enqueue_script( 'red-starter-searchForm', get_template_directory_uri() . '/build/js/searchForm.min.js', array(), '20151215', true );
	wp_enqueue_script( 'red-starter-fontResize', get_template_directory_uri() . '/build/js/fontResize.min.js', array(), '20151215', true );
	wp_enqueue_script( 'red-starter-navBarColor', get_template_directory_uri() . '/build/js/navBarColor.min.js', array('jquery'), 1.1, true );

	
	$script_url = get_template_directory_uri() . '/build/js/singleEvent.min.js';
  	wp_enqueue_script( 'jquery' );
   	wp_enqueue_script( 'red_events', $script_url, array( 'jquery' ), false, true );
	  
	wp_localize_script( 'red_events', 'red_vars', array(
	  'rest_url' => esc_url_raw( rest_url() ),
	  'template_uri' =>  get_stylesheet_directory_uri(),
      'wpapi_nonce' => wp_create_nonce( 'wp_rest' ),
      'post_id' => get_the_ID()
  ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'red_starter_scripts' );


function custom_load_font_awesome() {

    wp_enqueue_style( 'red-starter-font-awesome-free', 'https://use.fontawesome.com/releases/v5.2.0/css/all.css' );

}
add_action( 'wp_enqueue_scripts', 'custom_load_font_awesome' );


function flickity_scripts() {
	wp_enqueue_script( 'flickityjs', 'https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js', array(), '1.9.0', true );
	
	
	wp_enqueue_style( 'flickitycss', 'https://unpkg.com/flickity@2/dist/flickity.min.css');
 }
	add_action( 'wp_enqueue_scripts', 'flickity_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

 // Function to modify the main query object
 function query_ppp($query) {
	if (is_home()){
	$query->set('posts_per_page', '6');
	}
}
add_action( 'pre_get_posts', 'query_ppp' );

// Search form

function beyond_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="search-form" action="' . home_url( '/' ) . '" >
    <label class="screen-reader-text" for="s">' . __( 'Search for:' ) . '</label>
	<input class="search-input" onClick="this.setSelectionRange(0, this.value.length)" type="text" placeholder="Search here..." value="' . get_search_query() . '" name="s" id="s" />
	<span id="clear-search">X</span>
    <input type="submit" id="searchsubmit" class="search-submit" value="'. esc_attr__( 'Search' ) .'" />
    </form>';
 
    return $form;
}
add_filter( 'get_search_form', 'beyond_search_form' );


// Submenu "active" classes

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
    }
    return $classes;
}

function wpdocs_custom_excerpt_length( $length ) {
    return 5;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

