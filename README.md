# Allergenizer
An experimental plugin for Wordpress that highlights common food allergens. **PLEASE NOTE that this is a work in progress**, and by no means a complete and useable plugin :)

## Installation instructions
1. Put the allergenizer folder and all its contents inside the WP plugins folder
2. In WP admin -> Plugins: Locate Allergenizer and click on activate

## What does the plugin do?
Basically it highlights certain words in the_content :) EU FICs allergen provisions require food businesses to provide accessible, clear and accurate information about allergenic ingredients in prepacked and non-prepacked foods to enable consumers to make safe and informed food choices. This plugin is intended for food businesses, making it easier to meet the standards by automatically making allergens **bold**.

By default the whole content will be searched. Use the enclosing shortcodes (see below) to limit the search scope to a certain part of the content.

### Settings
The plugin creates a settings page (Settings -> Allergenizer), prefilled with common allergenic ingredients (in Swedish, for now). The default list can of course be edited. Make a new line for each ingredient.

### Shortcodes
Two types of shortcodes are available:

**[allergenizer] [/allergenizer]**
Defines the part of the content that should be checked for allergens. If not used, the search for allergenic ingredients will be performed on the whole content.

**[allergenizer-list]**
Inserts an info box listing all found allergens (so far prefixed by a Swedish message, however in a future release I might add the option to pass in a custom message)

## What happens where?
The plugin is built in Devin Vinsons boilerplate (https://github.com/DevinVinson/WordPress-Plugin-Boilerplate), responsible for the main file and folder structure. However, these are the main files where the magic happens:

**/includes/class-allergenizer.php**
Defines admin-specific hooks and public-facing site hooks

**/admin/class-allergenizer-admin.php**
The admin-specific functionality of the plugin.

- Add an options page under the Settings submenu
- Add "Settings" link to plugin list in WP
- Render the options page for the plugin
- Set default values for the allergens option field
- Register all related settings
- Render the textarea for allergenes_list option
- Sanitize the list of allergens before being saved to database
- Add a Meta box and populate with contents

**/public/partials/allergenizer-admin-display.php**
HTML template for the Settings page

**/public/class-allergenizer-public.php**
The public-facing functionality of the plugin.

- Construct array of all allergens from the Allergenizer settings field
- Construct array of allergens that are mentioned in a certain content
- Modify The Content so that found allergens are highlighted
- Shortcode callback function for [allergenizer-list], printing a list of found allergens
- Shortcode callback function for [allergenizer] [/allergenizer], highlighting found allergens within the shortcode scope

**/includes/class-allergenizer-deactivator.php**
Deletes related options from database on plugin deactivation.