<?php
/*

Plugin Name: Wp Tutor
Description: Wp Tutor - Free Plugin
Version: 1.0
Author: Selim Ahmad

*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );



// -----------------------------------------------------------------
// -- Custom post type registration
// -----------------------------------------------------------------

// Define Custom post type and taxonomy constants
if ( ! defined('TUTORS_PLUGIN_PT') )            define('TUTORS_PLUGIN_PT', 'tutor');
if ( ! defined('TUTORS_PLUGIN_ARCHIVE') )       define('TUTORS_PLUGIN_ARCHIVE', 'tutor');
if ( ! defined('TUTORS_PLUGIN_TAXONOMY') )      define('TUTORS_PLUGIN_TAXONOMY', 'tutor_tag');

// Plugin's core files
require_once 'core/core.plugin.php';
// Shortcodes
require_once 'core/shortcodes/shortcodes.php';
// Related tutors
require_once 'core/templates/related/related-tutors.php';


// Load required styles and scripts in the admin mode
if ( !function_exists( 'tutor_plugin_admin_enqueue_script' ) ) {
    function tutor_plugin_admin_enqueue_script() {
        // Admin styles
        wp_enqueue_style( 'tutor-plugin-style', plugins_url( '/css/tutor-plugin.css', __FILE__ ) );
    }
    add_action("admin_enqueue_scripts", 'tutor_plugin_admin_enqueue_script');
}

// Load required styles and scripts
if ( !function_exists( 'tutor_plugin_enqueue_script' ) ) {
    function tutor_plugin_enqueue_script() {

        // Styles and scripts
        wp_enqueue_style( 'tutor-plugin-style', plugins_url( '/css/tutor-plugin.css', __FILE__ ) );
        wp_enqueue_style( 'tutor-plugin-colors', plugins_url( '/css/colors.css', __FILE__ ) );
        wp_enqueue_style( 'tutor-plugin-fontello-style', plugins_url( '/css/fontello/css/fontello.css', __FILE__ ) );
        wp_enqueue_script( 'tutor-plugin-script', plugins_url( '/js/tutor-plugin.js', __FILE__ ), array('jquery'), null, true );

        // Magnific Popup
        wp_enqueue_style(  'magnific-popup', plugins_url('/js/magnific/magnific-popup.css',  __FILE__ ), array(), null );
        wp_enqueue_script( 'magnific-popup', plugins_url('/js/magnific/jquery.magnific-popup.js',  __FILE__ ), array('jquery'), null, true );

        // Swiper
        wp_enqueue_style(  'swiperslider', plugins_url('js/swiper/swiper.css',  __FILE__ ), array(), null );
        wp_enqueue_script( 'swiperslider', plugins_url('js/swiper/swiper.jquery.js',  __FILE__ ), array('jquery'), null, true );
    }
    add_action("wp_enqueue_scripts", 'tutor_plugin_enqueue_script');
}


// Register post type and taxonomy
if ( ! function_exists('tutor_plugin_cpt_tutor_init') ) {

    // Register Custom Post Type Tutor
    function tutor_plugin_cpt_tutor_init() {

        $labels = array(
            'name'                  => esc_html_x( 'Tutors', 'Post Type General Name', 'tutor-plugin' ),
            'singular_name'         => esc_html_x( 'Tutor', 'Post Type Singular Name', 'tutor-plugin' ),
            'menu_name'             => esc_html__( 'Tutors', 'tutor-plugin' ),
            'name_admin_bar'        => esc_html__( 'Tutor', 'tutor-plugin' ),
            'archives'              => esc_html__( 'Item Archives', 'tutor-plugin' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'tutor-plugin' ),
            'all_items'             => esc_html__( 'All tutors', 'tutor-plugin' ),
            'add_new_item'          => esc_html__( 'Add New Tutor', 'tutor-plugin' ),
            'add_new'               => esc_html__( 'Add New', 'tutor-plugin' ),
            'new_item'              => esc_html__( 'New Tutor', 'tutor-plugin' ),
            'edit_item'             => esc_html__( 'Edit Tutor', 'tutor-plugin' ),
            'update_item'           => esc_html__( 'Update Tutor', 'tutor-plugin' ),
            'view_item'             => esc_html__( 'View Tutor', 'tutor-plugin' ),
            'search_items'          => esc_html__( 'Search Tutor', 'tutor-plugin' ),
            'not_found'             => esc_html__( 'Not found', 'tutor-plugin' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'tutor-plugin' ),
            'featured_image'        => esc_html__( 'Featured Image', 'tutor-plugin' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'tutor-plugin' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'tutor-plugin' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'tutor-plugin' ),
            'insert_into_item'      => esc_html__( 'Insert into item', 'tutor-plugin' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'tutor-plugin' ),
            'items_list'            => esc_html__( 'Items list', 'tutor-plugin' ),
            'items_list_navigation' => esc_html__( 'Items list navigation', 'tutor-plugin' ),
            'filter_items_list'     => esc_html__( 'Filter items list', 'tutor-plugin' ),
        );
        $args = array(
            'label'                 => esc_html__( 'Tutor', 'tutor-plugin' ),
            'description'           => esc_html__( 'Tutor Description', 'tutor-plugin' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-admin-users',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'rewrite'               => array('slug' => 'tutors', 'with_front' => false),
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );
        register_post_type( 'tutor', $args );

    }
    add_action( 'init', 'tutor_plugin_cpt_tutor_init', 0 );


    // Register Custom Taxonomy
    function tutor_plugin_taxonomy_reg() {
        $labels_group = array(
            'name'                       => _x( 'Tutor group', 'Taxonomy General Name', 'tutor-plugin' ),
            'singular_name'              => _x( 'Group', 'Taxonomy Singular Name', 'tutor-plugin' ),
            'menu_name'                  => __( 'Tutor Group', 'tutor-plugin' ),
            'all_items'                  => __( 'All Groups', 'tutor-plugin' ),
            'parent_item'                => __( 'Parent Group', 'tutor-plugin' ),
            'parent_item_colon'          => __( 'Parent Group:', 'tutor-plugin' ),
            'new_item_name'              => __( 'New Group Name', 'tutor-plugin' ),
            'add_new_item'               => __( 'Add New Group', 'tutor-plugin' ),
            'edit_item'                  => __( 'Edit Group', 'tutor-plugin' ),
            'update_item'                => __( 'Update Group', 'tutor-plugin' ),
            'view_item'                  => __( 'View Group', 'tutor-plugin' ),
            'separate_items_with_commas' => __( 'Separate groups with commas', 'tutor-plugin' ),
            'add_or_remove_items'        => __( 'Add or remove groups', 'tutor-plugin' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'tutor-plugin' ),
            'popular_items'              => __( 'Popular Groups', 'tutor-plugin' ),
            'search_items'               => __( 'Search Groups', 'tutor-plugin' ),
            'not_found'                  => __( 'Not Found', 'tutor-plugin' ),
            'no_terms'                   => __( 'No groups', 'tutor-plugin' ),
            'items_list'                 => __( 'Groups list', 'tutor-plugin' ),
            'items_list_navigation'      => __( 'Groups list navigation', 'tutor-plugin' ),
        );
        $args_group = array(
            'labels'                     => $labels_group,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => false,
            'rewrite'                    => true
        );
        register_taxonomy( 'tutor_group', array( 'tutor' ), $args_group );

        $labels_subjects = array(
            'name'                       => _x( 'Tutor subjects', 'Taxonomy General Name', 'tutor-plugin' ),
            'singular_name'              => _x( 'Subjects', 'Taxonomy Singular Name', 'tutor-plugin' ),
            'menu_name'                  => __( 'Tutor Subjects', 'tutor-plugin' ),
            'all_items'                  => __( 'All Subjects', 'tutor-plugin' ),
            'parent_item'                => __( 'Parent Subjects', 'tutor-plugin' ),
            'parent_item_colon'          => __( 'Parent Subjects:', 'tutor-plugin' ),
            'new_item_name'              => __( 'New Subjects Name', 'tutor-plugin' ),
            'add_new_item'               => __( 'Add New Subjects', 'tutor-plugin' ),
            'edit_item'                  => __( 'Edit Subjects', 'tutor-plugin' ),
            'update_item'                => __( 'Update Subjects', 'tutor-plugin' ),
            'view_item'                  => __( 'View Subjects', 'tutor-plugin' ),
            'separate_items_with_commas' => __( 'Separate Subjects with commas', 'tutor-plugin' ),
            'add_or_remove_items'        => __( 'Add or remove subjects', 'tutor-plugin' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'tutor-plugin' ),
            'popular_items'              => __( 'Popular Subjects', 'tutor-plugin' ),
            'search_items'               => __( 'Search Subjects', 'tutor-plugin' ),
            'not_found'                  => __( 'Not Found', 'tutor-plugin' ),
            'no_terms'                   => __( 'No Subjects', 'tutor-plugin' ),
            'items_list'                 => __( 'Subjects list', 'tutor-plugin' ),
            'items_list_navigation'      => __( 'Subjects list navigation', 'tutor-plugin' ),
        );
        $args_subjects = array(
            'hierarchical' => false,
            'labels' => $labels_subjects,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'tutor_tag' ),
        );
        register_taxonomy('tutor_tag', array( 'tutor' ), $args_subjects);

        flush_rewrite_rules();
    }
    add_action( 'init', 'tutor_plugin_taxonomy_reg', 1 );
}

// Add the Tutor Meta Boxes
if ( ! function_exists('tutor_plugin_add_metaboxes') ) {
    function tutor_plugin_add_metaboxes() {
        add_meta_box('tutor_plugin_meta', esc_html__( 'Tutor info', 'tutor-plugin' ), 'tutor_plugin_show_meta', 'tutor', 'side', 'high');
    }
    add_action( 'add_meta_boxes', 'tutor_plugin_add_metaboxes' );
}

// Show the Tutor Metabox
if ( ! function_exists('tutor_plugin_show_meta') ) {
    function tutor_plugin_show_meta() {
        global $post;

        // Noncename needed to verify where the data originated
        ?><input type="hidden" name="tutor_plugin_meta" id="tutor_plugin_meta" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>"/><?php

        //Currency
        $tutor_plugin_currency = get_post_meta( $post->ID, 'tutor_plugin_currency', true  );
        ?>
            <p><label for="tutor_plugin_currency"><?php echo esc_html__('Currency of price:','tutor-plugin') ; ?></label></p>
            <input type="text" name="tutor_plugin_currency"  value="<?php echo esc_attr($tutor_plugin_currency); ?>" class="widefat" />
        <?php

        //Price
        $tutor_plugin_price = get_post_meta( $post->ID, 'tutor_plugin_price', true  );
        ?>
            <p><label for="tutor_plugin_price"><?php echo esc_html__('Price of tutor:','tutor-plugin') ; ?></label></p>
            <input type="text" name="tutor_plugin_price"  value="<?php echo esc_attr($tutor_plugin_price); ?>" class="widefat" />
        <?php

        //Period
        $tutor_plugin_period = get_post_meta( $post->ID, 'tutor_plugin_period', true  );
        ?>
            <p><label for="tutor_plugin_period"><?php echo esc_html__('Period for price:','tutor-plugin') ; ?></label></p>
            <input type="text" name="tutor_plugin_period"  value="<?php echo esc_attr($tutor_plugin_period); ?>" class="widefat" />
        <?php

        // Zipcode
        $tutor_plugin_zipcode = get_post_meta( $post->ID, 'tutor_plugin_zipcode', true  );
        ?>
        <p><label for="tutor_plugin_zipcode"><?php echo esc_html__('Zipcode:','tutor-plugin') ; ?></label></p>
        <input type="text" name="tutor_plugin_zipcode" min="0" value="<?php echo esc_attr($tutor_plugin_zipcode); ?>" class="widefat" />  
      <?php

        // Biography
        $tutor_plugin_biography = get_post_meta( $post->ID, 'tutor_plugin_biography', true  );
        ?>
        <p><label for="tutor_plugin_biography"><?php echo esc_html__('Biography:','tutor-plugin') ; ?></label></p>
        <textarea name="tutor_plugin_biography" rows="5" class="widefat" ><?php echo esc_attr($tutor_plugin_biography); ?></textarea>
        <?php

        // Rating of tutor
        $tutor_plugin_rating = get_post_meta( $post->ID, 'tutor_plugin_rating', true  );
        ?>
            <p><label for="tutor_plugin_rating"><?php echo esc_html__('Rating (how many stars):','tutor-plugin') ; ?></label></p>
            <select name="tutor_plugin_rating">
                <?php
                $option_values = array(1, 2, 3, 4, 5);
                foreach($option_values as $key => $value) {
                    if($value == $tutor_plugin_rating){ ?>
                        <option selected><?php echo esc_attr($value); ?></option>
                    <?php
                    } else { ?>
                        <option><?php echo esc_attr($value); ?></option>
                    <?php
                    }
                }
                ?>
            </select>
        <?php
    }
}

// Save the Tutor Metabox Data
if ( ! function_exists('tutor_plugin_save_meta') ) {
    function tutor_plugin_save_meta($post_id, $post) {

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        if (!isset($_POST["tutor_plugin_meta"]) || !wp_verify_nonce($_POST['tutor_plugin_meta'], plugin_basename(__FILE__))) {
            return $post->ID;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post->ID)) {
            return $post->ID;
        }

        // Check autosave
        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
            return $post_id;
        }

        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though.
        $tutor_plugin_meta['tutor_plugin_price']        = $_POST['tutor_plugin_price'];
        $tutor_plugin_meta['tutor_plugin_period']       = $_POST['tutor_plugin_period'];
        $tutor_plugin_meta['tutor_plugin_currency']     = $_POST['tutor_plugin_currency'];
        $tutor_plugin_meta['tutor_plugin_zipcode']      = $_POST['tutor_plugin_zipcode'];
        $tutor_plugin_meta['tutor_plugin_biography']    = $_POST['tutor_plugin_biography'];
        $tutor_plugin_meta['tutor_plugin_rating']       = $_POST['tutor_plugin_rating'];

        // Add values of $tutor_plugin_meta as custom fields
        foreach ($tutor_plugin_meta as $key => $value) {    // Cycle through the $tutor_plugin_meta array!
            if ($post->post_type == 'revision') return;     // Don't store custom data twice
            $value = implode(',', (array)$value);           // If $value is an array, make it a CSV (unlikely)
            if (get_post_meta($post->ID, $key, FALSE)) {    // If the custom field already has a value
                update_post_meta($post->ID, $key, $value);
            } else {                                        // If the custom field doesn't have a value
                add_post_meta($post->ID, $key, $value);
            }
            if (!$value) delete_post_meta($post->ID, $key); // Delete if blank
        }
    }
    add_action('save_post', 'tutor_plugin_save_meta', 1, 2); // save the custom fields
}

// Add new image size for featured images
if ( !function_exists( 'tutor_plugin_add_new_image_size' ) ) {
    function tutor_plugin_add_new_image_size()
    {
        add_image_size('tutor-archive', 740, 792, true);    //370 * 396
        add_image_size('tutor-single', 964, 996, true);     //482 * 498
    }
    add_action('init', 'tutor_plugin_add_new_image_size');
}

// Add search template
if ( !function_exists( 'tutor_plugin_search' ) ) {
    add_filter('template_include', 'tutor_plugin_search');
    function tutor_plugin_search($template){
        global $wp_query, $post;
        $post_type = get_query_var('post_type');
        if ($wp_query->is_search && $post_type == 'tutor') {
            $template = plugin_dir_path(__FILE__) . ('/core/templates/archive-search.php');
        }
        return $template;
    }
}

// Change standard single template for tutors
if ( !function_exists( 'tutor_plugin_single' ) ) {
    function tutor_plugin_single($template) {
        global $post;
        if (is_single() && $post->post_type == 'tutor') {
            $template = plugin_dir_path(__FILE__) . ('/core/templates/single-tutor.php');
        }
        return $template;
    }
    add_filter('single_template', 'tutor_plugin_single');
}

// Change standard category template for services categories (groups)
if ( !function_exists( 'tutor_plugin_taxonomy_template' ) ) {
    add_filter('taxonomy_template',	'tutor_plugin_taxonomy_template');
    function tutor_plugin_taxonomy_template( $template ) {
        if ( is_tax('tutor_taxonomy') || is_tax('tutor_tag') )
            $template = plugin_dir_path(__FILE__) . ('/core/templates/archive-tutor.php');
        return $template;
    }
}

// Change standard archive template for services posts
if ( !function_exists( 'tutor_plugin_archive_template' ) ) {
    add_filter('archive_template',	'tutor_plugin_archive_template');
    function tutor_plugin_archive_template( $template ) {
        if ( is_post_type_archive('tutor') )
            $template = plugin_dir_path(__FILE__) . ('/core/templates/archive-tutor.php');

        return $template;
    }
}


/* Form
----------------------*/


// Add variables in the frontend
if ( !function_exists( 'tutor_plugin_localize_scripts_front' ) ) {
    add_action("wp_footer", 'tutor_plugin_localize_scripts_front');
    function tutor_plugin_localize_scripts_front() {
        wp_localize_script( 'tutor-plugin-script', 'TUTOR_PLUGIN_STORAGE', array(
                // AJAX parameters
                'ajax_nonce'=> esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))),
                // E-mail mask to validate forms
                'email_mask' => '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$',
                // JS Messages
                'msg_ajax_error'			=> addslashes(esc_html__('Invalid server answer!', 'tutor-plugin')),
                'msg_magnific_loading'		=> addslashes(esc_html__('Loading image', 'tutor-plugin')),
                'msg_magnific_error'		=> addslashes(esc_html__('Error loading image', 'tutor-plugin')),
                'msg_error_like'			=> addslashes(esc_html__('Error saving your like! Please, try again later.', 'tutor-plugin')),
                'msg_field_name_empty'		=> addslashes(esc_html__("The name can't be empty", 'tutor-plugin')),
                'msg_field_email_empty'		=> addslashes(esc_html__('Too short (or empty) email address', 'tutor-plugin')),
                'msg_field_email_not_valid'	=> addslashes(esc_html__('Invalid email address', 'tutor-plugin')),
                'msg_field_phone_empty'		=> addslashes(esc_html__("The phone number can't be empty", 'tutor-plugin')),
                'msg_field_text_empty'		=> addslashes(esc_html__("The message text can't be empty", 'tutor-plugin')),
                'msg_search_error'			=> addslashes(esc_html__('Search error! Try again later.', 'tutor-plugin')),
                'msg_send_complete'			=> addslashes(esc_html__("Send message complete!", 'tutor-plugin')),
                'msg_send_error'			=> addslashes(esc_html__('Transmit failed!', 'tutor-plugin'))
            )
        );
    }
}

// AJAX handler for the send_form action
if ( !function_exists( 'tutor_plugin_sc_form_ajax_send_sc_form' ) ) {
    add_action('wp_ajax_send_sc_form',			'tutor_plugin_sc_form_ajax_send_sc_form');
    add_action('wp_ajax_nopriv_send_sc_form',	'tutor_plugin_sc_form_ajax_send_sc_form');
    function tutor_plugin_sc_form_ajax_send_sc_form() {

        if ( !wp_verify_nonce( tutor_plugin_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
            die();

        $response = array('error'=>'');

        if (!($contact_email = get_option('admin_email')))
            $response['error'] = esc_html__('Unknown admin email!', 'tutor-plugin');
        else {
            parse_str($_POST['data'], $post_data);
            $name_tutor	    = !empty($post_data['name_tutor']) ? stripslashes($post_data['name_tutor']) : '';
            $user_subject	= !empty($post_data['subject']) ? stripslashes($post_data['subject']) : '';
            $user_name	    = !empty($post_data['name']) ? stripslashes($post_data['name']) : '';
            $user_email	    = !empty($post_data['email']) ? stripslashes($post_data['email']) : '';
            $user_phone	    = !empty($post_data['phone']) ? stripslashes($post_data['phone']) : '';
            $user_msg	    = !empty($post_data['message']) ? stripslashes($post_data['message']) : '';

            // Attention! Strings below not need html-escaping, because mail is a plain text
            $subj = sprintf(__('Site %s - Contact form message from %s', 'tutor-plugin'), get_bloginfo('site_name'), $user_name);
            $msg = (!empty($name_tutor)     ? "\n".__('Tutor:', 'tutor-plugin')       .' '.trim($name_tutor) : '')
                .  (!empty($user_subject)   ? "\n".__('Subject:', 'tutor-plugin')     .' '.trim($user_subject) : '')
                .  (!empty($user_name)	    ? "\n".__('User Name:', 'tutor-plugin')   .' '.trim($user_name) : '')
                .  (!empty($user_email)     ? "\n".__('User E-mail:', 'tutor-plugin') .' '.trim($user_email) : '')
                .  (!empty($user_phone)     ? "\n".__('User Phone:', 'tutor-plugin')  .' '.trim($user_phone) : '')
                .  (!empty($user_msg)	    ? "\n".__('User Message:', 'tutor-plugin')."\n".trim($user_msg) : '')
                .  "\n\n............. " . get_bloginfo('site_name') . " (" . esc_url(home_url('/')) . ") ............";

            if (!wp_mail($contact_email, $subj, $msg)) {
                $response['error'] = esc_html__('Error send message!', 'tutor-plugin');
            }

            echo json_encode($response);
            die();
        }
    }
}

// Return GET or POST value
if (!function_exists('tutor_plugin_get_value_gp')) {
    function tutor_plugin_get_value_gp($name, $defa='') {
        $rez = $defa;
        $magic = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1;
        if (isset($_GET[$name])) {
            $rez = $magic ? stripslashes(trim($_GET[$name])) : trim($_GET[$name]);
        } else if (isset($_POST[$name])) {
            $rez = $magic ? stripslashes(trim($_POST[$name])) : trim($_POST[$name]);
        }
        return $rez;
    }
}