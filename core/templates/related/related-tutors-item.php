<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/

?><div class="tp-single tp-post<?php if (!has_post_thumbnail()){ echo ' without-image';} ?>">
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
                        ?><a href="<?php echo esc_url(get_term_link($term));?>" class="tp-tutor-subjects"><?php echo esc_html($term->name); ?></a><?php
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