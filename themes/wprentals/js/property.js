/*global $, jQuery, ajaxcalls_vars, document, control_vars, window, map, setTimeout, Modernizr, property_vars*/
jQuery(window).scroll(function ($) {
    "use strict";
    var scroll = jQuery(window).scrollTop();
    if (scroll >= 400) {
        if (!Modernizr.mq('only all and (max-width: 1180px)')) {
            jQuery(".property_menu_wrapper_hidden").fadeIn(400);
            jQuery(".property_menu_wrapper").fadeOut(400);
        }
    } else {
        jQuery(".property_menu_wrapper_hidden").fadeOut(400);
        jQuery(".property_menu_wrapper").fadeIn(400);
    }
});


jQuery(window).scroll(function () {
    "use strict";
    if (!jQuery('#booktrigger').is(':in-viewport') ){
     
    //    jQuery('#booking_form_request').addClass('fixed_booking');
    }else{
      //  jQuery('#booking_form_request').removeClass('fixed_booking');
    }
}); 


jQuery(document).ready(function ($) {
    "use strict";
    var today, booking_error;
    booking_error = 0;
    today = new Date();
    

    if( $('#listing_description').outerHeight() > 169 ){
        $('#view_more_desc').show();
    }
    
    //180
    var sidebar_padding=0;
    
    $('#view_more_desc').click(function(event){
       
        
        var new_margin = 0;
        if( $(this).hasClass('lessismore') ){
         
            $(this).text(property_vars.viewmore).removeClass('lessismore');
            $('#listing_description .panel-body').css('max-height','129px').css('overflow','hidden');
          
            if ( !jQuery('#primary').hasClass('listing_type_1') ){
                $('#primary').css('margin-top',sidebar_padding);
            }
           
           
        }else{
            sidebar_padding=$('.listingsidebar').css('margin-top');
            
            $(this).text(property_vars.viewless).addClass('lessismore');
            $('#listing_description .panel-body').css('max-height','100%').css('overflow','initial');
            
            if ( !jQuery('#primary').hasClass('listing_type_1') ){
                new_margin = $('.property_header').outerHeight() - 390;
                new_margin = 180-new_margin;

                if(new_margin <180){
                    $('#primary').css('margin-top',new_margin+'px');
                }else{
                    $('#primary').css('margin-top','0px');
                }
            }
          
        }
        
        
    });
    
    
    ////////////////////////////////////////////////////////////////////////////
    /// tooltip property
    ////////////////////////////////////////////////////////////////////////////     
    $('#listing_main_image_photo').bind('mousemove', function (e) {
        $('#tooltip-pic').css({'top': e.pageY, 'left': e.pageX, 'z-index': '1'});
    });
    setTimeout(function () {
        $('#tooltip-pic').fadeOut("fast");
    }, 10000);
    /////////////////////////////////////////////////////////////////////////////////////////
    // booking form calendars
    /////////////////////////////////////////////////////////////////////////////////////////
    function show_booking_costs() {
        var guest_fromone, guest_no, fromdate, todate, property_id, ajaxurl;
        ajaxurl             =   control_vars.admin_url + 'admin-ajax.php';
        property_id         =   $("#listing_edit").val();
        fromdate            =   $("#start_date").val();
        todate              =   $("#end_date").val();
        guest_no            =   parseInt( jQuery('#booking_guest_no_wrapper').attr('data-value'),10);
        
     
        if (fromdate === '' || todate === '') {
           
            jQuery('#show_cost_form').remove();
            return;
        }

        guest_fromone       =   parseInt( jQuery('#submit_booking_front').attr('data-guestfromone'),10);
         
        if( isNaN(guest_fromone) ){
            guest_fromone=0;
        } 
              
        if(isNaN(guest_no)){
            guest_no=0;
        }
 
              
        if(guest_fromone===1 && guest_no<1 ){
            return;
        }
        
         
        jQuery('#booking_form_request_mess').empty().hide();
        if(fromdate>todate && todate!=='' ){
            jQuery('#booking_form_request_mess').show().empty().append(property_vars.nostart)
            jQuery('#show_cost_form').remove();
            return;
        }
             
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'            :   'wpestate_ajax_show_booking_costs',
                'fromdate'          :   fromdate,
                'todate'            :   todate,
                'property_id'       :   property_id,
                'guest_no'          :   guest_no,
                'guest_fromone'     :   guest_fromone
            },
            success: function (data) {
                jQuery('#show_cost_form').remove();
                jQuery('#add_costs_here').before(data);
                redo_listing_sidebar();
            },
            error: function (errorThrown) {
            }
        });
    }
    
    var booking_started=0;
    $('#end_date').change(function () {
        booking_started=1;
        show_booking_costs();
    });
    
    $('#start_date').change(function () {
        if( booking_started===1){
            show_booking_costs();
        }
    });
    
    
    $('#booking_form_request li').click(function (event){
       event.preventDefault();
       var guest_fromone, guest_overload;
        
       guest_overload      =   parseInt(jQuery('#submit_booking_front').attr('data-overload'),10);
       guest_fromone       =   parseInt( jQuery('#submit_booking_front').attr('data-guestfromone'),10);
       
        if( ( guest_overload===1 &&  booking_started===1) || guest_fromone===1 ){
            show_booking_costs();
        }
    });
    
    

    $("#start_date,#end_date").change(function (event) {
        $(this).parent().removeClass('calendar_icon');
    });

    /////////////////////////////////////////////////////////////////////////////////////////
    // contact host
    /////////////////////////////////////////////////////////////////////////////////////////
    function wpestate_show_contact_owner_form(booking_id, agent_id) {
        var  ajaxurl;
        ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'    :   'wpestate_ajax_show_contact_owner_form',
                'booking_id':   booking_id,// is actualy property id,
                'agent_id'  :   agent_id
            },
            success: function (data) {
                jQuery('body').append(data);
                jQuery('#contact_owner_modal').modal();
                enable_actions_modal_contact();
            },
            error: function (errorThrown) {
            }
        }); //end ajax
    }


    $('#contact_host,#contact_me_long').click(function () {
        var booking_id, agent_id;
        agent_id    =   0;
        booking_id  =   $(this).attr('data-postid');
        wpestate_show_contact_owner_form(booking_id, agent_id);
    });

    $('#contact_me_long_owner').click(function () {
        var agent_id, booking_id;
        booking_id =   0;
        agent_id  =   $(this).attr('data-postid');
        wpestate_show_contact_owner_form(booking_id, agent_id);
    });

    function enable_actions_modal_contact() {
        jQuery('#contact_owner_modal').on('hidden.bs.modal', function (e) {
            jQuery('#contact_owner_modal').remove();
        });
        var today =new Date().getTime();

        $("#booking_from_date").datepicker({
            dateFormat : "yy-mm-dd",
            minDate: today
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);

        $("#booking_from_date").change(function () {
            var  prev_date = new Date($('#booking_from_date').val());
            prev_date =wpestate_UTC_addDays( jQuery('#booking_from_date' ).val(),0 );
            
            jQuery("#booking_to_date").datepicker("destroy");
            jQuery("#booking_to_date").datepicker({
                dateFormat : "yy-mm-dd",
                minDate: prev_date
            }, jQuery.datepicker.regional[control_vars.datepick_lang]);
        });
        
        $("#booking_from_date").datepicker('widget').wrap('<div class="ll-skin-melon"/>'); 

        $("#booking_to_date").datepicker({
            dateFormat : "yy-mm-dd",
            minDate: today
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);

        $("#booking_to_date").datepicker('widget').wrap('<div class="ll-skin-melon"/>'); 

        $("#booking_from_date,#booking_to_date").change(function (event) {
            $(this).parent().removeClass('calendar_icon');
        });

        $('#submit_mess_front').click(function (event) {
            event.preventDefault();
            var ajaxurl, subject, booking_from_date, booking_to_date, booking_guest_no, message, nonce, agent_property_id, agent_id;
            ajaxurl              =   control_vars.admin_url + 'admin-ajax.php';
            booking_from_date       =   $("#booking_from_date").val();
            booking_to_date         =   $("#booking_to_date").val();
            booking_guest_no        =   $("#booking_guest_no").val();
            message                 =   $("#booking_mes_mess").val();
            agent_property_id       =   $("#agent_property_id").val();
            agent_id                =   $('#agent_id').val();
            nonce                   =   $("#security-register-mess-front").val();

            if (subject === '' || message === '') {
                  $("#booking_form_request_mess_modal").empty().append(property_vars.plsfill);
                return;
            }

       

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_mess_front_end',
                    'message'           :   message,
                    'booking_guest_no'  :   booking_guest_no,
                    'booking_from_date' :   booking_from_date,
                    'booking_to_date'   :   booking_to_date,
                    'agent_property_id' :   agent_property_id,
                    'agent_id'          :   agent_id,
                    'security-register' :   nonce
                },
                success: function (data) {
                    $("#booking_form_request_mess_modal").empty().append(data);
                    setTimeout(function () {
                        $('#contact_owner_modal').modal('hide');
                    }, 2000);
                    
                },
                error: function (errorThrown) {

                }

            });
        });
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    // submit booking front
    /////////////////////////////////////////////////////////////////////////////////////////
    $('#submit_booking_front').click(function (event) {
        event.preventDefault();
        var guest_number, guest_overload,guestfromone,max_guest;
        if (!check_booking_form()  || booking_error === 1) {
            return;
        }
        
        guest_number = jQuery('#booking_guest_no_wrapper').attr('data-value');
        guest_number = parseInt(guest_number,10);
        
        if (isNaN(guest_number)){
            guest_number=0;
        }
        
        max_guest       =   parseInt  (jQuery('#submit_booking_front').attr('data-maxguest'),10);
        guest_overload  =   parseInt  (jQuery('#submit_booking_front').attr('data-overload'),10);
        guestfromone    =   parseInt  (jQuery('#submit_booking_front').attr('data-guestfromone'),10);
        
        
        if (guestfromone===1 && guest_number < 1){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.noguest);
            return;
        }
       
       
        if(guest_number < 1){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.noguest);
            return;
        }
        
        if(guest_overload===0 && guest_number>max_guest){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.guestoverload+max_guest+' '+property_vars.guests);
            return;
        }
        
        
        
        if(property_vars.logged_in==="no"){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.notlog);
        }else{
            $('#booking_form_request_mess').show().empty().removeClass('book_not_available').append(property_vars.sending);
            redo_listing_sidebar();
            check_booking_valability();
        }

    });


    function check_booking_form() {
        var book_from, book_to;
        book_from         =   $("#start_date").val();
        book_to           =   $("#end_date").val();

        if (book_from === '' || book_to === '') {
            $('#booking_form_request_mess').empty().removeClass('book_not_available').append(property_vars.plsfill);
            return false;
        } else {
            return true;
        }
    }
/// end jquery
});