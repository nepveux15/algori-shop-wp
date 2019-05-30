<?php
/**
 * Algori Shop Theme Customizer
 *
 * @package Algori_Shop
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function algori_shop_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'algori_shop_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'algori_shop_customize_partial_blogdescription',
		) );
	}
	
	/**
	* Algori Shop Custom Customizer Customizations [ PS: The order matters ( add_panel, add_section, add_setting, add_control ) ]
	*/
	
	/**
	 * Create custom control for Algori Shop Documentation link.
	 *
	 * Class WP_Customize_Control is loaded only when theme customizer is acutally used. 
	 * So, I've defined my custom class within the algori_shop_customize_register function that is binded to the 'customize_register' action.
	 *
	 * @link https://developer.wordpress.org/themes/customize-api/customizer-objects/#custom-controls-sections-and-panels.
	 */ 
	class WP_Documentation_Link_Customize_Control extends WP_Customize_Control {
		public $type = 'algori_shop_documentation_link';
		/** 
		* Render the control's content. esc_url($my_theme->get( 'AuthorURI' ))
		*/
		public function render_content() {
		?>
			<a href="<?php echo esc_url( wp_get_theme()->get( 'AuthorURI' ) ) . 'blog/article/algori-shop-gutenberg-ready-woocommerce-wordpress-theme'; ?>" class="button button-primary" id="algori-shop-documentation-link" target="_blank" tabindex="0"><?php _e( 'Documentation', 'algori-shop' ); ?></a>
		<?php
		}
	}
	
	// Documentation Section
	$wp_customize->add_section( 'algori_shop_documentation', array(
		'title'				=> __( 'Algori Shop Documentation', 'algori-shop' ),
		'description'		=> __( 'View Algori Shop Documentation.', 'algori-shop' ),
		'priority'			=> 1,
		'capability'		=> 'edit_theme_options',
	    'theme_supports' 	=> '', // Rarely needed.
	) );
	
	// Documentation Button ( from custom control WP_Documentation_Link_Customize_Control )
	$wp_customize->add_control( new WP_Documentation_Link_Customize_Control( $wp_customize, 'documentation_button', array(
        'section'			=> 'algori_shop_documentation',
        'settings'			=> array(),
    ) ) );
	
}
add_action( 'customize_register', 'algori_shop_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function algori_shop_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function algori_shop_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function algori_shop_customize_preview_js() {
	wp_enqueue_script( 'algori-shop-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'algori_shop_customize_preview_js' );

/**
 * Enqueue CSS for styling custom controls in the customizer.
 */
function algori_shop_customize_preview_assets() {
	wp_enqueue_style( 'algori-shop-customizer-custom-controls', get_template_directory_uri() . '/style/css/customizer.css', array(), '20190530', 'all' );
}
add_action( 'customize_controls_enqueue_scripts', 'algori_shop_customize_preview_assets' );
