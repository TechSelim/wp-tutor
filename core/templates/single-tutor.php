<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/

get_header();


if (have_posts()) : while (have_posts()) : the_post();
        $meta = get_post_meta(get_the_ID(), '', true);
?>
        <div class="tp-single-wrap">
            <div class="tp-featured"><?php
                                        the_post_thumbnail(
                                            'tutor-single',
                                            array(
                                                'alt' => get_the_title(),
                                                'itemprop' => 'image'
                                            )
                                        );
                                        ?>
                <style>
                    div.gmw-single-location-wrapper h3.gmw-sl-title {
                        display: none !important;
                    }
                </style>
                <?php

                // Contact form
                ?>
                <div class="info_tutor">
                    <div class="tp-single-contact-form">
                        <div class="sc_item_button sc_button_wrap">
                            <a href="#popup_message_form" class="sc_button sc_button_default sc_button_size_normal sc_button_icon_left sc_button_hover_slide_left">
                                <span class="sc_button_text"><span class="sc_button_title"><?php echo esc_html('Message Me', 'tutor-plugin'); ?></span></span>
                            </a>
							<?php if( is_user_logged_in() ): ?>
							<a href="/appointments/" class="sc_button sc_button_default sc_button_size_normal sc_button_icon_left sc_button_hover_slide_left">
                                <span class="sc_button_text"><span class="sc_button_title"><?php echo esc_html('Book Now', 'tutor-plugin'); ?></span></span>
                            </a>
							<?php else: ?>
							<a href="/create-student-account/" class="sc_button sc_button_default sc_button_size_normal sc_button_icon_left sc_button_hover_slide_left">
                                <span class="sc_button_text"><span class="sc_button_title"><?php echo esc_html('Book Now', 'tutor-plugin'); ?></span></span>
                            </a>
							<?php endif; ?>
                        </div>
                        <div id="popup_message_form" class="sc_popup sc_popup_default mfp-hide">
                            <div class="tp-single-contact-form-wrap">
                                <div id="sc_form_single-contact-form" class="sc_form sc_form_default sc_align_default">

                                    <form class="tp_sc_form_form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                                        <div class="sc_form_details tp-columns-container">
                                            <input type="text" name="name_tutor" id="name_tutor" value="<?php the_title(); ?>" aria-required="true" placeholder="<?php the_title(); ?>">
                                            <div class="column-1-2">
                                                <label class="sc_form_field sc_form_field_name required">
                                                    <span class="sc_form_field_wrap"><input type="text" name="name" id="name" value="" aria-required="true" placeholder="<?php esc_html_e('Your name *', 'tutor-plugin'); ?>"></span>
                                                </label>
                                            </div>
                                            <div class="column-1-2">
                                                <label class="sc_form_field sc_form_field_email required">
                                                    <span class="sc_form_field_wrap"><input type="text" name="email" id="email" value="" aria-required="true" placeholder="<?php esc_html_e('Your e-mail *', 'tutor-plugin'); ?>"></span>
                                                </label>
                                            </div>
                                            <div class="column-1-2">
                                                <label class="sc_form_field sc_form_field_phone required">
                                                    <span class="sc_form_field_wrap"><input type="text" name="phone" id="phone" value="" placeholder="<?php esc_html_e('Your phone *', 'tutor-plugin'); ?>"></span>
                                                </label>
                                            </div>
                                            <div class="column-1-2">
                                                <label class="sc_form_field sc_form_field_subject required">
                                                    <select name="subject" id="subject">
                                                        <?php
                                                        $tutor_group = get_the_terms($post->ID, 'tutor_tag');
                                                        foreach ($tutor_group as $term) {
                                                        ?><option value="<?php echo esc_attr($term->name); ?>"><?php echo esc_attr($term->name); ?></option><?php
                                                                                                                                                        } ?>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                        <label class="sc_form_field sc_form_field_message">
                                            <span class="sc_form_field_wrap"><textarea name="message" id="message" placeholder="<?php esc_html_e('Your message', 'tutor-plugin'); ?>"></textarea></span>
                                        </label>
                                        <div class="sc_form_field sc_form_field_button"><button class="sc_button_hover_slide_left">Send Message</button></div>
                                        <div class="tutor_plugin_message_box sc_form_result"></div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <h4> Other Info</h4>
                    <?php /*?><strong>Address:</strong> <?php the_field( 'tutor_plugin_zipcode' ); ?> <br/><?php */ ?>
                    <strong>Qualification(s):</strong> <?php the_field('tutor_qualification'); ?><br />
                    <strong>Teaching Preferences:</strong> <?php the_field('tutor_preference'); ?><br />
                    <!-- <strong>DBS Checked:</strong> <?php the_field('tutor_dbs'); ?> <br /> -->

                </div>
            </div>
            <?php

            ?><div class="tp-info">
                <?php //Title 
                ?>
                <h2 class="tp-single-title"><?php the_title(); ?></h2>
                <?php


                //Price
                if (isset($meta['tutor_plugin_price'][0])) {
                    if (($price = $meta['tutor_plugin_price'][0]) != '') {
                        $currency = '';
						$_SESSION['tutor_plugin_price'] = $price;
						
                        if (isset($meta['tutor_plugin_currency'][0])) {
                            $currency = $meta['tutor_plugin_currency'][0];
                        }
                ?>

                        <div class="tp-price">
                            <span class="tp-present-price"><?php
                                                            echo '<span class="small">' . esc_html($currency) . '</span>' . esc_html($price); ?>
                            </span>
                            <?php if (isset($meta['tutor_plugin_period'][0])) {
                                $period = $meta['tutor_plugin_period'][0];
                            ?>
                                <span class="tp-period-price">

                                    <?php
                                    echo esc_html($period); ?>
                                </span>
                            <?php } ?>
                        </div><?php
                            }
                        }

                        //Rating
                        if (isset($meta['tutor_plugin_rating'][0])) {
                            if (($rating_count = $meta['tutor_plugin_rating'][0]) != '') { ?>
								<div class="tp-rating">
									<?php for ($temp_count = 0; $temp_count < $rating_count; $temp_count++) { ?>
									<span class="tp-rating-star tutor-icon-bookmark-star"></span>
									<?php } ?>
								</div>
						<?php
							}
						}
						?>

                <div class="tp-subjects"><?php
                                            $tutor_tag = get_the_terms(get_the_ID(), 'tutor_tag');
                                            $i = 0;
                                            $len = count($tutor_tag);
                                            if (!empty($tutor_tag)) {
                                                echo '<span class="label">' . esc_html__('Instrument(s): ', 'tutor-plugin') . '</span>';
                                                foreach ($tutor_tag as $term) {
                                                    if ($i > 0) { ?> <?php }
                                                                        ?><a href="<?php echo esc_url(get_term_link($term)); ?>" class="tp-tutor-subjects"><?php echo esc_html($term->name); ?></a><?php
                                                                                                                                                                                                    $i++;
                                                                                                                                                                                                }
                                                                                                                                                                                            }
                                                                                                                                                                                                    ?></div>


                <?php
                //Biography
               /* if (isset($meta['tutor_plugin_biography'][0])) {
                if (($tutor_plugin_biography = $meta['tutor_plugin_biography'][0]) != '') {
                    ?><div class="tp-biography"><?php
                        echo esc_html($tutor_plugin_biography);
                    ?></div><?php
                }
            }*/
                ?>
			
			

                <div class="tp-biography">
                    <?php the_content(); ?>
                </div>

            </div>
        </div>

        <div style="font-size: 20px; margin-top: 20px;">
            DBS Checked: <strong> <?php the_field('tutor_dbs'); ?> </strong>
        </div> <br />

        <div class="tp-single-content post_content">

            <?php /*the_field( 'tutor_map' );*/ ?>


            <?php echo do_shortcode('[gmw_single_location map_width="100%"]') ?>


            <?php  /*the_content();*/ ?>

        </div>

<h1><?php echo $_SESSION['tutor_plugin_price']; ?></h1>
<?php

        // If comments are open or we have at least one comment, load up the comment template.
			 if( is_user_logged_in() ): 
				if (comments_open() || get_comments_number()) {
					comments_template();
				}
			 endif; 
        


        // Related posts.
        // You can specify style 1|2 in the second parameter
        tutor_plugin_show_related_posts(array(
            'orderby' => 'post_date',    // put here 'rand' if you want to show posts in random order
            'order' => 'DESC'
        ), 1);




    endwhile;
endif;

get_footer();
?>