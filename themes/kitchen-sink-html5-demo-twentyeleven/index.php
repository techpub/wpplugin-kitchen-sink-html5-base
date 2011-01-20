<?php
/**
 * Blog index main template
 *
 * @author		zoe somebody
 * @link        http://beingzoe.com/zui/wordpress/kitchen_sink_theme
 * @copyright	Copyright (c) 2011, zoe somebody, http://beingzoe.com
 * @license		http://en.wikipedia.org/wiki/MIT_License The MIT License
 * @package     WordPress
 * @subpackage  kitchenSinkTheme
 * @version     0.4
 * @since       0.1
 */

get_header(); 

?>

<section id="bd" class="clearfix hfeed">

<?php include( locate_template( array( 'loop.php' ) ) ); /* get_template_part( 'loop' ); */ ?>

</section><!-- #bd -->

<?php 
get_sidebar();
get_footer();
?>