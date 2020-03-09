<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.johannorlund.com
 * @since      1.0.0
 *
 * @package    Allergenizer
 * @subpackage Allergenizer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Allergenizer
 * @subpackage Allergenizer/includes
 * @author     Johan Norlund <jn@trummis.com>
 */
class Allergenizer_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('allergenizer_allergenelist');
	}

}
