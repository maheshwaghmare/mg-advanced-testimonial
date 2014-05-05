<?php

/**
 * The MG Advanced Testimonial Plugin
 *
 * Plugin Name:     MG Advanced Testimonial
 * Plugin URI:      http://mgwebthemes.com
 * Github URI:      https://github.com/maheshwaghmare/mg-advanced-testimonials
 * Description:     Use <strong>[mg-testimonial]</strong> for Few testimonials. If you have maximum testimonials then create testimonials by using post type "MG Testimonial". And use <strong>[mg-post-testimonial]</strong> shortcode to print it.
 * Author:          Mahesh Waghmare
 * Author URI:      http://mgwebthemes.com
 * Version:         1.0.
 * Text Domain:     mg-testimonials
 * License:         GPL2+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /MGTestimonials/lang
 *
 * @author          Mahesh M. Waghmare <mwaghmare7@gmail.com>
 * @license         GNU General Public License, version 2
 * @copyright       2014 MG Web Themes
 */

//	Enqueue	Styles [CSS]
	wp_enqueue_style( 'wp_enqueue_styles', plugins_url( '/css/style.css', __FILE__ ) );
	wp_enqueue_style( 'font_awesome', plugins_url( '/font-awesome-4.0.3/css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'owl_carsousel_css', plugins_url( '/owl-carousel/owl.carousel.css', __FILE__ ) );
	wp_enqueue_style( 'owl_carsousel_theme_css', plugins_url( '/owl-carousel/owl.theme.css', __FILE__ ) );

//	Enqueue	Scripts [JS]
	function mg_testimonial_init() {
		wp_register_script( 'owl_carsousel_js', plugins_url('/owl-carousel/owl.carousel.min.js', __FILE__ ), array('jquery')); 	

		wp_enqueue_script('jquery');
		wp_enqueue_script('owl_carsousel_js');
	}
	add_action( 'wp', 'mg_testimonial_init' );


/**
 * Add Action
 * Init testimonials setup
 *
 * @since MG Advanced Testimonial 1.0
 */
 add_action('init', function(){
	new mg_a_testimonial_init();
	include dirname(__FILE__) . '/mg-a-shortcodes.php';
}); 


//	init class definition
class mg_a_testimonial_init {
	
	public function __construct(){
		$this->register_post_type();
		$this->taxonomies();
		$this->metaboxes();
	}/* Construct Method */
	
	
	//	Register "MG Testimonial" custom post
	public function register_post_type(){
		$args = array(
			'labels' => array(
				'name' => 'MG Testimonial',
				'singular_name' => 'Testimonial',
				'add_new' => 'Add new',
				'add_new_item' => 'Add new Testimonial',
				'edit_item' =>  'Edit item',
				'new_item' => 'Add new item',
				'all_items' => 'All Testimonials',
				'view_item' => 'View Testimonial',
				'search_items' => 'Search Testimonials',
				'not_found' => 'No Testimonial Found',
				'not_found_in_trash' => 'No Testimonial Found in Trash'
			),
			'query_var' => 'mg_testimonial',
			'rewrite' => array(
				'slug' => 'mg_testimonial/',
			),
			'public' => true,
			'menu_position' => 25, /* Below Comments ie for various positions please refer 	http://codex.wordpress.org.Function_Reference/register_post_type	 */
			'menu_icon' => admin_url() .'images/media-button-video.gif', /* set icon for Testimonial */
			'supports' => array(
				'title',	/* show title field field at new Testimonial window */
				'thumbnail', /* show featured image field at new Testimonial window */
				'editor'
			)
			
		);/* [$args array] holds all information about New Custom Post values i.e. create new, edit item etc. */
		
		register_post_type('mg_testimonial', $args);	/* Register Post Type */
	}
	
	
	//	Register category "MG Testimonial Type"
	public function taxonomies()
	{
		$taxonomies = array();
		
		$taxonomies['mg_testimonial_type'] = array(
			'hierarchical' => true, 
			'query_var' => 'mg_testimonial_details',
			'rewrite' => array(
				'slug' => 'mg_testimonial_details'
			),
			'labels' => array(
				'name' => 'Testimonial Types',
				'singular_name' => 'Testimonial Type',
				'add_new' => 'Add new Testimonial Type',
				'add_new_item' => 'Add new Testimonial',
				'edit_item' =>  'Edit item',
				'new_item' => 'Add new item',
				'view_item' => 'View Testimonial',
				'search_items' => 'Search Testimonials',
				'not_found' => 'No Testimonial Found',
				'not_found_in_trash' => 'No Testimonial Found in Trash'
			)
		);
		$this->register_all_taxonomies($taxonomies); 
	}/* TAXONOMIES are same as Categories */
	
	
	//	Set mg_testimonial_type Category to Post type [mg_testimonial]
	public function register_all_taxonomies($taxonomies)
	{
		foreach($taxonomies as $name => $arr) {
			register_taxonomy($name, array('mg_testimonial'), $arr);
		}
	}
	

	/**
	 * Register Meta boxes
	 *
	 */
	public function metaboxes()
	{

		/**
		 * Add Action
		 *
		 * Create meta boxes by adding action hook
		 */
		 
		add_action( 'add_meta_boxes', 'mg_testimonial_metabox_init' );
		
		function mg_testimonial_metabox_init() {
			add_meta_box( 'mg_testimonial_metaboxes', __( 'MG Testimonial', 'mg-testimonial' ), 'mg_testimonial_call_back', 'mg_testimonial' );
		}

		/**
		 * Outputs the content of the meta box
		 */
		function mg_testimonial_call_back( $post ) {
			wp_nonce_field( basename( __FILE__ ), 'mg_nonce' );
			$data = get_post_meta( $post->ID );
			?>
			<p>
				<label for="mg-class" class="mg-row-title"><?php _e( 'Class', 'mg-textdomain' )?></label>
				<select name="mg-class" id="mg-class">
					<option value="simple" <?php if ( isset ( $data['mg-class'] ) ) selected( $data['mg-class'][0], 'simple' ); ?>><?php _e( 'Simple', 'mg-textdomain' )?></option>';
					<option value="bordered" <?php if ( isset ( $data['mg-class'] ) ) selected( $data['mg-class'][0], 'bordered' ); ?>><?php _e( 'Bordered', 'mg-textdomain' )?></option>';
				</select>
			</p><!--	Show [Class] DROP DOWN LIST at meta box [MG Testimonial]		-->

			<p>
				<label for="mg-position" class="mg-row-title"><?php _e( 'Position', 'mg-textdomain' )?></label>
				<input type="text" name="mg-position" id="mg-position" value="<?php if ( isset ( $data['mg-position'] ) ) echo $data['mg-position'][0]; ?>" />
			</p><!--	Show [Position] INPUT BOX at meta box [MG Testimonial]		-->

			<p>
				<span class="mg-row-title"><strong><?php _e( 'Quote', 'mg-textdomain' )?></strong></span>
				<div class="mg-row-content">
					<label for="mg-quote">
						<input type="checkbox" name="mg-quote" id="mg-quote" value="yes" <?php if ( isset ( $data['mg-quote'] ) ) checked( $data['mg-quote'][0], 'yes' ); ?> />
						<?php _e( ' if you want to show Quote?', 'mg-textdomain' )?>
					</label>
				</div>
			</p><!--	Show [Quote] INPUT ITEM at meta box [MG Testimonial]		-->

			<p>
				<label for="mg-show" class="mg-row-title"><?php _e( 'Show', 'mg-textdomain' )?></label>
				<select name="mg-show" id="mg-show">
					<option value="none" <?php if ( isset ( $data['mg-show'] ) ) selected( $data['mg-show'][0], 'none' ); ?>><?php _e( 'None', 'mg-textdomain' )?></option>';
					<option value="iconfont" <?php if ( isset ( $data['mg-show'] ) ) selected( $data['mg-show'][0], 'iconfont' ); ?>><?php _e( 'Icon Font', 'mg-textdomain' )?></option>';
					<option value="image" <?php if ( isset ( $data['mg-show'] ) ) selected( $data['mg-show'][0], 'image' ); ?>><?php _e( 'Image', 'mg-textdomain' )?></option>';
				</select>
			</p><!--	Show [Show] DROP DOWN LIST at meta box [MG Testimonial]		-->
	
			<?php
		}



		/**
		 * Add Action
		 *
		 * Save or Update meta box values
		 */
		
		add_action( 'save_post', 'mg_meta_save' );
		
		function mg_meta_save( $post_id ) {
 
			// Checks save status
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );
			$is_valid_nonce = ( isset( $_POST[ 'mg_nonce' ] ) && wp_verify_nonce( $_POST[ 'mg_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
			// Exits script depending on save status
			if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
				return;
			}
 
			// Checks for input and sanitizes/saves if needed
			if( isset( $_POST[ 'mg-position' ] ) ) {
				update_post_meta( $post_id, 'mg-position', sanitize_text_field( $_POST[ 'mg-position' ] ) );
			}
			
			if( isset( $_POST[ 'mg-quote' ] ) ) {
				update_post_meta( $post_id, 'mg-quote', '1' );
			} else {
				update_post_meta( $post_id, 'mg-quote', '0' );
			}
			
			if( isset( $_POST[ 'mg-class' ] ) ) {
				update_post_meta( $post_id, 'mg-class', $_POST[ 'mg-class' ] );
			}else {
				update_post_meta( $post_id, 'mg-class', '' );
			}

			if( isset( $_POST[ 'mg-show' ] ) ) {
				update_post_meta( $post_id, 'mg-show', $_POST[ 'mg-show' ] );
			}else {
				update_post_meta( $post_id, 'mg-show', '' );
			}

		}
	}
}

?>