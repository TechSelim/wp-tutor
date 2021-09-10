/**
 Plugin Name: Tutor Plugin
 Description: Tutor Plugin - Premium plugin
 Author: Bobrovskyi Olexandr
 Version: 1.0
 */

jQuery(document).ready(function(){

    swiper_slides();

    function swiper_slides() {
        var count = jQuery('.tp-container.swiper-container').attr('data-slides');

        if (jQuery(document).width() < 740) {
            count = 2;
        }
        if (jQuery(document).width() < 470) {
            count = 1;
        }

        var swiper = new Swiper('.tp-container.swiper-container', {
            slidesPerView: count,
            slidesPerColumn: 1,
            paginationClickable: true,
            spaceBetween: 30,
            scrollbarDraggable: true,
            autoplay: 5000,
            scrollbarHide: false,
            scrollbarSnapOnRelease: true,
            scrollbar: '.swiper-scrollbar',
            loop: true,
            autoheight: true
        });
    }


    // Sorting
    jQuery(function( a ) {
        a( '.tutor-plugin-ordering' ).on( 'change', 'select.orderby', function() {
            a( this ).closest( 'form' ).submit();
        });
    });
});



/* Form js
----------------------------------------------------*/

jQuery(document).ready( function(e, container) {
    "use strict";

    container = jQuery('html');

    // Contact form validate and submit
    if (container.find('.tp_sc_form_form:not(.inited)').length > 0) {
        container.find('.tp_sc_form_form:not(.inited)')
            .addClass('inited')
            .submit(function(e) {
                "use strict";
                tutor_plugin_sc_form_validate(jQuery(this));
                e.preventDefault();
                return false;
            });
    }

    // Mark field as 'filled'
    jQuery('[class*="sc_input_hover_"] input, [class*="sc_input_hover_"] textarea, select').each(function() {
        "use strict";
        tutor_plugin_sc_form_mark_filled(jQuery(this));
    });
    jQuery('[class*="sc_input_hover_"] input, [class*="sc_input_hover_"] textarea, select').on('blur change', function() {
        "use strict";
        tutor_plugin_sc_form_mark_filled(jQuery(this));
    });
    jQuery('input, textarea, select').on('change', function() {
        "use strict";
        jQuery(this).removeClass('tutor_plugin_field_error');
    });
});

// Mark fields
function tutor_plugin_sc_form_mark_filled(field) {
    "use strict";
    if (field.val()!='')
        field.addClass('filled');
    else
        field.removeClass('filled');
}

// Validate form
function tutor_plugin_sc_form_validate(form){
    "use strict";
    var url = form.attr('action');
    if (url == '') return false;
    form.find('input').removeClass('tutor_plugin_error_field');
    var error = tutor_plugin_form_validate(form, {
        rules: [
            {
                field: "name",
                min_length: { value: 1,	 message: TUTOR_PLUGIN_STORAGE['msg_field_name_empty'] }
            },
            {
                field: "email",
                min_length: { value: 1,	 message: TUTOR_PLUGIN_STORAGE['msg_field_email_empty'] },
                mask: { value: TUTOR_PLUGIN_STORAGE['email_mask'], message: TUTOR_PLUGIN_STORAGE['msg_field_email_not_valid'] }
            },
            {
                field: "phone",
                min_length: { value: 1,	 message: TUTOR_PLUGIN_STORAGE['msg_field_phone_empty'] }
            }
        ]
    });

    if (!error && url!='#') {
        jQuery.post(url, {
            action: "send_sc_form",
            nonce: TUTOR_PLUGIN_STORAGE['ajax_nonce'],
            data: form.serialize()
        }).done(function(response) {
            "use strict";
            var rez = {};
            try {
                rez = JSON.parse(response);
            } catch(e) {
                rez = { error: TUTOR_PLUGIN_STORAGE['msg_ajax_error'] };
                console.log(response);
            }
            var result = form.find(".tutor_plugin_message_box").toggleClass("tutor_plugin_message_box_error", false).toggleClass("tutor_plugin_message_box_success", false);
            if (rez.error === '') {
                form.get(0).reset();
                result.addClass("tutor_plugin_message_box_success").html(TUTOR_PLUGIN_STORAGE['msg_send_complete']);
            } else {
                result.addClass("tutor_plugin_message_box_error").html(TUTOR_PLUGIN_STORAGE['msg_send_error'] + ' ' + rez.error);
            }
            result.fadeIn().delay(3000).fadeOut();
        });
    }
    return !error;
}

function tutor_plugin_form_validate(form, opt) {
    "use strict";
    // Default options
    if (typeof(opt.error_message_show)=='undefined')		opt.error_message_show = true;
    if (typeof(opt.error_message_time)=='undefined')		opt.error_message_time = 5000;
    if (typeof(opt.error_message_class)=='undefined')		opt.error_message_class = 'tutor_plugin_message_box_error';
    if (typeof(opt.error_message_text)=='undefined')		opt.error_message_text = 'Incorrect data in the fields!';
    if (typeof(opt.error_fields_class)=='undefined')		opt.error_fields_class = 'tutor_plugin_field_error';
    if (typeof(opt.exit_after_first_error)=='undefined')	opt.exit_after_first_error = false;
    // Validate fields
    var error_msg = '';
    form.find(":input").each(function() {
        "use strict";
        if (error_msg!='' && opt.exit_after_first_error) return;
        for (var i = 0; i < opt.rules.length; i++) {
            if (jQuery(this).attr("name") == opt.rules[i].field) {
                var val = jQuery(this).val();
                var error = false;
                if (typeof(opt.rules[i].min_length) == 'object') {
                    if (opt.rules[i].min_length.value > 0 && val.length < opt.rules[i].min_length.value) {
                        if (error_msg=='') jQuery(this).get(0).focus();
                        error_msg += '<p class="tutor_plugin_error_item">' + (typeof(opt.rules[i].min_length.message)!='undefined' ? opt.rules[i].min_length.message : opt.error_message_text ) + '</p>';
                        error = true;
                    }
                }
                if ((!error || !opt.exit_after_first_error) && typeof(opt.rules[i].max_length) == 'object') {
                    if (opt.rules[i].max_length.value > 0 && val.length > opt.rules[i].max_length.value) {
                        if (error_msg=='') jQuery(this).get(0).focus();
                        error_msg += '<p class="tutor_plugin_error_item">' + (typeof(opt.rules[i].max_length.message)!='undefined' ? opt.rules[i].max_length.message : opt.error_message_text ) + '</p>';
                        error = true;
                    }
                }
                if ((!error || !opt.exit_after_first_error) && typeof(opt.rules[i].mask) == 'object') {
                    if (opt.rules[i].mask.value != '') {
                        var regexp = new RegExp(opt.rules[i].mask.value);
                        if (!regexp.test(val)) {
                            if (error_msg=='') jQuery(this).get(0).focus();
                            error_msg += '<p class="tutor_plugin_error_item">' + (typeof(opt.rules[i].mask.message)!='undefined' ? opt.rules[i].mask.message : opt.error_message_text ) + '</p>';
                            error = true;
                        }
                    }
                }
                if ((!error || !opt.exit_after_first_error) && typeof(opt.rules[i].state) == 'object') {
                    if (opt.rules[i].state.value=='checked' && !jQuery(this).get(0).checked) {
                        if (error_msg=='') jQuery(this).get(0).focus();
                        error_msg += '<p class="tutor_plugin_error_item">' + (typeof(opt.rules[i].state.message)!='undefined' ? opt.rules[i].state.message : opt.error_message_text ) + '</p>';
                        error = true;
                    }
                }
                if ((!error || !opt.exit_after_first_error) && typeof(opt.rules[i].equal_to) == 'object') {
                    if (opt.rules[i].equal_to.value != '' && val!=jQuery(jQuery(this).get(0).form[opt.rules[i].equal_to.value]).val()) {
                        if (error_msg=='') jQuery(this).get(0).focus();
                        error_msg += '<p class="tutor_plugin_error_item">' + (typeof(opt.rules[i].equal_to.message)!='undefined' ? opt.rules[i].equal_to.message : opt.error_message_text ) + '</p>';
                        error = true;
                    }
                }
                if (opt.error_fields_class != '') jQuery(this).toggleClass(opt.error_fields_class, error);
            }

        }
    });
    if (error_msg!='' && opt.error_message_show) {
        var error_message_box = form.find(".tutor_plugin_message_box");
        if (error_message_box.length == 0) error_message_box = form.parent().find(".tutor_plugin_message_box");
        if (error_message_box.length == 0) {
            form.append('<div class="tutor_plugin_message_box"></div>');
            error_message_box = form.find(".tutor_plugin_message_box");
        }
        if (opt.error_message_class) error_message_box.toggleClass(opt.error_message_class, true);
        error_message_box.html(error_msg).fadeIn();
        setTimeout(function() { error_message_box.fadeOut(); }, opt.error_message_time);
    }
    return error_msg!='';
}



/* Popup
 ----------------------------------------------------*/

jQuery(document).ready( function(e, container) {
        "use strict";

    container = jQuery('html');

    jQuery('.sc_popup:not(.inited)').each(function() {
        "use strict";
        var id = jQuery(this).attr('id');
        if (!id) return;
        jQuery('a[href="#'+id+'"]').addClass('tutor_plugin_popup_link');
        jQuery(this).addClass('inited');
    });


    // Display lightbox on click on the popup link
    container.find(".tutor_plugin_popup_link:not(.inited)").addClass('inited').magnificPopup({
        type: 'inline',
        focus: 'input',
        closeBtnInside: true,
        callbacks: {
            // Will fire when this exact popup is opened
            // this - is Magnific Popup object
            open: function () {
                "use strict";
                jQuery(document).trigger('action.init_hidden_elements', [jQuery(this.content)]);
            },
            // resize event triggers only when height is changed or layout forced
            resize: function () {
                tutor_plugin_resize_actions();
            }
        }
    });
});

// Do actions when page scrolled
function tutor_plugin_resize_actions(cont) {
    "use strict";

    // Call theme/plugins specific action (if exists)
    //----------------------------------------------
    jQuery(document).trigger('action.resize_trx_addons', [cont]);
}




// Add buttons to input type=number
jQuery(document).ready( function() {
    jQuery('.tp-form-shortcode-wrap .type-number').append('<span class="q_inc"></span><span class="q_dec"></span>');
    jQuery('.tp-form-shortcode-wrap .type-number').on('click', '>span', function (e) {
        "use strict";
        var f = jQuery(this).siblings('input');

        if (f.attr('value') == '')
            f.attr('value', 0 );

        if (jQuery(this).hasClass('q_inc')) {
            f.val(Math.max(0, parseInt(f.val())) + 1);
        } else {
            f.val(Math.max(0, Math.max(0, parseInt(f.val())) - 1));
        }
        e.preventDefault();
        return false;
    });
});