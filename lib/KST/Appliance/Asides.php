<?php
/**
 * KST_Asides
 * Kitchen Sink Class: Asides
 *
 * Hassle free "sideblog" using a designated category for "aside" posts
 * Pass it your "aside" category and style the output however you like
 *
 * @author      zoe somebody
 * @link        http://beingzoe.com/
 * @license		http://en.wikipedia.org/wiki/MIT_License The MIT License
 * @package     KitchenSinkHTML5Base
 * @subpackage  Plugins:Blog
 * @version     0.1
 * @since       0.1
 * @todo        figure out how to get/pass the "asides" category unobtrusively
 * @todo        add options page (probably the solution to above)
 * @todo        research whatever other coolness needs to happen to pluginify this
 * @todo        rename to KSP (kitchen sink plugins) building a brand you know
 * @todo        check to see if this class has been loaded already (plugin or class in theme)
 * @todo        create some kind of method of passing a template in to output fully custom markup
 * @todo        have the asides categories created dynamically by kitchen
*/

class KST_Appliance_Asides extends KST_Appliance {

    /**#@+
     * @access      private
     * @var         string
    */
    private $current_day;       // Custom date string (dmY) of current post
    private $previous_day;      // Custom date string of  post
    private $category;          // The category the asides belong to; $name, $slug, OR $id
    /**#@-*/


    /**#@+
     * @access private
     * @var array
    */
    private $asides;            // Array of asides content
    /**#@-*/


    /**
     * Constructor
     *
     * @since       0.1
     * @param       required string|int|array $category
     * @uses        KST_Asides::asides
     * @uses        KST_Asides::should_we_do_this()
     * @uses        add_action() WP function
    */
    public function __construct(&$kitchen) {
        /*
        if ( !is_object( get_term_by( 'id', $category, 'category' ) ) )
            return; // No category so nothing to do
            */

        $appliance_options = array(
            'parent_slug'           => 'kst',
            'menu_title'            => 'Asides',
            'page_title'            => 'Asides sideblog and special custom formatting',
            'capability'            => 'manage_options',
            'view_page_callback'    => "auto",
            'options'               => array(
                    // Page title settings
                    'seo_main' => array(  "name"      => __('Aside Categories'),
                            "desc"      => __("
                                            <p><em>Choose the categories to use for sideblogs and special custom formatting.</em></p>
                                            <p>See \"Appearance &gt; Theme Help\" to learn more about asides.</p>
                                        "),
                            "type"      => "section",
                            "is_shut"   => FALSE ),

                    'layout_category_aside' => array(  "name"      => __('Asides Category'),
                            "desc"      => __('Pick the category to use as your sideblog'),
                            "type"      => "select_wp_categories",
                            "args"      => array( )
                            ),

                    'layout_category_gallery' => array(  "name"      => __('Gallery Category SLUG'),
                            "desc"      => __('Pick the category to use for gallery posts'),
                            "type"      => "select_wp_categories",
                            "args"      => array( )
                            )
                    )
        );

        // Add Help
        $appliance_help = array (
                array (
                    'page' => 'WordPress',
                    'section' => 'Special Formatting',
                    'title' => 'Appliance: Media: Asides',
                    'content_source' => array('KST_Appliance_Asides', 'helpBlogAsides')
                )
            );

         // Every kitchen needs the basic settings
        $appliance_settings = array(
                    'friendly_name'       => 'KST Appliance: Plugin: Blog: Asides',
                    'prefix'              => 'kst_asides',
                    'developer'           => 'zoe somebody',
                    'developer_url'       => 'http://beingzoe.com/',
                );

        // Declare as core
        $this->_is_core_appliance = TRUE;
        // Common appliance
        parent::_init($kitchen, $appliance_settings, $appliance_options, $appliance_help);

        return false; // We aren't ready for you yet!

        $this->category = $category;
        $this->asides = array();

        add_action( 'loop_start', array( &$this, 'should_we_do_this' ), 10, 2 );
    }


    /**
     * Set custom date string format
     *
     * @since       0.1
     * @param       string $date any date string
     * @return      string date
    */
    private function set_date($date) {
        return date( "mdY", strtotime($date) );
    }


    /**
     * Determine if we are in the "main" loop
     *
     * This is a placeholder function to figure out if we are in the REAL loop
     * or what so that we can output the appropriate markup
     *
     * @since       0.1
     * @uses        KST_Asides::do_in_loop()
     * @uses        KST_Asides::output()
     * @uses        add_action() WP function
    */
    public function should_we_do_this() {
        /* Figure out if we are in the "main" loop */
        /* Add WP actions */
        add_action( 'the_post', array( &$this, 'do_in_loop' ), 10, 2 );
        add_action( 'loop_end', array( &$this, 'output' ), 10 );
    }


    /**
     * Check the current $post_object object for an aside
     *
     * @version     0.1
     * @since       0.1
     * @global      object $wp_query used to determine if we are at the end of the loop and to increment the loop manually
     * @param       object $post_object The current $post in the loop
     * @uses        KST_Asides::set_date()
     * @uses        KST_Asides::add()
     * @uses        KST_Asides::output()
     * @uses        KST_Asides::current_day
     * @uses        in_category() WP function
     * @uses        the_post() WP function
    */
    public function do_in_loop( $post_object ) {
        global $wp_query;

        $this->current_day = $this->set_date( $post_object->post_date );

        if ( $this->current_day != $this->previous_day )
            $this->output( $post_object );


        if ( in_category( $this->category ) ) {

            $this->add( $post_object );
            $this->current_day = $this->set_date( $post_object->post_date );
            if ( ( $wp_query->current_post + 1 ) < $wp_query->post_count )
               the_post();

        }
    }


    /**
     * Add Aside
     *
     * Adds this post object to our asides array so we can output later as we like
     *
     * @version       0.1
     * @since         0.1
     * @param         required object $post_object    The $post object to be deferred in the loop and output later
     * @uses          KST_Asides::asides
    */
    public function add( $post_object ) {
        $this->asides[] = $post_object;
    }


    /**
     * Reset (empty) the asides array
     *
     * @version       0.1
     * @since         0.1
     * @uses          KST_Asides::asides
    */
    public function reset() {
        $this->asides = array();
    }


    /**
     * Output the asides
     *
     * @since 0.1
     * @param       required object $post_object
     * @param       optional boolean $is_new_day      Defaults to TRUE i.e. we have asides from the previous day so output them (is_new_day() in loop)
     * @param       optional boolean $reverse         Defaults to TRUE i.e. sort asides from oldest to newest
     * @uses        KST_Asides::set_date()
     * @uses        KST_Asides::reset()
     * @uses        KST_Asides::asides
     * @uses        get_permalink() WP function
    */
    public function output( $post_object, $is_new_day = 1, $reverse = 1 ) {
        if ( !empty( $this->asides ) && $is_new_day ) {

            if ( $reverse )
                $this->asides = array_reverse($this->asides);
            ?>
            <article id="post-aside-<?php $this->asides[0]->post_date; ?>" class="aside hentry">
            <?php
            foreach ( $this->asides as $post ) {
                echo '<div id="post-' . $post->ID . '" class="wp_entry"><header class="wp_entry_aside_meta"><a href="' . get_permalink($post->ID) . '"><span class="meta-nav">' . get_the_time('', $post) . ' &raquo;</span></a></header>' . wptexturize($post->post_content) . '</div>';
            }
            ?>
            </article>
            <?php
            $this->previous_day = $this->set_date( $post_object->post_date );
            $this->reset(); // Clear the array for next day

        }
    }


    /**
     * KST_Appliance_Help entry
     * Blog: Asides
     *
     * @since       0.1
    */
    public static function helpBlogAsides() {
        ?>
            <p><strong>
                NOTE: This is being rewritten (the appliance itself) and
                it's functionality will go way beyond what you think of
                when you think of asides. So this help entry is TBD...
            </strong></p>
            <p>
                <em>When you lean towards someone and tell them a little bit of information, you are making an "aside" comment. In blogs, you can do that on your blog by passing on small bits of information to your readers called Asides.
                Also known as remaindered links or linkblog, Asides were originally implemented by Matt Mullenweg, developer of WordPress, and it soon spread far and wide and became a very popular method of adding little bits of information to your blog.
                Learn more about Asides <a href='http://codex.wordpress.org/Adding_Asides'>here</a> and <a href='http://churchcrunch.com/a-genius-way-to-keep-your-blog-content-fresh-create-an-aside-category/'>here</a></em>
            </p>
            <h4>"Asides" Categories</h4>
            <p>Your theme has more than one type of "aside". </p>
            <ol>
                <li>ASIDES <em>(traditional as described above)</em></li>
                <li>GALLERY <em>(for posts consisting mostly pictures typically as thumbnails with a lightbox)</em></li>
            </ol>

        <?php
    }

}

