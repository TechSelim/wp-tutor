<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/

/* Template Name: Custom Search */
get_header();

$count = 0;
if ( have_posts() ) {

    require_once 'orderby-tutor.php';

    ?><div class="tp-columns-container"><?php
    while ( have_posts() ) : the_post();

        // Post data
        $meta = get_post_meta( get_the_ID(), '', true);
        $tutor_group = get_the_terms( get_the_ID(), 'tutor_group');
        $tutor_subject = get_the_terms( get_the_ID(), 'tutor_tag');

		if(!empty($tutor_group)){			
			foreach( $tutor_group as $term ) {
				$tutor_group = isset($term->slug) ? $term->slug : '';
			}		
		} else {
			$tutor_group = '';
		}

        $show_tutor = (
                ((isset($_GET['g']) && !empty($_GET['g']) && $_GET['g'] != 'all')
                    ? (($_GET['g'] == $tutor_group) ? true : false)
                    : true)
            &&  ((isset($_GET['z']) && !empty($_GET['z']))
                ? ((isset($meta['tutor_plugin_zipcode']) && !empty($meta['tutor_plugin_zipcode']))
                    ? (($_GET['z'] == $meta['tutor_plugin_zipcode'][0] ? true : false))
                    : false)
                : true)
            &&  ((isset($_GET['n']) && !empty($_GET['n']) && $_GET['n'] != 'all')
                ? tutor_plugin_search_subjects($tutor_subject)
                : true)
        );

        if ($show_tutor) {
            $count++;
        } else {
            continue;
        }

        $columns_archive_tutor = (function_exists('tutor_plugin_loop_tutor_columns') ? tutor_plugin_loop_tutor_columns(3) : 3);
        ?><div class="<?php echo esc_html('tutor-plugin column-1-3'); ?>"> <?/*php .$columns_archive_tutor*/?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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
    endwhile;
    ?></div>

<?php } else if ($count != 0){
    ?>
    <article <?php post_class( 'post_item_single post_item_404 post_item_none_search' ); ?>>
        <div class="post_content">
            <h1 class="page_title"><?php esc_html_e( 'No results', 'tutor-plugin' ); ?></h1>
            <div class="page_info">
                <h3 class="page_subtitle"><?php echo sprintf(esc_html__("We're sorry, but your search \"%s\" did not match", 'tutor-plugin'), get_search_query()); ?></h3>
                <p class="page_description"><?php echo wp_kses_data( sprintf( __("Can't find what you need? Take a moment and do a search below or start from <a href='%s'>our homepage</a>.", 'tutor-plugin'), esc_url(home_url('/')) ) ); ?></p>
            </div>
        </div><?php
        ?>
    </article>
    <?php
}
if ($count == 0) {
    ?>
    <article <?php post_class( 'post_item_single post_item_404 post_item_none_search' ); ?>>
        <div class="post_content">
            <h1 class="page_title"><?php esc_html_e( 'No results', 'tutor-plugin' ); ?></h1>
            <div class="page_info">
                <h3 class="page_subtitle"><?php echo sprintf(esc_html__("We're sorry, but your search \"%s\" did not match", 'tutor-plugin'), get_search_query()); ?></h3>
                <p class="page_description"><?php echo wp_kses_data( sprintf( __("Can't find what you need? Take a moment and do a search below or start from <a href='%s'>our homepage</a>.", 'tutor-plugin'), esc_url(home_url('/')) ) ); ?></p>
            </div>
        </div><?php
        ?>
    </article>
    <?php
}

get_footer();
?>