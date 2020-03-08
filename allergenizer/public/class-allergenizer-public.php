<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.johannorlund.com
 * @since      1.0.0
 *
 * @package    Allergenizer
 * @subpackage Allergenizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Allergenizer
 * @subpackage Allergenizer/public
 * @author     Johan Norlund <jn@trummis.com>
 */
class Allergenizer_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/allergenizer-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/allergenizer-public.js', array( 'jquery' ), $this->version, false );

	}

	private $found_allergens_in_content;

	/**
	 * Returns an array of all allergens from the Allergenizer settings field
	 *
	 * @since    1.0.0
	 */

	private function allergenizer_getAllergenList() {
		return explode("\n", str_replace("\r", "", get_option( $this->plugin_name . '_allergenelist' )));
	}

	/**
	 * Returns an array of allergens that are mentioned in a certain content
	 *
	 * @since    1.0.0
	 * @param    array     $arr    The array containing the allergens to search for
	 * @param    string    $str    The content to be searched
	 */

	private function allergenizer_extractAllergens($arr, $str) {
		$found_allergens = [];
		foreach($arr as $a) {
			if (stripos($str,$a) !== false) $found_allergens[] = $a;
		}
		return $found_allergens;
	}	

	/**
	 * If no shortcode is used to set content boundaries, modify The Content so that known allergens are highlighted.
	 *
	 * @since    1.0.0
	 * @param    string    $text    The content to be searched
	 */

	public function allergenizer_highlight($text) {

		global $found_allergens_in_content;
		
		if (!has_shortcode($text, "allergenizer")) {

			$search_for = $this->allergenizer_getAllergenList();

			$replace_with = preg_filter('/^/', '<strong>', $search_for);
			$replace_with = preg_filter('/$/', '</strong>', $replace_with);
		
			$text = str_replace($search_for, $replace_with, $text);
		}

		return $text;
		
	}

	/**
	 * This function is used by the allergenizer-list shortcode, and prints a list of found allergens
	 *
	 * @since    1.0.0
	 */

	public function allergenizer_shortcode_list($atts) {

		global $found_allergens_in_content;

		if (has_shortcode(get_the_content(), "allergenizer")) {
			$found_allergens = $found_allergens_in_content;
		} else {
			$search_for = $this->allergenizer_getAllergenList();
			$content = get_the_content();
			$found_allergens = $this->allergenizer_extractAllergens($search_for, $content);
		}
		$formatted_allergens = implode(", ", $found_allergens);

		ob_start();
		if (!empty($formatted_allergens)) {
			?>
			<div class="alert alert-warning" role="alert"><p>Receptet innehåller följande allergener: <?php echo $formatted_allergens;?></p></div> <?php
		} else {
		?>
		<div class="alert alert-success" role="alert"><p>Receptet är fritt från allergener.</p></div> <?php
		}
		return ob_get_clean();
	}

	/**
	 * This function is used by the allergenizer shortcode, and makes the found allergens <strong></strong>
	 *
	 * @since    1.0.0
	 */

	public function allergenizer_shortcode_highlight($atts, $content = NULL) {

		global $found_allergens_in_content;

		$search_for = $this->allergenizer_getAllergenList();
		$found_allergens = $this->allergenizer_extractAllergens($search_for, $content);
		$found_allergens_in_content = $found_allergens;
		$replace_with = preg_filter('/^/', '<strong>', $search_for);
		$replace_with = preg_filter('/$/', '</strong>', $replace_with);
	
		$filteredtext = str_replace($search_for, $replace_with, $content);

		return $filteredtext;

	}

}
