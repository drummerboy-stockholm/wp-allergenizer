<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.johannorlund.com
 * @since      1.0.0
 *
 * @package    Allergenizer
 * @subpackage Allergenizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Allergenizer
 * @subpackage Allergenizer/admin
 * @author     Johan Norlund <jn@trummis.com>
 */
class Allergenizer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Allergenizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Allergenizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/allergenizer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Allergenizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Allergenizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/allergenizer-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Allergenizer Settings', $this->plugin_name ),
			__( 'Allergenizer', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	
	}
	
	/**
	 * Add "Settings" link to plugin list in WP
	 *
	 * @since  1.0.0
	 */	
	public function add_action_links( $links ) {
	   $settings_link = array(
		'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/allergenizer-admin-display.php';
	}

	/**
	 * Default values for the allergens option field
	 * FUTURE IMPLEMENTATION: Connect to API to fetch defaults
	 *
	 * @since  1.0.0
	 */
	private function get_default_allergens() {
	 $default_allergens = array(
		"blötdjur",
		"fisk",
		"torsk",
		"lax",
		"abborre",
		"gädda",
		"kräftdjur",
		"musslor",
		"räkor",
		"lupinbönor",
		"mjölk",
		"nötter",
		"mandel",
		"hasselnöt",
		"valnöt",
		"cashewnöt",
		"pekannöt",
		"paranöt",
		"pistagenöt",
		"macadamianöt",
		"selleri",
		"senap",
		"sesamfrön",
		"sojabönor",
		"vete",
		"råg",
		"korn",
		"havre",
		"spelt",
		"kamutvete",
		"durumvete",
		"ströbröd",
		"svaveldioxid",
		"sulfit",
		"ägg");

		return $default_allergens;
	 }
	
	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {

		add_settings_section(
			$this->plugin_name . '_general',
			__( 'General', $this->plugin_name ),
			array( $this, $this->plugin_name . '_general_cb' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->plugin_name . '_allergenelist',
			__( 'List of allergenes', $this->plugin_name ),
			array( $this, $this->plugin_name . '_allergenelist_cb' ),
			$this->plugin_name,
			$this->plugin_name . '_general',
			array( 'label_for' => $this->plugin_name . '_allergenelist' )
		);
		
		add_option($this->plugin_name . '_allergenelist', implode("\n", str_replace("\r", "", $this->get_default_allergens())));

		register_setting( $this->plugin_name, $this->plugin_name . '_allergenelist', array( $this, $this->plugin_name . '_sanitize_allergenelist' ) );
	}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function allergenizer_general_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', $this->plugin_name ) . '</p>';
	}

	/**
	 * Render the textarea for allergenes list option
	 *
	 * @since  1.0.0
	 */
	public function allergenizer_allergenelist_cb() {	
		$allergenes = get_option( $this->plugin_name . '_allergenelist' );
		?>
			<fieldset>
				<label>
					<textarea name="<?php echo $this->plugin_name . '_allergenelist' ?>" id="<?php echo $this->plugin_name . '_allergenelist' ?>" rows="7" cols="50" type="textarea"><?php echo $allergenes;?></textarea>
				</label>
			</fieldset>
		<?php
	}
	
	/**
	 * Sanitize the list of allergenes before being saved to database
	 *
	 * @param  string $allergenes $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function allergenizer_sanitize_allergenelist( $allergenes ) {
		// Check that our textarea option field contains no HTML tags - if so strip them out
		$allergenes =  wp_filter_nohtml_kses($allergenes);	
		return $allergenes; // return validated input
	}

	/**
	 * Add a Meta box
	 *
	 * @since  1.0.0
	 */
	public function allergenizer_render_metabox() {

		add_meta_box("allergenizer-meta", "Allergenizer shortcodes", array($this, "allergenizer_metabox_contents"), "page", "side");

	}

	/**
	 * Display Meta box contents
	 *
	 * @since  1.0.0
	 */
	public function allergenizer_metabox_contents() {

		
		echo "<p><strong>[allergenizer][/allergenizer]</strong><br> 
		Defines the part of the content that should be checked for allergens.</p>
		
		<p><strong>[allergenizer-list]</strong><br>
		Inserts an info box listing all found allergens.</p>";

		
	}

}
