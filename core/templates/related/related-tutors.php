<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/



// Show related posts
if ( !function_exists('tutor_plugin_show_related_posts') ) {
    function tutor_plugin_show_related_posts($args=array(), $style=1, $title='') {
        $columns_related_tutor = (function_exists('tutor_plugin_output_related_tutors') ? tutor_plugin_output_related_tutors(3) : 3);

        $args = array_merge(array(
            'numberposts' => $columns_related_tutor,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'post__not_in' => array(),
            'category__in' => array(),
            'post_type' => 'tutor'      // Related posts only post type == tutor
        ), $args);

        $args['post__not_in'][] = get_the_ID();

        if (empty($args['category__in']) || is_array($args['category__in']) && count($args['category__in']) == 0) {
            $post_categories_ids = array();
            $post_cats = get_the_category(get_the_ID());
            if (is_array($post_cats) && !empty($post_cats)) {
                foreach ($post_cats as $cat) {
                    $post_categories_ids[] = $cat->cat_ID;
                }
            }
            $args['category__in'] = $post_categories_ids;
        }

        $recent_posts = wp_get_recent_posts( $args, OBJECT );

        if (is_array($recent_posts) && count($recent_posts) > 0) {
            ?>
            <section class="related_wrap">
                <h2 class="section title related_wrap_title"><?php
                    if (!empty($title))
                        echo esc_html($title);
                    else
                        esc_html_e('Featured Tutors', 'tutor-plugin');
                    ?></h2>
                <div class="columns_wrap posts_container">
                    <?php
                    global $post;
                    foreach( $recent_posts as $post ) {
                        setup_postdata($post);
                        ?><div class="column-1_<?php echo intval(max(2, $args['numberposts'])); ?>"><?php
                        include( plugin_dir_path(__FILE__) . 'related-tutors-item.php');
                        ?></div><?php
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </section>
            <?php
        }
    }
}