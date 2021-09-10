<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/


// Add tutor shortcode
if ( !function_exists( 'tutor_plugin_blog_shortcode') ) {
    function tutor_plugin_blog_shortcode($atts) {
        $atts = shortcode_atts( array(
                // Individual params
                "style" => 'original',
                "group" => '',
                "order" => '',
                "count" => 3,
                "columns" => 3
            ), $atts , 'tutor_plugin_blog_shortcode'
        );
        $atts['count'] = max(1, (int) $atts['count']);
        $atts['columns'] = max(2, (int) $atts['columns']);

        ob_start();
        $query = new WP_Query(array(
            'post_type' => 'tutor',
            'posts_per_page' => $atts['count'],
            'order' => $atts['order'],
            'orderby' => 'rand'
        ));

        if ($query->have_posts()) {
            ?><div class="tp-container"<?php
            echo ($atts['columns'] > 1
                    ? ' data-slides="' . esc_attr($atts['columns']) . '"'
                    : '');
            ?>><?php

            if ($atts['columns'] > 1) {
                ?><div class="tp-columns-container"><?php
            }

            while ($query->have_posts()) : $query->the_post();
                if ($atts['columns'] > 1) {
                    ?><div class="<?php echo esc_html('tutor-plugin column-1-'.$atts['columns']); ?>"><?php
                }
                ?><div id="post-<?php the_ID(); ?>" <?php post_class();?>>
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
                </div></div><?php
            endwhile;
            wp_reset_postdata();
            ?></div></div><?php
        }

        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
    add_shortcode('tutor_plugin_blog_shortcode', 'tutor_plugin_blog_shortcode');
}


// Add tutor shortcode to Visual Composer
if (!function_exists('tutor_plugin_blog_shortcode_add_vc')) {
    function tutor_plugin_blog_shortcode_add_vc() {
        //Register shortcode Tutors in Visual Composer
        vc_map(array(
            "name" => esc_html__("Tutors", 'tutor-plugin'),
            "description" => esc_html__("Display team members from specified group", 'tutor-plugin'),
            "base" => "tutor_plugin_blog_shortcode",
            "content_element" => true,
            "category" => esc_html__('Tutor Plugin', 'tutor-plugin'),
            "show_settings_on_create" => true,
            "is_container" => false,
            "class" => "tutor_plugin_blog_shortcode",
            "icon" => 'icon-tutor-plugin',
            "params" => array(
                array(
                    "param_name" => "group",
                    "heading" => esc_html__("Group", 'tutor-plugin'),
                    "description" => esc_html__("Tutor group", 'tutor-plugin'),
                    "value" => array_merge(array(esc_html__('- Select tutor group -', 'tutor-plugin') => 0), array_flip(tutor_plugin_get_list_cats())),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "order",
                    "heading" => esc_html__("Order", 'tutor-plugin'),
                    "description" => esc_html__("Select sort order", 'tutor-plugin'),
                    "value" => array(
                        esc_html__('Descending', 'tutor-plugin') => 'desc',
                        esc_html__('Ascending', 'tutor-plugin') => 'asc'
                    ),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "count",
                    "heading" => esc_html__("Count", 'tutor-plugin'),
                    "description" => esc_html__("Specify number of tutors to display", 'tutor-plugin'),
                    "admin_label" => true,
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "columns",
                    "heading" => esc_html__("Columns", 'tutor-plugin'),
                    "description" => esc_html__("Specify number of columns. If empty - auto detect by tutors number", 'tutor-plugin'),
                    "admin_label" => true,
                    "value" => array(
                        '2' => '2',
                        '3' => '3',
                    ),
                    "type" => "dropdown"
                )
            ),
        ));
    }
    add_action( 'vc_before_init', 'tutor_plugin_blog_shortcode_add_vc' );
}


// Add tutor form shortcode
if ( !function_exists( 'tutor_plugin_form_shortcode' ) ) {
    function tutor_plugin_form_shortcode($atts='') {
        $atts = shortcode_atts( array(), $atts , 'tutor_plugin_form_shortcode');

        ob_start();
        ?>
        <div class="tp-form-shortcode-wrap">
            <div class="tp-form-header">
                <h6 class="tp-form-title"><?php echo esc_html__("Find Your", "tutor-plugin");?></h6>
                <h4 class="tp-form-title"><?php echo esc_html__("Tutor", "tutor-plugin");?></h4>
            </div>
            <div class="tp-form">
                <form role="search" action="<?php echo esc_url(site_url('/')); ?>" method="get" id="tutor-search-form" class="tutor-plugin tutor-plugin-form-search">
                    <input type="hidden" name="post_type" value="tutor" />
                    <input type="hidden" name="s" value="" />
                    <input type="text" name="n" placeholder="<?php echo esc_html__ ('Instrument', 'tutor-plugin')?>" value="<?php echo ((isset($_GET['n']) && !empty($_GET['n'])) ? esc_attr($_GET['n']) : ''); ?>" autocomplete="off"/>
                    <select name="g">
                        <option value="all" selected><?php esc_html_e("Select Level", "tutor-plugin")?></option>
                        <?php $tutor_group = get_terms('tutor_group', array(
                            //'orderby'    => 'count',
                            'hide_empty' => 0
                        ));
                        foreach( $tutor_group as $term ) {
                            ?><option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_attr($term->name); ?></option><?php
                        } ?>
                    </select>
                    <input type="text" name="z" min="0" pattern="[a-zA-Z0-9]*" placeholder="<?php echo esc_html__ ('Postcode', 'tutor-plugin')?>"  value="<?php echo ((isset($_GET['z']) && !empty($_GET['z'])) ? esc_attr($_GET['z']) : ''); ?>" autocomplete="off"/>
                    <div class="tp-form-button">
                        <button type="submit" value="<?php echo esc_html__ ('Find Now', 'tutor-plugin')?>"><?php echo esc_html__ ('Find Now', 'tutor-plugin')?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
    add_shortcode('tutor_plugin_form_shortcode', 'tutor_plugin_form_shortcode');
}


// Add tutor form shortcode to Visual Composer
if (!function_exists('tutor_plugin_form_shortcode_add_vc')) {
    function tutor_plugin_form_shortcode_add_vc() {
        //Register shortcode Tutor in Visual Composer
        vc_map(array(
            "name" => esc_html__("Tutor search form", 'tutor-plugin'),
            "description" => esc_html__("Display tutor search form", 'tutor-plugin'),
            "base" => "tutor_plugin_form_shortcode",
            "content_element" => true,
            "category" => esc_html__('Tutor Plugin', 'tutor-plugin'),
            "show_settings_on_create" => true,
            "is_container" => false,
            "class" => "tutor_plugin_form_shortcode",
            "icon" => 'icon-tutor-plugin',
            "params" => array(),
        ));
    }
    add_action( 'vc_before_init', 'tutor_plugin_form_shortcode_add_vc' );
}