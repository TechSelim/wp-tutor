<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/

// Return list of taxonomies
if ( !function_exists( 'tutor_plugin_get_list_cats' ) ) {
    function tutor_plugin_get_list_cats() {
        $list['tutor_taxonomy'] = array();
        $terms = get_terms( array(
            'taxonomy' => 'tutor_group',
            'order' => 'ASC',
            'hierarchical' => 1,
            'hide_empty' => false,
        ) );

        if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
                foreach ($terms as $term) {
                    $list['tutor_taxonomy'][$term->term_id] = $term->name;
                }
            }
        return $list['tutor_taxonomy'];
    }
}


if( !function_exists('tutor_plugin_change_sort_order') ){
    add_action('pre_get_posts', 'tutor_plugin_change_sort_order');
    function tutor_plugin_change_sort_order($query)
    {
        if ( is_archive() && (is_post_type_archive( 'tutor' ) || is_tax( 'tutor_tag' ) ) && ($query->is_main_query()) ) {

            if (isset($_GET['orderby'])) {
                if ($_GET['orderby'] == 'default') {
                    $query->set('post_type', 'tutor');
                } else if ($_GET['orderby'] == 'price') {
                    $query->set('post_type', 'tutor');
                    $query->set('meta_key', 'tutor_plugin_price');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'ASC');
                } else if ($_GET['orderby'] == 'price-desc') {
                    $query->set('post_type', 'tutor');
                    $query->set('meta_key', 'tutor_plugin_price');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                }
            }
        }
    }
}


// Check if Tutor Plugin installed and activated
if ( !function_exists('tutor_plugin_active') ) {
    function tutor_plugin_active() {
        return true;
    }
}



/* Breadcrumbs
------------------------------------------------------------------------------------- */

// Return true if it's services page
if ( !function_exists( 'tutuor_plugin_is_tutors_page' ) ) {
    function tutuor_plugin_is_tutors_page() {
        return defined('TRX_ADDONS_CPT_SERVICES_PT')
            && !is_search()
            && (
                (is_single() && get_post_type()==TUTORS_PLUGIN_PT)
                || is_post_type_archive(TUTORS_PLUGIN_ARCHIVE)
                || is_tax(TUTORS_PLUGIN_TAXONOMY)
            );
    }
}


// Return taxonomy for the current post type
if ( !function_exists( 'tutuor_plugin_tutors_post_type_taxonomy' ) ) {
    add_filter( 'trx_addons_filter_post_type_taxonomy',	'tutuor_plugin_tutors_post_type_taxonomy', 10, 2 );
    function tutuor_plugin_tutors_post_type_taxonomy($tax='', $post_type='') {
        if ( defined('TUTORS_PLUGIN_TAXONOMY') && $post_type == TUTORS_PLUGIN_PT )
            $tax = TUTORS_PLUGIN_TAXONOMY;
        return $tax;
    }
}


// Return link to the all posts for the breadcrumbs
if ( !function_exists( 'tutuor_plugin_tutors_get_blog_all_posts_link' ) ) {
    add_filter('trx_addons_filter_get_blog_all_posts_link', 'tutuor_plugin_tutors_get_blog_all_posts_link');
    function tutuor_plugin_tutors_get_blog_all_posts_link($link='') {
        if ($link=='') {
            if (tutuor_plugin_is_tutors_page() && !is_post_type_archive(TUTORS_PLUGIN_ARCHIVE))
                $link = '<a href="'.esc_url(get_post_type_archive_link( TUTORS_PLUGIN_PT )).'">'.esc_html__('All Tutors', 'tutor-plugin').'</a>';
        }
        return $link;
    }
}


// Return current page title
if ( !function_exists( 'tutuor_plugin_tutors_get_blog_title' ) ) {
    add_filter( 'trx_addons_filter_get_blog_title', 'tutuor_plugin_tutors_get_blog_title');
    function tutuor_plugin_tutors_get_blog_title($title='') {
        if (defined('TUTORS_PLUGIN_PT'))
        {
            if ( is_post_type_archive(TUTORS_PLUGIN_ARCHIVE) )
                $title = esc_html__('All Tutors', 'tutor-plugin');
        }
        return $title;
    }
}


// Search
if ( !function_exists( 'tutor_plugin_search_subjects' ) ) {
    function tutor_plugin_search_subjects($tutor_subject)
    {
        foreach ($tutor_subject as $term) {
            $tutor_subject = $term->name;
            $tutor_subject = !empty(stristr($tutor_subject, $_GET['n']));
            if ($tutor_subject === true)
                break;
        }

        return $tutor_subject;
    }
}
