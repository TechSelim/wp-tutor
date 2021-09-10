<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/

get_header();

if (have_posts()) {

    do_action('tutor_plugin_action_start_archive');

    require_once 'orderby-tutor.php';

    ?><div class="tp-columns-container"><?php
    $columns_archive_tutor = (function_exists('tutor_plugin_loop_tutor_columns') ? tutor_plugin_loop_tutor_columns(3) : 3);

    if(have_posts()) : while(have_posts()) : the_post();

        ?><div class="<?php echo esc_html('tutor-plugin column-1-3'); ?>">

            <div id="post-<?php the_ID(); ?>" <?php post_class();?>>
                <div class="tp-single tp-post<?php if (!has_post_thumbnail()){ echo ' without-image';} ?>">
                    <div class="tp-featured">
                        <?php echo get_the_post_thumbnail(get_the_ID(), 'tutor-archive'); ?>
                        <div class="tp-featured-overlay">
                            <?php
                            $meta = get_post_meta(get_the_ID(), '', true);

                            //Price
                            if (isset($meta['tutor_plugin_price'][0])) {
                                if (($price = $meta['tutor_plugin_price'][0]) != '') {
                                    $currency = '';
                                    if (isset($meta['tutor_plugin_currency'][0])) {
                                        $currency = $meta['tutor_plugin_currency'][0];
                                    }
                                    ?><div class="tp-price">
                                    <span class="tp-present-price"><?php
                                        echo '<span class="small">'.esc_html($currency).'</span>' . esc_html($price); ?>
                                                </span>
                                    <?php if (isset($meta['tutor_plugin_period'][0])) {
                                        $period = $meta['tutor_plugin_period'][0];
                                        ?>
                                        <span class="tp-period-price"><?php
                                            echo esc_html($period); ?>
                                        </span>
                                    <?php } ?>
                                    </div><?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tp-content"><?php

                        // Subjects
                        ?><div class="tp-subjects"><?php
                            $tutor_tag = get_the_terms( get_the_ID(), 'tutor_tag');
                            $i=0;
                            $len = count($tutor_tag);
                            if (!empty($tutor_tag)) {
                                foreach( $tutor_tag as $term ){
                                    if($i>0 && $i<3){ ?>, <?php }
                                    if ($i<3) {
                                        ?><span class="tp-tutor-subjects"><?php echo esc_html($term->name); ?></span><?php
                                    } else if ($i == $len - 1) {
                                        ?><span class="tp-tutor-subjects"><?php
                                        echo ' ' . esc_html__("and", "tutor-plugin") . ' '.esc_html($len-3).' ' . esc_html__("other", "tutor-plugin");  ?></span><?php
                                    }
                                    $i++;
                                }
                            }
                            ?></div>
                        <div class="tp-title">
                            <a class="tp-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                        <div class="tp-read-more">
                            <a class="tp-title-read-more sc_button_hover_slide_left" href="<?php the_permalink(); ?>"><?php echo esc_html__('View Tutor', 'tutor-plugin'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div><?php

    endwhile; endif;

    ?></div><?php

    do_action('tutor_plugin_action_end_archive');

} else {
    do_action('tutor_plugin_action_none_archive');
}

get_footer();
?>