<?php
/**
 * KST_Appliance_Options
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
 * @link        http://beingzoe.com/zui/wordpress/kitchen_sink/
 * @todo        replace WP_PLUGIN_DIR and any of these constants per Codex?
*/
class KST {

     /**#@+
     *
     * @since       0.1
     * @access      protected
     * @var         boolean
     * @see         KST::pluginsAreLoaded();
     * @see         KST_Kitchen_Theme::__construct()
     * @see         KST_Kitchen_plugin::__construct()
    */
    protected static $_plugins_are_loaded = false; // Set _plugins_are_loaded to false until WP tells us that the plugins are loaded - Hook set in init.php
    protected static $_is_core_only = TRUE; // Set _core_only to TRUE until a plugin or theme loads - Used to minimize impact of KST when running without a KST dependent kitchen
    /**#@-*/


    /**#@+
     * Various central urls mostly Help/support
     * Stored centrally in KST in case it needs to change because of Kitchen settings
     *
     * @since       0.1
     * @access      protected
     * @var         string
     * @see         KST::getHelpIndex()
    */
    protected static $_kst_theme_options_parent_slug = FALSE; // Central access to help
    protected static $_help_index = 'admin.php?page=kst_theme_help_section'; // Central access to help
    protected static $_help_developers = 'admin.php?page=kst_theme_help_developers'; // central access to developers page
    /**#@-*/


    /**
     * PUBLIC STATIC HELPER METHODS
    */

    /**
     * Get $_is_core_only
     *
     * @since       0.1
     * @access      public
     * @uses        KST::$_is_core_only
    */
    public static function isCoreOnly() {
        return self::$_is_core_only;
    }


    /**
     * Set $_is_core_only
     *
     * @since       0.1
     * @access      public
     * @uses        KST::$_is_core_only
    */
    public static function setIsCoreOnly($value) {
        self::$_is_core_only = $value;
    }


    /**
     * Get the current KST 'Theme Options' Parent Slug
     *
     * @since       0.1
     * @access      public
     * @uses        KST::$_kst_theme_options_parent_slug
    */
    public static function getKstThemeOptionsParentSlug() {
        return self::$_kst_theme_options_parent_slug;
    }


    /**
     * Set/store current KST 'Theme Options' Parent Slug
     * for use by all kitchens/appliances
     * Should only be accessed by KST core (please ;)
     *
     * @since       0.1
     * @access      public
     * @uses        KST::$_kst_theme_options_parent_slug
    */
    public static function setKstThemeOptionsParentSlug($value) {
        self::$_kst_theme_options_parent_slug = $value;
    }


    /**
     * Get the current help file index
     *
     * @since       0.1
     * @access      public
     * @uses        KST::$_help_index
    */
    public static function getDeveloperIndex() {
        return self::$_help_developers;
    }


    /**
     * Get the current help file index
     *
     * @since       0.1
     * @access      public
     * @uses        KST::$_help_index
    */
    public static function getHelpIndex() {
        return self::$_help_index;
    }


    /**
     * Set the current help file index
     *
     * @since       0.1
     * @access      public
     * @uses        KST::$_help_index
     * @param       required string $path_to_file
    */
    public static function setHelpIndex($path_to_file) {
        self::$_help_index = $path_to_file;
    }


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
     * @uses        plugin_basename() WP Function
     * @uses        get_option() WP Function
     * @uses        update_option() WP Function
     * @uses        WP_PLUGIN_DIR WP constant
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
