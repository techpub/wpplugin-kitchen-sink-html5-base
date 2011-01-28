<?php
/**
 * Display navigation to next/previous post when applicable
 * DRY include/partial
 *
 * @author		zoe somebody
 * @link        http://beingzoe.com/zui/wordpress/kitchen_sink_theme
 * @license		http://en.wikipedia.org/wiki/MIT_License The MIT License
 * @package     KitchenSinkHTML5Themes
 * @subpackage  TwentyEleven
 * @version     0.4
 * @since       1.0
 */
?>

<aside class="wp_next_previous_more clearfix">
    <ul>
        <li class="previous"><?php previous_post_link( '%link', '<strong><span>&larr;</span> PREVIOUS:</strong><br />%title' ); ?></li>
        <li class="next"><?php next_post_link( '%link', '<strong>NEXT:<span>&rarr;</span></strong><br />%title' ); ?></li>
        <li class="more">
            <span class="more_title">Other recent posts...</span>
            <ul>
            <?php
                $next_post = get_adjacent_post(true,'',false);
                $previous_post = get_adjacent_post(true,'',true);
                if ( $next_post && $previous_post )
                    $exclude = $next_post->ID . "," . $previous_post->ID;
                else if ( $next_post )
                    $exclude = $next_post->ID;
                else
                    $exclude = $previous_post->ID;

                global $post, $id;

                $tmp_post = $post; /* save the original loop */
                $tmp_id = $id; /* save the original loop */

                $featured_posts = get_posts("numberposts=5&exclude=$exclude");
                foreach($featured_posts as $post) :
                    setup_postdata($post);
            ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php
                    $post = $tmp_post; /* restore the original loop */
                    $id = $tmp_id; /* restore the original loop */
                    endforeach;
            ?>
            </ul>
        </li>
    </ul>
</aside>
