<?php
/**
 * KST_Options
 * Kitchen Sink Class: Options
 * Methods to rapidly create and use WordPress options (and just admin content pages)
 * Creates menu item and builds options/content page using options array you create
 *
 * @author		zoe somebody
 * @link        http://beingzoe.com/
 * @author		Scragz
 * @link        http://scragz.com/
 * @license		http://en.wikipedia.org/wiki/MIT_License The MIT License
 * @package     KitchenSinkHTML5Base
 * @subpackage  Core
 * @version     0.1
 * @link        http://beingzoe.com/zui/wordpress/kitchen_sink_theme
*/
class KST {

    /**
     * Set is_plugins_loaded to false until WP tells us that the plugins are loaded
     * Hook set in init.php
     *
     * @since       0.1
     * @access      private
     * @see         KST::pluginsAreLoaded()
    */
    protected static $_plugins_are_loaded = false;


    /**#@+
     * Core protected variables to keep tabs on all the kitchens
     *
     * @since       0.1
     * @access      protected
    */
    protected static $_extant_options; // Array of options that exist IF they were checked with $this->option_exists();
    protected static $_all_admin_pages; // Store all menus/pages from all registered kst kitchens
    protected static $_appliances; // Array of bundled appliances (classes, functions/helper libraries)
    /**#@-*/


    /**
     * Set static _appliances with bundled appliances array
     *
     * @since       0.1
     * @access      public
     * @param       required array $array
    */
    public function init_appliances() {
        self::$_appliances = kst_bundled_appliances();
    }


    /**
     * PUBLIC STATIC HELPER METHODS
    */

    /**
     * Set is_plugins_loaded via WP hook callback
     * Flag to know whether we are initializing a plugin or the active theme
     * Hook set in init.php
     *
     * @since       0.1
    */
    public static function pluginsAreLoaded() {
        self::$_plugins_are_loaded = true;
    }


    /**
     * Get is_plugins_loaded
     * Flag to know whether we are initializing a plugin or the active theme
     *
     * @since       0.1
    */
    public static function arePluginsLoaded() {
        return self::$_plugins_are_loaded;
    }


    /**
     * In order for the companion plugin concept to work we have to make sure that
     * Base loads first, so on activation we move it the start of the list.
     *
     * This might could be done better (I lifted this from somewhere??)
     *
     * @since       0.1
     * @see         http://wordpress.org/support/topic/how-to-change-plugins-load-order
     * @todo        Clean this up
     * @todo        Create a hook for KST plugin developers to use to make sure they load after this
    */
    public static function loadAsFirstPlugin() {
        global $active_plugins;
        // ensure path to this file is via main wp plugin path
        $wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
        $this_plugin = plugin_basename(trim($wp_path_to_this_file));
        $active_plugins = get_option('active_plugins');
        $this_plugin_key = array_search($this_plugin, $active_plugins);
        if ($this_plugin_key) { // if it's 0 it's the first plugin already, no need to continue
            array_splice($active_plugins, $this_plugin_key, 1);
            array_unshift($active_plugins, $this_plugin);
            update_option('active_plugins', $active_plugins);
        }
    }

}
