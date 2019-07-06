<?php
/**
 * love.
 *
 * This file adds functions to the love Theme.
 *
 * @package love
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Defines the child theme (do not remove).
define( 'CHILD_THEME_NAME', 'love' );
define( 'CHILD_THEME_URL', 'https://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.8.0' );

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( 'genesis-sample', get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style(
		'genesis-sample-fonts',
		'//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700',
		array(),
		CHILD_THEME_VERSION
	);

	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script(
		'genesis-sample-responsive-menu',
		get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js",
		array( 'jquery' ),
		CHILD_THEME_VERSION,
		true
	);

	wp_localize_script(
		'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

	wp_enqueue_script(
		'genesis-sample',
		get_stylesheet_directory_uri() . '/js/genesis-sample.js',
		array( 'jquery' ),
		CHILD_THEME_VERSION,
		true
	);

}

/**
 * Defines responsive menu settings.
 *
 * @since 2.3.0
 */
function genesis_sample_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => __( 'Menu', 'genesis-sample' ),
		'menuIconClass'    => 'dashicons-before dashicons-menu',
		'subMenu'          => __( 'Submenu', 'genesis-sample' ),
		'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'      => array(
			'combine' => array(
				'.nav-primary',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Adds support for HTML5 markup structure.
add_theme_support(
	'html5',
	array(
		'caption',
		'comment-form',
		'comment-list',
		'gallery',
		'search-form',
	)
);

// Adds support for accessibility.
add_theme_support(
	'genesis-accessibility',
	array(
		'404-page',
		'drop-down-menu',
		'headings',
		'search-form',
		'skip-links',
	)
);

// Adds viewport meta tag for mobile browsers.
add_theme_support(
	'genesis-responsive-viewport'
);

// Adds custom logo in Customizer > Site Identity.
add_theme_support(
	'custom-logo',
	array(
		'height'      => 120,
		'width'       => 700,
		'flex-height' => true,
		'flex-width'  => true,
	)
);

// Renames primary and secondary navigation menus.
add_theme_support(
	'genesis-menus',
	array(
		'primary'   => __( 'Header Menu', 'genesis-sample' ),
		'secondary' => __( 'Footer Menu', 'genesis-sample' ),
	)
);

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'internal-1', 920, 629, true );
add_image_size( 'banner', 2000, 1000, true );

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

add_action( 'genesis_theme_settings_metaboxes', 'genesis_sample_remove_metaboxes' );
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 2.6.0
 *
 * @param string $_genesis_admin_settings The admin screen to remove meta boxes from.
 */
function genesis_sample_remove_metaboxes( $_genesis_admin_settings ) {

	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_admin_settings, 'main' );

}

add_filter( 'genesis_customizer_theme_settings_config', 'genesis_sample_remove_customizer_settings' );
/**
 * Removes output of header settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function genesis_sample_remove_customizer_settings( $config ) {

	unset( $config['genesis']['sections']['genesis_header'] );
	return $config;

}

// Displays custom logo.
add_action( 'genesis_site_title', 'the_custom_logo', 0 );

// Repositions primary navigation menu.
//remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 9 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' !== $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;
	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

// Admin css
add_action('admin_enqueue_scripts', 'admin_styles');
function admin_styles() {
    wp_enqueue_style('backend-admin', get_stylesheet_directory_uri() . '/admin.css');
}

//* Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );

// Add user role class to body tag
add_filter('body_class','add_role_to_body');
function add_role_to_body($classes) {
    global $current_user;
    $user_role = $current_user->roles;
    return array_merge( $classes, array( $user_role[0] ) );
}

// ------------------------ Seconday nav HEADER widget area ------------------------------
genesis_register_sidebar( array(
	'id'		=> 'secondary-nav-header',
	'name'		=> __( 'Secondary Nav Header', '' ),
	'description'	=> __( 'Secondary nav in header, above primary.', '' ),
) );

add_action( 'genesis_header', 'secondary_menu_header', 11 );
function secondary_menu_header() {
	genesis_widget_area ('secondary-nav-header', array(
        'before' => '<div class="second-nav">',
        'after' => '</div>',
        ) );
}

// ------------------------ Seconday nav FOOTER widget area ------------------------------
genesis_register_sidebar( array(
	'id'		=> 'secondary-nav-footer',
	'name'		=> __( 'Secondary Nav Footer', '' ),
	'description'	=> __( 'Secondary nav in footer, above primary.', '' ),
) );

add_action( 'genesis_footer', 'secondary_menu_footer', 8 );
function secondary_menu_footer() {
	genesis_widget_area ('secondary-nav-footer', array(
        'before' => '<div class="second-nav second-nav--footer">',
        'after' => '</div>',
        ) );
}


// --------------------------                           ---------------------------
// Secondary nav in header
add_action('genesis_header', 'secondary_nav_header', 10 );
function secondary_nav_header(){

		/*
		
		 echo '<div class="second-nav"><a class="second-nav__lnk" href="' . home_url() . '">Home</a><a class="second-nav__lnk" href="' . types_render_field( 'about-lnk', array('id' => '9') ) . '">About</a><a  class="second-nav__lnk" href="' . types_render_field( 'donate-lnk', array('id' => '9', 'url' => 'true') ) . '">Donate</a><a class="second-nav__lnk" href="' . home_url() . '/contact/">Contact</a></div>';

		*/
	
	/*
	echo '<div class="second-nav"><a class="second-nav__lnk" href="' . home_url() . '">Home</a><a class="second-nav__lnk" href="' . types_render_field( 'about-lnk', array('id' => '9') ) . '">About</a><a class="second-nav__lnk" href="' . home_url() . '/contact/">Contact</a></div>';
	*/

} 

// Secondary nav in footer
/*
add_action('genesis_footer', 'secondary_nav_footer', 8 );
function secondary_nav_footer(){

		echo '<div class="second-nav second-nav--footer"><a class="second-nav__lnk" href="' . home_url() . '">Home</a><a class="second-nav__lnk" href="' . types_render_field( 'about-lnk', array('id' => '9') ) . '">About</a><a class="second-nav__lnk" href="' . home_url() . '/contact/">Contact</a></div>';

} 
*/

// Logo output
add_action('genesis_header', 'header_logo', 10 );
function header_logo(){

	if ( is_page_template( 'internal-page.php') ){

		echo '<div class="title-area-new"><p class="site-title" itemprop="headline">' . types_render_field( 'brand-logo', array() )  . '</p><p class="site-description" itemprop="description">Life Opportunities Valuing Everyone</p></div>';

	}
}


// Brand colour output
add_action('genesis_header', 'brand_colors', 9 );
function brand_colors(){

		echo '<style> .page-template-internal-page .content .block-internal-intro h2, .page-template-internal-page .content .block-contact h2 { color: ' . types_render_field( 'brand-color', array() )  . '} .page-template-internal-page .content .row--bg2 { background-color: ' . types_render_field( 'brand-color', array() )  . '} .page-template-internal-page .content .block--internal-1 { background-color: ' . types_render_field( 'brand-color', array() )  . '} .genesis-nav-menu .current-menu-item>a { background-color: ' . types_render_field( 'brand-color', array() )  . '} </style>';

} 


// Output main banner image
add_action('genesis_entry_content', 'banner_image', 10 );
function banner_image(){

	if ( is_page_template( 'internal-page.php') ){

		echo '<div class="banner" role="banner">' . types_render_field( 'banner-image', array('size' => 'banner', 'alt'=>'Banner image', 'class'=>'') ) . '</div>';
	}
}

// ----------------------- Homepage output -----------------------------------
add_action('genesis_after_header', 'home_page_10', 10 );
function home_page_10(){

	if ( is_page_template( 'homepage.php') ){
		
		echo '<div class="row row--bg1"><div class="wrap"><div class="video-container">
				<video class="video-container__video" autoplay="true"><source src="' . home_url() . '/wp-content/uploads/2019/02/animated-logo.mp4" type="video/mp4">Your browser does not support the video tag.</video></div></div></div>';
		
	//echo '<div class="row row--bg1"><div class="wrap"><div class="video-container"></div></div></div>';

	
	}
}

// Intro - Block 1
add_action('genesis_entry_content', 'home_page_11', 11 );
function home_page_11(){

	if ( is_page_template( 'homepage.php') ){

		echo '<div class="row row--2"><div class="wrap"><div class="block block-intro" style="background-image: url(' . types_render_field( 'block-intro-bg', array('url' => 'true') )  . ')">' . types_render_field( 'home-1-text', array() )  . '</div></div></div>';
	}
}

// Learning - Block 2
add_action('genesis_entry_content', 'home_page_12', 12 );
function home_page_12(){

	if ( is_page_template( 'homepage.php') ){

		echo '<article class="row" style="background-color:' . types_render_field( 'home-2-bg-colour', array() )  . '"><div class="wrap wrap--bg1" style="background-image: url(' . types_render_field( 'block-learning-bg', array('url' => 'true') )  . ')"><div class="block block-learning block--thirds"><style> .block-learning__header{ background-image: url(' . types_render_field( 'home-2-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-learning__header">' . types_render_field( 'home-2-header', array() )  . '</h2>' . types_render_field( 'home-2-text', array() )  . '</div></div></article>';
	}
}

// Corporate - Block 3
add_action('genesis_entry_content', 'home_page_13', 13 );
function home_page_13(){

	if ( is_page_template( 'homepage.php') ){

		echo '<article class="row row--3" style="background-color:' . types_render_field( 'home-3-bg-colour', array() )  . '"><div class="wrap"><div class="block block-corporate" style="background-image: url(' . types_render_field( 'block-corporate-bg', array('url' => 'true') )  . ')"><style> .block-corporate__header{ background-image: url(' . types_render_field( 'home-3-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-corporate__header">' . types_render_field( 'home-3-header', array() )  . '</h2>' . types_render_field( 'home-3-text', array() )  . '</div></div></article>';
	}
}

// Care and sport - Block 4 and 5
add_action('genesis_entry_content', 'home_page_14', 14 );
function home_page_14(){

	if ( is_page_template( 'homepage.php') ){

		echo '<article class="row row--4"><div class="wrap"><div class=" dflex dflex--wrap dflex--space-between"><div class="block block--halves block-care"  style="background-image: url(' . types_render_field( 'block-care-bg', array('url' => 'true') )  . '); background-color:' . types_render_field( 'home-4-bg-colour', array() )  . '"><style> .block-care__header{ background-image: url(' . types_render_field( 'home-4-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-care__header">' . types_render_field( 'home-4-header', array() )  . '</h2>' . types_render_field( 'home-4-text', array() )  . '</div><div class="block block--halves block-sport"  style="background-image: url(' . types_render_field( 'block-sport-bg', array('url' => 'true') )  . '); background-color:' . types_render_field( 'home-5-bg-colour', array() )  . '"><style> .block-sport__header{ background-image: url(' . types_render_field( 'home-5-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-sport__header">' . types_render_field( 'home-header-5', array() )  . '</h2>' . types_render_field( 'home-5-text', array() )  . '</div></div></div></article>';
	}
}

// Training, recruitment, publications - Block 6,7,8
add_action('genesis_entry_content', 'home_page_15', 15 );
function home_page_15(){

	if ( is_page_template( 'homepage.php') ){

		echo '<article class="row"><div class="wrap"><div class="dflex dflex--wrap dflex--space-between">
			<div class="block block--thirds  block-training" style="background-image: url(' . types_render_field( 'block-training-bg', array('url' => 'true') )  . '); background-color:' . types_render_field( 'home-6-bg-colour', array() )  . '"><style> .block-training__header{ background-image: url(' . types_render_field( 'home-6-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-training__header">' . types_render_field( 'home-6-header', array() )  . '</h2>' . types_render_field( 'home-6-text', array() )  . '</div>
			<div class="block block--thirds block-recruitment" style="background-image: url(' . types_render_field( 'block-recruitment-bg', array('url' => 'true') )  . '); background-color:' . types_render_field( 'home-7-bg-colour', array() )  . '"><style> .block-recruitment__header{ background-image: url(' . types_render_field( 'home-7-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-recruitment__header">' . types_render_field( 'home-header-7', array() )  . '</h2>' . types_render_field( 'home-7-text', array() )  . '</div>
			<div class="block block--thirds block-publications" style="background-image: url(' . types_render_field( 'block-publications-bg', array('url' => 'true') )  . '); background-color:' . types_render_field( 'home-8-bg-colour', array() )  . '"><style> .block-publications__header{ background-image: url(' . types_render_field( 'home-8-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-publications__header">' . types_render_field( 'home-header-8', array() )  . '</h2>' . types_render_field( 'home-8-text', array() )  . '</div
			</div></div></article>';
	}
}

// E-learning- Block 9
add_action('genesis_entry_content', 'home_page_16', 16 );
function home_page_16(){

	if ( is_page_template( 'homepage.php') ){

		echo '<article class="row row--5"><div class="wrap">
			<div class="block block-elearning" style="background-color:' . types_render_field( 'home-9-bg-colour', array() )  . '"><style> .block-elearning__header{ background-image: url(' . types_render_field( 'home-9-brand-logo', array('url' => 'true') )  . ');}</style><h2 class="block-elearning__header">' . types_render_field( 'home-9-header', array() )  . '</h2>' . types_render_field( 'home-9-text', array() )  . '</div>
			</div></article>';
	}
}

// --------------------------------- Contact page --------------------------------------

add_action('genesis_entry_content', 'contact_11', 11 );
function contact_11(){

	if ( is_page_template( 'contact-page.php') ){

		echo '<div class="row"><div class="wrap">
			<div class="block-freephone"><p>' . types_render_field( 'free-phone', array() )  . '</p></div>
			</div></div>';
	}
}

// Contact page
add_action('genesis_entry_content', 'contact_12', 12 );
function contact_12(){

	if ( is_page_template( 'contact-page.php') ){

		echo '<div class="row"><div class="wrap"><div class="dflex dflex--2 dflex--space-between">
			<div class="block block-contact-details block--halves-2"><p>Our contact details<br>Telephone: <strong>' . types_render_field( 'contact-telephone', array() )  . '</strong><br>Email: <strong>' . types_render_field( 'contact-email-address', array() )  . '</strong></p><p>' . types_render_field( 'contact-postal-address', array() )  . '</p></div>
			<div class="block block--halves-2 block-map"><img class="block-map__img" src="' . home_url() . '/wp-content/uploads/2019/02/map.png" alt="map"></div>
			</div></div></div>';
	}
}

// Contact page
add_action('genesis_entry_content', 'contact_13', 13 );
function contact_13(){

	if ( is_page_template( 'contact-page.php') ){

		echo '<div class="row row--1"><div class="wrap"><div class="dflex  dflex--2 dflex--space-between">
			<div class="block block--bg1 block--halves"><div class="block block-contact"><h2>contact</h2>' . do_shortcode('[contact-form-7 id="220" title="Contact form 1"]') . '</div></div>
			<div class="block block--bg1 block--halves">
				<div class="block-contact block-contact--r-t"><h2>' . types_render_field( 'contact-header-blk-1', array() )  . '</h2>' . types_render_field( 'contact-content-blk-1', array() )  . '</div>
				<div class="block-contact block-contact--r-b"><h2>' . types_render_field( 'contact-header-blk-2', array() )  . '</h2>' . types_render_field( 'contact-content-blk-2', array() )  . '</div>
			</div></div></div></div>';
	}
}

// ------------------------------------------- Internal page (charity, sport etc ) ----------------------------

add_action('genesis_entry_content', 'internal_page_11', 11 );
function internal_page_11(){

	if ( is_page_template( 'internal-page.php') ){

		echo '<div class="row row--2"><div class="wrap"><div class="block-internal-intro-container"><div class="block block-internal-intro"><h2>' . types_render_field( 'internal-1-header', array() )  . '</h2>' . types_render_field( 'internal-1-text', array() )  . '</div><div class="block-internal-intro-image">' . types_render_field( 'internal-1-image', array() )  . '</div></div></div></div>';
	}
}

add_action('genesis_entry_content', 'internal_page_12', 12 );
function internal_page_12(){

	if ( is_page_template( 'internal-page.php') ){

		echo '<div class="row row--1 row--6 row--bg2"><div class="wrap"><div class="dflex dflex--space-between">
				<div class="block block--halves-2 block--internal-1"><h2>' . types_render_field( 'internal-2-header', array() )  . '</h2>' . types_render_field( 'internal-2-text', array() )  . '</div>
				<div class="block block--halves-2 block--internal-img">' . types_render_field( 'internal-image', array('size' => 'internal-1', 'alt' => 'Photo') )  . '</div>
			</div></div></div>';
	}
}

add_action('genesis_entry_content', 'internal_page_13', 13 );
function internal_page_13(){

	if ( is_page_template( 'internal-page.php') ){

		/*
		echo '<div class="row"><div class="wrap"><div class="dflex dflex--space-between">
			<div class="block block--bg1 block--halves"><div class="block block-contact"><h2>contact</h2>' . do_shortcode('[contact-form-7 id="220" title="Contact form 1"]') . '</div></div>
			<div class="block block--bg1 block--halves">
				<div class="block-contact block-contact--r-t block-contact--r-t-1"><h2>' . types_render_field( 'internal-5-header', array() )  . '</h2>' . types_render_field( 'internal-5-text', array() )  . '</div>
				<div class="block-contact block-contact--r-b"><h2>' . types_render_field( 'internal-4-header', array() )  . '</h2>' . types_render_field( 'internal-4-text', array() )  . '</div>
			</div></div></div></div>';

		*/
	
		echo '<div class="row"><div class="wrap"><div class="dflex dflex--space-between">
			<div class="block block--bg1 block--halves"><div class="block block-contact">' . types_render_field( 'home-block-10', array() ) . '</div></div>
			<div class="block block--bg1 block--halves">
				<div class="block-contact block-contact--r-t block-contact--r-t-1">
					<div class="block-contact__inner-l"><h2>' . types_render_field( 'internal-5-header', array() )  . '</h2>' . types_render_field( 'internal-5-text', array() )  . '</div>
					<div class="block-contact__inner">' . types_render_field( 'internal-4-image', array() )  . '</div>
				</div>
				<div class="block-contact block-contact--r-b">
					<h2>' . types_render_field( 'internal-4-header', array() )  . '</h2>
					<div class="block-contact__r-b-container">
						<div class="block-contact__r-b-container__l">' . types_render_field( 'internal-4-text', array() )  . '</div>
						<div class="block-contact__r-b-container__r">' . types_render_field( 'internal-5-image', array() )  . '</div></div>
					</div>
			</div></div></div></div>';

	}
}





// Social media - header
add_action('genesis_header', 'social_media', 13 );
function social_media(){

		echo '<div class="social-media-icons"><a target="_blank" class="social-media-icons__instagram" href="' . types_render_field( 'social-instagram-url', array() )  . '">Instagram</a><a  target="_blank" class="social-media-icons__twitter" href="' . types_render_field( 'social-twitter-url', array() )  . '">Twitter</a><a  target="_blank" class="social-media-icons__facebook" href="' . types_render_field( 'social-facebook-url', array() )  . '">Facebook</a></div>';

}

// Social media - footer
add_action('genesis_footer', 'social_media_footer', 9 );
function social_media_footer(){

		echo '<div class="social-media-icons social-media-icons--footer"><a target="_blank" class="social-media-icons__instagram" href="' . types_render_field( 'social-instagram-url', array() )  . '">Instagram</a><a  target="_blank" class="social-media-icons__twitter" href="' . types_render_field( 'social-twitter-url', array() )  . '">Twitter</a><a  target="_blank" class="social-media-icons__facebook" href="' . types_render_field( 'social-facebook-url', array() )  . '">Facebook</a></div>';

}

//* Output footer content
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = '<p>Copyright ' . do_shortcode('[footer_copyright]') . ' LOVE.</p>';

	return $creds;
}

//
add_filter('genesis_footer', 'footer_nav_1', 9);
function footer_nav_1( $creds ) {
	echo '<nav class="tertiary-nav"><a class="tertiary-nav__lnk" href="' . home_url() . '/privacy-policy">Privacy &amp; Cookie Policy</a><a class="tertiary-nav__lnk" href="' . home_url() . '/terms-of-use">Terms of Use</a></nav>';
}
