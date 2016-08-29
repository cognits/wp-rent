/*global $, jQuery, ajaxcalls_vars, document, control_vars, window, map, setTimeout, Modernizr, Blazy, location, google, options, show_pins, show_login_form, adv_search_click, restart_js_after_ajax,start_filtering_ajax,  start_filtering_ajax_map, toggleStreetView, show_capture, new_open_close_map*/
var has_transparent = 0;
var componentForm;
var price_array;

var my_custom_curr_symbol  =   getCookie('my_custom_curr_symbol');
var my_custom_curr_coef    =   parseFloat(getCookie('my_custom_curr_coef'), 10);
var my_custom_curr_pos     =   parseFloat(getCookie('my_custom_curr_pos'), 10);
var my_custom_curr_cur_post =   getCookie('my_custom_curr_cur_post');

function getCookie(cname) {
    var name, ca, i, c;
    name = cname + "=";
    ca = document.cookie.split(';');
    for (i = 0; i < ca.length; i = i + 1) {
        c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1);
        if (c.indexOf(name) === 0) return c.substring(name.length,c.length);
    }
    return "";
}   
    
(function($) {
  $.fn.nodoubletapzoom = function() {
      $(this).bind('touchstart', function preventZoom(e) {
        var t2 = e.timeStamp
          , t1 = $(this).data('lastTouch') || t2
          , dt = t2 - t1
          , fingers = e.originalEvent.touches.length;
        $(this).data('lastTouch', t2);
        if (!dt || dt > 500 || fingers > 1) return; // not double-tap

        e.preventDefault(); // double tap - prevent the zoom
        // also synthesize click events we just swallowed up
        $(this).trigger('click').trigger('click');
      });
  };
})(jQuery);


     
var widgetId1,widgetId2,widgetId3,widgetId4;

var wpestate_onloadCallback = function() {

    // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
    // The id of the reCAPTCHA widget is assigned to 'widgetId1'.

    if(  document.getElementById('capthca_register') ){
        widgetId1 = grecaptcha.render('capthca_register', {
            'sitekey' : control_vars.captchakey,
            'theme' : 'light'
        });
    }

    if(  document.getElementById('mobile_register_menu') ){
        widgetId2 = grecaptcha.render('mobile_register_menu', {
            'sitekey' : control_vars.captchakey,
            'theme' : 'light'
        });
    }


    if(  document.getElementById('widget_register_menu') ){
        
        widgetId3 = grecaptcha.render('widget_register_menu', {
            'sitekey' : control_vars.captchakey,
            'theme' : 'light'
        });
      
    }

    if(  document.getElementById('capthca_register_sh') ){
        widgetId4 = grecaptcha.render('capthca_register_sh', {
            'sitekey' : control_vars.captchakey,
            'theme' : 'light'
        });
        
    }


}
    
jQuery(window).scroll(function ($) {
    "use strict";
    var scroll = jQuery(window).scrollTop();
    if (scroll >= 10) {
        if (!Modernizr.mq('only all and (max-width: 1025px)')) {
            jQuery('.logo').addClass('miclogo');
            
            if( !jQuery(".header_wrapper").hasClass('is_half_map') ){
                jQuery(".header_wrapper").addClass("navbar-fixed-top");
                jQuery(".header_wrapper").addClass("customnav");

                if (jQuery(".header_wrapper").hasClass('transparent_header')) {
                    has_transparent = 1;
                    jQuery(".header_wrapper").removeClass('transparent_header');
                    if(control_vars.transparent_logo!==''){
                        if(control_vars.normal_logo!==''){
                            jQuery(".logo img").attr('src',control_vars.normal_logo);
                        }else{
                            jQuery(".logo img").attr('src',control_vars.path+"/img/logo.png");
                        }
                    }
                }
            }
           

          
            jQuery('.barlogo').show();
            jQuery('#user_menu_open').hide();
        }
        jQuery('.backtop').addClass('islive');
    } else {
        jQuery(".header_wrapper").removeClass("navbar-fixed-top");
        jQuery(".header_wrapper").removeClass("customnav");

        if (has_transparent === 1) {
            jQuery(".header_wrapper").addClass('transparent_header');
            if(control_vars.transparent_logo!==''){
                jQuery(".logo img").attr('src',control_vars.transparent_logo);
            }
        }

        jQuery('.backtop').removeClass('islive');
        jQuery('.contactformwrapper').addClass('hidden');
        jQuery('.barlogo').hide();
        jQuery('#user_menu_open').hide();
        jQuery('.logo').removeClass('miclogo');
    }
});


jQuery('#google_map_prop_list_sidebar').scroll(function () {
    "use strict";
    var scroll = jQuery('#google_map_prop_list_sidebar').scrollTop();
    if (scroll >= 110) {
        jQuery('#advanced_search_map_list_hidden').show();
        jQuery('#advanced_search_map_list').removeClass('move_to_fixed');
    } else {
        jQuery('#advanced_search_map_list_hidden').hide();
    }
});

jQuery(window).resize(function () {
    "use strict";
    jQuery('#mobile_menu').hide('10');
});

Number.prototype.format = function (n, x) {
    "use strict";
    var re;
    re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};



function  show_capture_vertical(){
    "use strict";
   
    
    var position, slideno, slidedif, tomove, curentleft, position;
    jQuery('#googleMapSlider').hide();
    position=parseInt( jQuery('#carousel-listing .carousel-inner .active').index(),10);
    jQuery('#carousel-indicators-vertical  li').removeClass('active');
    jQuery('#carousel-listing  .caption-wrapper span').removeClass('active');
    jQuery("#carousel-listing  .caption-wrapper span[data-slide-to='"+position+"'] ").addClass('active');
    jQuery("#carousel-listing  .caption-wrapper span[data-slide-to='"+position+"'] ").addClass('active');
   
    jQuery("#carousel-indicators-vertical  li[data-slide-to='"+position+"'] ").addClass('active');
    
    slideno=position+1;

    slidedif=slideno*92;
    

    if( slidedif > 338){
        tomove=338-slidedif;
        tomove=tomove;
        jQuery('#carousel-indicators-vertical').css('top',tomove+"px");
    }else{
        position = jQuery('#carousel-indicators-vertical').css('top',tomove+"px").position();
        curentleft = position.top;

        if( curentleft < 0 ){
            tomove = 0;
            jQuery('#carousel-indicators-vertical').css('top',tomove+"px");
        }

    }
}



function owner_insert_book() {
    "use strict";
    var fromdate, todate, listing_edit, nonce, ajaxurl, comment, booking_guest_no;
    ajaxurl             =   control_vars.admin_url + 'admin-ajax.php';
    fromdate            =   jQuery("#start_date").val();
    todate              =   jQuery("#end_date").val();
    listing_edit        =   jQuery('#listing_edit').val();
    comment             =   jQuery("#book_notes").val();
    booking_guest_no    =   jQuery('#booking_guest_no_wrapper').attr('data-value');
    //nonce               =   $('#security-register-booking_front').val();

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action'            :   'wpestate_ajax_add_booking',
            'fromdate'          :   fromdate,
            'todate'            :   todate,
            'listing_edit'      :   listing_edit,
            'comment'           :   comment,
            'booking_guest_no'  :   booking_guest_no,
            'security'          :   nonce
        },
        success: function (data) {
        
            jQuery('.has_future').each(function () {
                jQuery('#start_date, #end_date').val('');
                jQuery('#booking_guest_no_wrapper').html('any<span class="caret caret_filter"></span>');           
            });
           
            jQuery('#booking_form_request_mess').empty().removeClass('book_not_available').text(control_vars.bookconfirmed);
            redo_listing_sidebar();
            
           
        },
        error: function (errorThrown) {
        }
    });
}


function redo_listing_sidebar(){ // 638
    if ( jQuery('#primary').hasClass('listing_type_1') ){
        return;
    }
    
    var newmargin=0;
    var current_height= jQuery('#booking_form_request').outerHeight();
    if (current_height > 525 ){
        newmargin = current_height-525 + 180 ;
        // 525  default booking_form_request
        // listing sidebar margin-top
        jQuery('#primary').css('margin-top',newmargin+'px');
    }
    
}





function check_booking_valability() {
    "use strict";
    var book_from, book_to, listing_edit, ajaxurl,internal;
    internal        =   0;
    book_from       =   jQuery('#start_date').val();
    book_to         =   jQuery('#end_date').val();
    listing_edit    =   jQuery('#listing_edit').val();
    ajaxurl         =   control_vars.admin_url + 'admin-ajax.php';

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action'            :   'wpestate_ajax_check_booking_valability',
            'book_from'         :   book_from,
            'book_to'           :   book_to,
            'listing_id'        :   listing_edit,
            'internal'          :   internal
        },
        success: function (data) {
       
            if (data === 'run') {
                owner_insert_book();
            }else if(data === 'stopcheckinout'){
                  jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.stopcheckinout);
            }else if(data === 'stopcheckin'){
                  jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.stopcheckin);
            }else if(data === 'stopdays'){
                jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.mindays);
            }else {
                jQuery('#booking_form_request_mess').empty().addClass('book_not_available').text(control_vars.bookdenied);
              
            }
        },
        error: function (errorThrown) {
        }
    });
}


function owner_insert_book_internal() {
    "use strict";
    var fromdate, todate, listing_edit, nonce, ajaxurl, comment, booking_guest_no;
    ajaxurl             =   control_vars.admin_url + 'admin-ajax.php';
    fromdate            =   jQuery("#start_date_owner_book").val();
    todate              =   jQuery("#end_date_owner_book").val();
    listing_edit        =   jQuery('#listing_edit').val();
    comment             =   jQuery("#book_notes").val();
    booking_guest_no    =   jQuery('#booking_guest_no_wrapper').attr('data-value');
    //nonce               =   $('#security-register-booking_front').val();

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action'            :   'wpestate_ajax_add_booking',
            'fromdate'          :   fromdate,
            'todate'            :   todate,
            'listing_edit'      :   listing_edit,
            'comment'           :   comment,
            'booking_guest_no'  :   booking_guest_no,
            'confirmed'         :   1,
            'security'          :   nonce
        },
        success: function (data) {
          
            jQuery('.has_future').each(function () {
                jQuery(this).removeClass('calendar-reserved-stop');
                jQuery(this).removeClass('calendar-reserved-start');
                jQuery(this).removeClass('calendar-reserved-stop-visual');
                jQuery(this).removeClass('calendar-reserved-start-visual');
                jQuery('#owner_reservation_modal').modal('hide');
                
               
                jQuery('.booking-calendar-wrapper-in .calendar-selected').removeClass('calendar-selected');
                jQuery('#book_dates').empty().text(ajaxcalls_vars.reserve);
                jQuery('#book_notes').val('');
            });
            jQuery('#booking_form_request_mess').empty().text(control_vars.bookconfirmed);
        },
        error: function (errorThrown) {
        }
    });
}

function check_booking_valability_internal() {
    "use strict";
    var book_from, book_to, listing_edit, ajaxurl,internal;
    jQuery('#book_dates').empty().text(ajaxcalls_vars.saving);
    book_from       =   jQuery('#start_date_owner_book').val();
    book_to         =   jQuery('#end_date_owner_book').val();
    listing_edit    =   jQuery('#listing_edit').val();
    ajaxurl         =   control_vars.admin_url + 'admin-ajax.php';
    internal        =   1;
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action'            :   'wpestate_ajax_check_booking_valability_internal',
            'book_from'         :   book_from,
            'book_to'           :   book_to,
            'listing_id'        :   listing_edit,
            'internal'          :   internal
        },
        success: function (data) {
           
            if (data === 'run') {
                owner_insert_book_internal();
            } else {
                jQuery('#book_dates').empty().text(ajaxcalls_vars.reserve);
            }
        },
        error: function (errorThrown) {
        }
    });
}

componentForm = {
    establishment: 'long_name',
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    administrative_area_level_2: 'long_name',
    country: 'long_name',
    postal_code: 'short_name',
    postal_code_prefix: 'short_name',
    neighborhood: 'long_name'
};




function wpestate_UTC_addDays(date, days) {
    
    var result = new Date(date);
  
    var now_utc = new Date(result.getUTCFullYear(), result.getUTCMonth(), result.getUTCDate(),  result.getUTCHours(), result.getUTCMinutes(), result.getUTCSeconds());
    var new_day=parseInt(result.getUTCDate())+1 + parseInt(days);
    now_utc.setDate(new_day);
    return now_utc;
}


function check_in_out_enable(in_date, out_date) {
    "use strict";
    var today, prev_date,prev_date_string;
    today = new Date();
  
    jQuery("#" + in_date+',#'+out_date).blur();
    
    jQuery("#" + in_date).datepicker({
        dateFormat : "yy-mm-dd",
        minDate: today
    }, jQuery.datepicker.regional[control_vars.datepick_lang]).focus(function () {
			jQuery(this).blur()
		}).datepicker('widget').wrap('<div class="ll-skin-melon"/>');



    jQuery("#" + in_date).change(function () {
        
     
        prev_date = wpestate_UTC_addDays( jQuery('#' + in_date).val(),0 );
        
    
        
        if( in_date==='check_in_list'){
            jQuery("#check_out_list").val('');
        }
        
        jQuery("#" + out_date).removeAttr('disabled');
        jQuery("#" + out_date).datepicker("destroy");
        jQuery("#" + out_date).datepicker({
            dateFormat : "yy-mm-dd",
            minDate: prev_date
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);
    });


    jQuery("#" + out_date).datepicker({
        dateFormat : "yy-mm-dd",
        minDate: today
    }, jQuery.datepicker.regional[control_vars.datepick_lang]).focus(function () {
			jQuery(this).blur()
    });

    jQuery("#" + in_date + ",#" + out_date).change(function (event) {
        jQuery(this).parent().removeClass('calendar_icon');
    });
  
}


price_array=[];
if(control_vars.custom_price!==''){
    price_array     = JSON.parse (control_vars.custom_price);
}

booking_array=[];
if( control_vars.booking_array!=='' && control_vars.booking_array.length!==0 ){
    booking_array   = JSON.parse (control_vars.booking_array);
}

cleaning_fee_per_day            =   control_vars.cleaning_fee_per_day;        
city_fee_per_day                =   control_vars.city_fee_per_day;
price_per_guest_from_one        =   control_vars.price_per_guest_from_one;
checkin_change_over             =   control_vars.checkin_change_over;
checkin_checkout_change_over    =   control_vars.checkin_checkout_change_over;
min_days_booking                =   control_vars.min_days_booking;
extra_price_per_guest           =   control_vars.extra_price_per_guest;
price_per_weekeend              =   control_vars.price_per_weekeend;

mega_details=[];
if(control_vars.mega_details!==''){
    mega_details                    =   JSON.parse(control_vars.mega_details);
}

weekdays=[];
if(control_vars.weekdays!==''){
    weekdays                        =   JSON.parse(control_vars.weekdays);
}


function check_in_out_enable2(in_date, out_date) {
    "use strict";
    var today, prev_date,selected_date,selected_min_days,who_is;
    today = new Date();

    

    jQuery("#" + in_date).datepicker({
        dateFormat : "yy-mm-dd",
        minDate: today,
        beforeShowDay:function (date){
            return enableAllTheseDays(date, in_date );
        }    
    }, jQuery.datepicker.regional[control_vars.datepick_lang]).focus(function () {
                jQuery(this).blur()
            }).datepicker('widget').wrap('<div class="ll-skin-melon"/>');




    jQuery("#" + in_date).change(function () {
     
        prev_date = new Date(jQuery('#' + in_date).val());
      
        selected_date       =   wpestate_get_unix_time(prev_date);
        selected_min_days   =   wpestate_return_min_days_value(selected_date);
     
        if (selected_min_days>0){
            prev_date =wpestate_UTC_addDays( jQuery('#' + in_date).val(),selected_min_days-1 );
        }else{
            prev_date =wpestate_UTC_addDays( jQuery('#' + in_date).val(),0 );
        }
      
        jQuery("#" + out_date).val('');
        jQuery("#" + out_date).removeAttr('disabled');
        jQuery("#" + out_date).datepicker("destroy");
        jQuery("#" + out_date).datepicker({
            dateFormat : "yy-mm-dd",
            minDate: prev_date,
            beforeShowDay:function (date){
            return enableAllTheseDays(date, out_date );
        } 
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);
        
    });


    jQuery("#" + out_date).datepicker({
        dateFormat : "yy-mm-dd",
        minDate: today,
        beforeShowDay:function (date){
            return enableAllTheseDays(date, out_date );
        } 
    }, jQuery.datepicker.regional[control_vars.datepick_lang]).focus(function () {
            jQuery(this).blur()
    });

    jQuery("#" + in_date).click(function(){
        who_is=1;
      
    });
    
    jQuery("#" + out_date).click(function(){
        who_is=2;
      
    });

    jQuery("#" + in_date + ",#" + out_date).change(function (event) {
        jQuery(this).parent().removeClass('calendar_icon');
    });
    
     
    jQuery("html").on("mouseenter",".wpestate_calendar ", function() {
        var price,unit_class;
        price = jQuery(this).attr('title');
        unit_class = jQuery(this).attr('class');
        unit_class = unit_class.match(/\d+/);
        
      
        if( unit_class ){
            unit_class = unit_class[0];
            
            jQuery(this).append('<span class="hover_price">'+price+'</span>');
            if(who_is===1){
                wpestate_show_min_days_reservation(unit_class);
            }
        }
    });


    jQuery("html").on("mouseleave",".wpestate_calendar", function() {
        jQuery(this).find('.hover_price').remove();
        wpestate_remove_min_days_reservation(this);
    });
  
}






function wpestate_addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate()+1 +days);
    return result;
}


function wpestate_return_min_days_value(item){
    var step, minim_days;
    step=0;
    minim_days=0;
    
    if( mega_details[item] != undefined  ){
        minim_days = parseInt( mega_details[item]['period_min_days_booking'] ,10 );
    }else if(min_days_booking !=undefined && min_days_booking>0){
        minim_days=parseInt (min_days_booking,10);
    }
    
    return minim_days;
}







function wpestate_show_min_days_reservation(item){
    var step, minim_days ,classad,item_count;
    step=0;
    minim_days=0;

    if( mega_details[item] != undefined  ){
        minim_days = parseInt( mega_details[item]['period_min_days_booking'] ,10 );
    }else if(min_days_booking !=undefined && min_days_booking>0){
        minim_days=parseInt (min_days_booking,10);
    }
    
    classad='date'+item;
    item_count=parseInt(item,10);
    if(minim_days>0){
        while(step<minim_days){
            step++;
            jQuery('.'+classad).addClass('minim_days_reservation');
            item_count = item_count+(24*60*60);//next day
            classad='date'+item_count;
        }
        
    }

   
}


function wpestate_remove_min_days_reservation(item){
    jQuery('.wpestate_calendar').removeClass('minim_days_reservation');
}









function wpestate_get_unix_time(date){
    unixtime = new Date(date).getTime()/1000; 
//    unixtime1= unixtime - date.getTimezoneOffset()*60;
    unixtime1_key = String(unixtime);
    return unixtime1_key;
}
 
 

var start_reservation,end_reservation,reservation_class;

function  wpestate_return_weekeend_price(week_day,unixtime1_key){
    display_price='';
    if(mega_details[unixtime1_key] !== undefined){
        if (  parseFloat (mega_details[unixtime1_key]['period_price_per_weekeend'],10)!==0 ){
            display_price = parseFloat (mega_details[unixtime1_key]['period_price_per_weekeend'],10);
        }             
    }else if( parseFloat(price_per_weekeend,10)!==0 ){
        display_price = parseFloat(price_per_weekeend,10);
    } 

    return display_price;  
                
}

function enableAllTheseDays(date, from_who) {
    "use strict";
    var  today,block_check_in, block_check_in_check_out, week_day, display_price, received_date,unixtime, unixtime1,unixtime1_key,from_css_class ; 
    
    received_date       =   new Date(date);
    today               =   Math.floor(Date.now() / 1000);
    unixtime            =   received_date.getTime()/1000; 
    unixtime1           =   unixtime - date.getTimezoneOffset()*60;
    unixtime1_key       =   String(unixtime1);
    reservation_class   =   '';
    display_price       =   '';
    from_css_class      =   '';
    block_check_in      =   0;
    week_day            =   received_date.getDay();
    block_check_in_check_out    =   0;

    if(week_day===0){
        week_day=7;
    }
  
    
    // establish the price per weekend
    ////////////////////////////////////////////////////////////////////////////
    
    if ( control_vars.setup_weekend_status === '0' && (week_day==6 || week_day==7) ){
        display_price = wpestate_return_weekeend_price(week_day,unixtime1_key);
    }else if(control_vars.setup_weekend_status === '1'  && (week_day==5 || week_day==6) ){
       display_price = wpestate_return_weekeend_price(week_day,unixtime1_key);
    }else if(control_vars.setup_weekend_status === '2' && (week_day==5 || week_day==6 || week_day==7)){
       display_price = wpestate_return_weekeend_price(week_day,unixtime1_key);
    }
    
   
    // establish the price per guest mode
    ////////////////////////////////////////////////////////////////////////////
    
    if( parseInt(price_per_guest_from_one,10)===1 ){
//        display_price = '<div class="hover_from">'+control_vars.from+" "+parseFloat (extra_price_per_guest,10)+'</div>';
        display_price = control_vars.from+" "+parseFloat (extra_price_per_guest,10);
        from_css_class= " hover_from ";
    }
   
    // establish the start day block
    ////////////////////////////////////////////////////////////////////////////
    if(mega_details[unixtime1_key] !== undefined){
        if( parseInt(mega_details[unixtime1_key]['period_checkin_change_over'],10)!==0 ) {
            block_check_in =  parseInt(mega_details[unixtime1_key]['period_checkin_change_over'],10);
        }
    }else if( parseFloat(checkin_change_over)!==0 ){
        block_check_in =  parseInt(checkin_change_over,10);
    }
    
    // establish the start day - end day block
    ////////////////////////////////////////////////////////////////////////////   
    if(mega_details[unixtime1_key] !== undefined){
        if( parseInt(mega_details[unixtime1_key]['period_checkin_checkout_change_over'],10)!==0 ) {
            block_check_in_check_out =  parseInt(mega_details[unixtime1_key]['period_checkin_checkout_change_over'],10);
        }
    }else if( parseFloat(checkin_checkout_change_over)!==-1 ){
        block_check_in_check_out =  parseInt(checkin_checkout_change_over,10);
    }

    
   
    
    //  if( booking_array.indexOf(unixtime1) > -1 ){
    if( booking_array[unixtime1] != undefined){
        end_reservation=1;
        
        if(start_reservation==1){
            reservation_class=' start_reservation';
            start_reservation=0;
            return [true,"wpestate_calendar calendar-reserved"+reservation_class, control_vars.unavailable_check]; 
        }
        
      
        return [false,"wpestate_calendar calendar-reserved"+reservation_class,  control_vars.unavailable]; 
    
    }else{
       
        start_reservation=1;
        if(end_reservation===1){
            reservation_class=' end_reservation';
            end_reservation=0;
           
        }
        
        if(week_day !== block_check_in_check_out && block_check_in_check_out!==0 && unixtime1_key > today ){ 
      
        if(reservation_class !== ' end_reservation'){
            reservation_class=' check_in_block';
        }
        return [
            false,
            "wpestate_calendar "+reservation_class+" date"+unixtime1_key, 
            wpestate_booking_calendat_get_price(unixtime1_key,display_price) 
        ]; 
        
        //Check in/Check out  only on '+weekdays[block_check_in]
        }else if(week_day !== block_check_in && block_check_in!==0 && from_who ==='start_date' && unixtime1_key > today){ 
            
            if(reservation_class !== ' end_reservation'){
                reservation_class=' check_in_block';
            }
            return [
                false,
                "wpestate_calendar "+reservation_class+" date"+unixtime1_key, 
                wpestate_booking_calendat_get_price(unixtime1_key,display_price) 
            ];

        }  
        
        return [
            true, 
            "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key+" "+from_css_class, 
            wpestate_booking_calendat_get_price(unixtime1_key,display_price) 
            ];              
    } 

 


}





function wpestate_booking_calendat_get_price(unixtime1_key,display_price){
    var return_price;
    return_price ='';
   
    
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) { // if we have custom curency
            if (my_custom_curr_cur_post === 'before') {       
                
             
                if(price_array[unixtime1_key] === undefined){
                    if(display_price===''){ // we DONT have weekend price
                        display_price = control_vars.default_price;
                    }
                    display_price = replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) + String (  Math.round( display_price* my_custom_curr_coef) );
                    
                //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) + String ( display_price* my_custom_curr_coef ) ];              
                }else{
                    if(display_price===''){ // we DONT have weekend price
                        display_price = price_array[unixtime1_key] ;
                    }
                    display_price =replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) +  String(  Math.round ( display_price * my_custom_curr_coef) );
                    //return [true, "freetobook wpestate_calendar" +reservation_class+" date"+unixtime1_key, replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) +  String( display_price * my_custom_curr_coef ) ];   
                }
            } else {
             
                if(price_array[unixtime1_key] === undefined){
                    if(display_price===''){ // we DONT have weekend price
                        display_price = control_vars.default_price;
                    }
                    display_price =  String (  Math.round(display_price * my_custom_curr_coef) ) + replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) ;
                    //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key,String ( display_price * my_custom_curr_coef ) + replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) ];  
                }else{
                    if(display_price===''){ // we DONT have weekend price
                       display_price = price_array[unixtime1_key] ;
                    }
                    display_price = String(  Math.round(display_price  * my_custom_curr_coef) )+ replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) )
                    //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, String( display_price  * my_custom_curr_coef )+ replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) ];  
                }
            }
        } else { // we don't have custom curency
          
            if (control_vars.where_curency === 'before') {                  
                if(price_array[unixtime1_key] === undefined){
                    if(display_price===''){ // we DONT have weekend price
                        display_price = control_vars.default_price;
                    }
                    display_price = replace_plus ( decodeURIComponent ( control_vars.curency ) ) + display_price;
                    //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, replace_plus ( decodeURIComponent ( control_vars.curency ) ) + display_price ];              
                }else{
                    if(display_price===''){ // we DONT have weekend price
                       display_price = price_array[unixtime1_key] ;
                    }
                    display_price = replace_plus ( decodeURIComponent ( control_vars.curency ) ) +  String(display_price);
                    //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, replace_plus ( decodeURIComponent ( control_vars.curency ) ) +  String(display_price) ];   
                }
            } else {
                if(price_array[unixtime1_key] === undefined){
                    if(display_price===''){ // we DONT have weekend price
                        display_price = control_vars.default_price;
                    }
                    display_price = display_price + replace_plus ( decodeURIComponent ( control_vars.curency ) );
                    //return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key,display_price +  replace_plus ( decodeURIComponent ( control_vars.curency ) ) ];  
                }else{
                    if(display_price===''){ // we DONT have weekend price
                       display_price = price_array[unixtime1_key] ;
                    }
                    display_price =  String(display_price)+ replace_plus( decodeURIComponent ( control_vars.curency ) );
                   // return [true, "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key, String(display_price)+ replace_plus( decodeURIComponent ( control_vars.curency ) ) ];  
                }
            }
        }


      /*
        if(price_array[unixtime1_key] === undefined){
            if(display_price===''){ // we DONT have weekend price
                display_price = control_vars.default_price;;
            }
            display_price = display_price+ control_vars.curency;
           // return [true, "freetobook wpestate_calendar"+reservation_class, display_price+ control_vars.curency ];             
        }else{
            if(display_price===''){ // we DONT have weekend price
                display_price = price_array[unixtime1_key] ;
            }
            display_price = String(display_price) + " vv "+control_vars.curency ;
           // return [true, "freetobook wpestate_calendar"+reservation_class, String(display_price) + control_vars.curency ]; 
        }*/
        
    return display_price;
}






























function wpestate_enable_slider(slider_name, price_low, price_max, amount, my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post, my_custom_curr_coef) {
    "use strict";
    var price_low_val, price_max_val, temp_min, temp_max;
    price_low_val = parseInt(jQuery('#'+price_low).val(), 10);
    price_max_val = parseInt(jQuery('#'+price_max).val(), 10);
    
    jQuery("#" + slider_name).slider({
        range: true,
        min: parseFloat(control_vars.slider_min),
        max: parseFloat(control_vars.slider_max),
        values: [price_low_val, price_max_val ],
        slide: function (event, ui) {
         
            if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
                jQuery("#" + price_low).val(ui.values[0]* my_custom_curr_coef);
                jQuery("#" + price_max).val(ui.values[1]* my_custom_curr_coef);
                
                temp_min= ui.values[0]* my_custom_curr_coef;
                temp_max= ui.values[1]* my_custom_curr_coef;
                
                if (my_custom_curr_cur_post === 'before') {
                    jQuery("#" + amount).text( replace_plus( decodeURIComponent ( my_custom_curr_symbol ) ) + " " + temp_min.format() + " " + control_vars.to + " " + replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) )+ " " + temp_max.format());
                } else {
                    jQuery("#" + amount).text(temp_min.format() + " " + replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) )+ " " + control_vars.to + " " + temp_max.format() + " " + replace_plus ( decodeURIComponent ( my_custom_curr_symbol ) ) );
                }
            } else {
                jQuery("#" + price_low).val(ui.values[0]);
                jQuery("#" + price_max).val(ui.values[1]);
            
                if (control_vars.where_curency === 'before') {
                    jQuery("#" + amount).text( replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + ui.values[0].format() + " " + control_vars.to + " " +  replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + ui.values[1].format());
                } else {
                    jQuery("#" + amount).text(ui.values[0].format() + " " + replace_plus ( decodeURIComponent ( control_vars.curency ) ) + " " + control_vars.to + " " + ui.values[1].format() + " " + replace_plus ( decodeURIComponent ( control_vars.curency ) ) );
                }
            }
        }
    });
}


function replace_plus(string){
    return string.replace("+"," ");
}

function prevent_enter_submit(main_search,search_location, check_in, check_out, guest_no){
    jQuery('#'+main_search).on("keyup keypress", function(e) {
        var code = e.keyCode || e.which; 
        if (code  == 13) {               
            e.preventDefault();
            if( jQuery('#'+search_location).val()!=='' ){
                if ( jQuery('#'+check_in).val()!=='' )  {
                    if ( jQuery('#'+check_out).val()!=='' )  {
                        jQuery('#'+check_out).focusout();
                        jQuery('#'+check_out).datepicker("hide");
                        jQuery('#'+guest_no).focus().dropdown('toggle');       
                        jQuery('#'+check_out).datepicker("hide");
                    }else{
                        jQuery('#'+check_in).datepicker("hide");
                        jQuery('#'+check_out).datepicker("show");
                    }
                }else{
                    jQuery('#'+check_in).datepicker("show");
                }
            } 
            
            return false;
        }
    });
}
 function wpestate_request_byid(){
       
        var url="https://maps.googleapis.com/maps/api/place/textsearch/json?query=Verenigde%20Staten&language=zh-CN&key=AIzaSyBR_AKsig4yk-ZBxw607Sbikyz3vGNNq2I";
   
      
        
    }
/*
jQuery(window).resize(function (){
    if( jQuery( window ).width()< 974 ){
        jQuery('#user_tab_menu_container').css('display','none');
    }else{
        jQuery('#user_tab_menu_container').css('display','block');
    }
});
*/

function wpestate_lazy_load_carousel_property_unit(){
    jQuery('.property_unit_carousel img').each(function(event){
          var new_source='';
          new_source=jQuery(this).attr('data-lazy-load-src');
          if(typeof (new_source)!=='undefined' && new_source!==''){
              jQuery(this).attr('src',new_source);
          }
      });
}


jQuery(document).ready(function ($) {
    "use strict";
    var bLazy, search_label, curent, price_regular, price_featured, total, percent, parent, price_low_val, price_max_val, autoscroll_slider, all_browsers_stuff, wrap_h, map_h, mediaQuery;


    //////direct pay//////////////////////////////////////////////////////////////////////
    
    jQuery('#direct_pay').click(function(){
        var direct_pay_modal, selected_pack,selected_prop,include_feat,attr, price_pack;

        selected_pack=$('#pack_select').val();
        var price_pack  =   $('#pack_select option:selected').attr('data-price');
     
        if (control_vars.where_curency === 'after'){
            price_pack = price_pack +' '+control_vars.submission_curency;
        }else{
            price_pack = control_vars.submission_curency+' '+price_pack;
        }
        
        price_pack=control_vars.direct_price+': '+price_pack;
        
        if(selected_pack!==''){
            window.scrollTo(0, 0);
            direct_pay_modal='<div class="modal fade" id="direct_pay_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h2 class="modal-title_big">'+control_vars.direct_title+'</h2></div><div class="modal-body listing-submit"><span class="to_be_paid">'+price_pack+'</span><span>'+control_vars.direct_pay+'</span><div id="send_direct_bill" data-pack="'+selected_pack+'">'+control_vars.send_invoice+'</div></div></div></div></div></div>';
            jQuery('body').append(direct_pay_modal);
            jQuery('#direct_pay_modal').modal();
            enable_direct_pay();
        }
        
        $('#direct_pay_modal').on('hidden.bs.modal', function (e) {
               $('#direct_pay_modal').remove();
        })
        
    });


    $('.perpack').click(function(){
        var direct_pay_modal, selected_pack,selected_prop,include_feat,attr;
        selected_prop   =   $(this).attr('data-listing');
        
        var price_pack  =   $(this).parent().parent().find('.submit-price-total').text();
        
        attr = $(this).attr('data-isupgrade');
        if (typeof attr !== typeof undefined && attr !== false) {
            price_pack  =   $(this).parent().parent().find('.submit-price-featured').text();
        }
     
        if (control_vars.where_curency === 'after'){
            price_pack = price_pack +' '+control_vars.submission_curency;
        }else{
            price_pack = control_vars.submission_curency+' '+price_pack;
        }
        
        price_pack=control_vars.direct_price+': '+price_pack;
        
        
        include_feat=' data-include-feat="0" ';
        $('#send_direct_bill').attr('data-include-feat',0);
        $('#send_direct_bill').attr('data-listing',selected_prop);
         
        if ( $(this).parent().find('.extra_featured').attr('checked') ){
            include_feat=' data-include-feat="1" ';
            $('#send_direct_bill').attr('data-include-feat',1);
        }

   
        if (typeof attr !== typeof undefined && attr !== false) {
            include_feat=' data-include-feat="1" ';
            $('#send_direct_bill').attr('data-include-feat',1);
        }


        window.scrollTo(0, 0);
        direct_pay_modal='<div class="modal fade" id="direct_pay_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h2 class="modal-title_big">'+control_vars.direct_title+'</h2></div><div class="modal-body listing-submit"><span class="to_be_paid">'+price_pack+'</span><span>'+control_vars.direct_pay+'</span><div id="send_direct_bill" '+include_feat+' data-listing="'+selected_prop+'">'+control_vars.send_invoice+'</div></div></div></div></div></div>';
        jQuery('body').append(direct_pay_modal);
        jQuery('#direct_pay_modal').modal();
        enable_direct_pay_perlisting();
        
          $('#direct_pay_modal').on('hidden.bs.modal', function (e) {
               $('#direct_pay_modal').remove();
        })
        
    });
    
    
    
    function enable_direct_pay(){
        jQuery('#send_direct_bill').click(function(){
            jQuery('#send_direct_bill').unbind('click');
            var selected_pack,ajaxurl;
            selected_pack=jQuery(this).attr('data-pack');
            ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
            
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_direct_pay_pack',
                    'selected_pack'     :   selected_pack,
                },
                success: function (data) {     
                    jQuery('#send_direct_bill').hide();
                    jQuery('#direct_pay_modal .listing-submit span:nth-child(2)').empty().html(control_vars.direct_thx);
                  
                },
                error: function (errorThrown) {}
            });//end ajax  

 
    
    
        });
        
    }    


      
    function  enable_direct_pay_perlisting(){
        jQuery('#send_direct_bill').unbind('click');
        jQuery('#send_direct_bill').click(function(){
            jQuery('#send_direct_bill').unbind('click');
            var selected_pack,ajaxurl,include_feat;
           
            selected_pack   =   jQuery(this).attr('data-listing');
            include_feat    =   jQuery(this).attr('data-include-feat')
            ajaxurl         =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
            
         
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_direct_pay_pack_per_listing',
                    'selected_pack'     :   selected_pack,
                    'include_feat'      :   include_feat,
                },
                success: function (data) {
                    jQuery('#send_direct_bill').hide();
                    jQuery('#direct_pay_modal .listing-submit span:nth-child(2)').empty().html(control_vars.direct_thx);
                },
                error: function (errorThrown) {}
            });//end ajax  

        });
         
    }    
        









    /////////////////////////////////////////////////////////////////////////////
    if (document.getElementById('imagelist')) {
        $( "#imagelist" ).sortable({
            revert: true,
            update: function( event, ui ) {
                var all_id,new_id;
                all_id="";
                $( "#imagelist .uploaded_images" ).each(function(){

                    new_id = $(this).attr('data-imageid'); 
                    if (typeof new_id != 'undefined') {
                        all_id=all_id+","+new_id; 

                    }

                });

                $('#attachid').val(all_id);
            },
        });
    }

    $(window).bind("load", function() {
        wpestate_lazy_load_carousel_property_unit();
    });
    
    
  
    $('.retina_ready').dense();
    

    $('#user_tab_menu_trigger').click(function(){
        $('#user_tab_menu_container').toggle('200');
    });

    $('#carousel-listing').on('slid.bs.carousel', function () {
        show_capture_vertical();
        $('#carousel-listing div').removeClass('slideron');
        $('#slider_enable_slider').addClass('slideron');
    })
    
    ////////////////////////////////////////////////////////////////////////////
    ///carousel show controls
    ////////////////////////////////////////////////////////////////////////////
    
    ////////////////////////////////////////////////////////////////////////////
    ///prevent form submit on enter
    ////////////////////////////////////////////////////////////////////////////
    prevent_enter_submit('main_search','search_location','check_in','check_out','guest_no');
    prevent_enter_submit('widget_search','search_location_filter_widget','checkinwidget','checkoutwidget','guest_no_wid');
    prevent_enter_submit('form-search-mobile','search_location_mobile','check_in_mobile','check_out_mobile','guest_no_mobile');
   
    ////////////////////////////////////////////////////////////////////////////
    ///unit clicks
    ////////////////////////////////////////////////////////////////////////////  
    jQuery('.blog_unit_back ').click(function(){
        window.open($(this).find('.blog-title-link').attr('href'), '_self', false);      
    });

    ////////////////////////////////////////////////////////////////////////////
    /// lazy load - scripts disabled because they are  not realible yet
    ////////////////////////////////////////////////////////////////////////////     
   /* bLazy = new Blazy();
    $("#google_map_prop_list_sidebar").scroll(function () {
        bLazy.revalidate();
    });
*/
    //$(window).lazyLoadXT();
    /*$("#google_map_prop_list_sidebar").scroll(function () {
       $('.b-lazy').lazyLoadXT(); 
       $('#google_map_prop_list_sidebar').find('.b-lazy').lazyLoadXT();
      
    });
    */
    
    $('.calendar_pad').hover(
    function() {
        
        //$(this).addClass('calendar-pad-hover');
        var timeunix=$(this).attr('data-curent-date');
        $(".calendar_pad[data-curent-date=" + timeunix + "]").addClass('calendar-pad-hover');
        $(".calendar_pad_title[data-curent-date=" + timeunix + "]").addClass('calendar-pad-hover');
       // $(".calendar-pad").find("[data-curent-date='" + timeunix + "']").addClass('calendar-pad-hover');
        
       
        if( $(this).hasClass('calendar-reserved') ){
            var reservation_data=$(this).find('.allinone_reservation');
            //$(this).find('.allinone_reservation').show();
            reservation_data.show();
            var internal_booking_id =   parseInt( $(this).find('.allinone_reservation').attr('data-internal-reservation'),10);
            if (!isNaN(internal_booking_id) && internal_booking_id!=0 ){
                var ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
               
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,

                    data: {
                        'action'                  :   'wpestate_get_booking_data',
                        'internal_booking_id'     :   internal_booking_id,
                    },
                    success: function (data) {
                        reservation_data.empty().append(data);
                      
                    },
                    error: function (errorThrown) {}
                });//end ajax     
            }
        }
       
    }, function() {
        $(this).find('.allinone_reservation').hide();
        //$(this).removeClass('calendar-pad-hover');
        var timeunix=$(this).attr('data-curent-date');
        $(".calendar_pad[data-curent-date=" + timeunix + "]").removeClass('calendar-pad-hover');
        $(".calendar_pad_title[data-curent-date=" + timeunix + "]").removeClass('calendar-pad-hover');
        
    });
    
   
    
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////
    // mobile menu
    ////////////////////////////////////////////////////////////////////////////

    $('.all-elements').animate({
        minHeight: 100 + '%'
    });
    $('.header-tip').addClass('hide-header-tip');


    $('.mobile-trigger').click(function () {
        if ($('#all_wrapper').hasClass('moved_mobile')) {
            $('.mobilewrapper-user').show();
            $('#all_wrapper').css('-webkit-transform', 'translate(0px, 0px)');
            $('#all_wrapper').css('-moz-transform', 'translate(0px, 0px)');
            $('#all_wrapper').css('-ms-transform', 'translate(0px, 0px)');
            $('#all_wrapper').css('-o-transform', 'translate(0px, 0px)');
  
            $('#all_wrapper').removeClass('moved_mobile');
       
            $('.mobilewrapper').css('-webkit-transform', 'translate(-265px, 0px)');
            $('.mobilewrapper').css('-moz-transform', 'translate(-265px, 0px)');
            $('.mobilewrapper').css('-ms-transform', 'translate(-265px, 0px)');
            $('.mobilewrapper').css('-o-transform', 'translate(-265px, 0px)');
        } else {
            $('.mobilewrapper-user').hide();
            $('.mobilewrapper').show();
            $('#all_wrapper').css('-webkit-transform', 'translate(265px, 0px)');
            $('#all_wrapper').css('-moz-transform', 'translate(265px, 0px)');
            $('#all_wrapper').css('-ms-transform', 'translate(265px, 0px)');
            $('#all_wrapper').css('-o-transform', 'translate(265px, 0px)');

            $('#all_wrapper').addClass('moved_mobile');
          
            $('.mobilewrapper').css('-webkit-transform', 'translate(0px, 0px)');
            $('.mobilewrapper').css('-moz-transform', 'translate(0px, 0px)');
            $('.mobilewrapper').css('-ms-transform', 'translate(0px, 0px)');
            $('.mobilewrapper').css(' -o-transform', 'translate(0px, 0px)');
        }
    });

    $('.mobile-trigger-user').click(function () {
        if ($('#all_wrapper').hasClass('moved_mobile_user')) {
            $('#all_wrapper').css('-webkit-transform', 'translate(0px, 0px)');
            $('#all_wrapper').css('-moz-transform', 'translate(0px, 0px)');
            $('#all_wrapper').css('-ms-transform', 'translate(0px, 0px)');
            $('#all_wrapper').css('-o-transform', 'translate(0px, 0px)');
            $('#all_wrapper').removeClass('moved_mobile_user');
        
            
            $('.mobilewrapper-user').hide();
            $('.mobilewrapper').show();
            $('.mobilewrapper-user').css('-webkit-transform', 'translate(265px, 0px)');
            $('.mobilewrapper-user').css('-moz-transform', 'translate(265px, 0px)');
            $('.mobilewrapper-user').css('-ms-transform', 'translate(265px, 0px)');
            $('.mobilewrapper-user').css('-o-transform', 'translate(265px, 0px)');
        } else {
            $('#all_wrapper').css('-webkit-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-moz-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-ms-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').css('-o-transform', 'translate(-265px, 0px)');
            $('#all_wrapper').addClass('moved_mobile_user');
          
            $('.mobilewrapper-user').show();
            $('.mobilewrapper').hide();
            $('.mobilewrapper-user').css('-webkit-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css('-moz-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css('-ms-transform', 'translate(0px, 0px)');
            $('.mobilewrapper-user').css(' -o-transform', 'translate(0px, 0px)');
        }
    });
    
    
    
    $('.mobilemenu-close-user').click(function () {
        $('#all_wrapper').css('-webkit-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-moz-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-ms-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-o-transform', 'translate(0px, 0px)');
        $('#all_wrapper').removeClass('moved_mobile_user');
        $('.mobilewrapper-user').css('-webkit-transform', 'translate(-265px, 0px)');
        $('.mobilewrapper-user').css('-moz-transform', 'translate(-265px, 0px)');
        $('.mobilewrapper-user').css('-ms-transform', 'translate(-265px, 0px)');
        $('.mobilewrapper-user').css('-o-transform', 'translate(-265px, 0px)');
    });

    $('.mobilemenu-close').click(function () {
        $('.mobilewrapper-user').show();
        $('#all_wrapper').css('-webkit-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-moz-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-ms-transform', 'translate(0px, 0px)');
        $('#all_wrapper').css('-o-transform', 'translate(0px, 0px)');
        $('#all_wrapper').removeClass('moved_mobile');
        $('.mobilewrapper').css('-webkit-transform', 'translate(-265px, 0px)');
        $('.mobilewrapper').css('-moz-transform', 'translate(-265px, 0px)');
        $('.mobilewrapper').css('-ms-transform', 'translate(-265px, 0px)');
        $('.mobilewrapper').css('-o-transform', 'translate(-265px, 0px)');
    });

    $('.mobilex-menu li').click(function (event) {
        event.stopPropagation();
        var selected;
        selected = $(this).find('.sub-menu:first');
        selected.slideToggle();
    });


    $('#user_menu_u').click(function (event) {
        if ($('#user_menu_open').is(":visible")) {
            $('#user_menu_open').removeClass('iosfixed').fadeOut(400);
        } else {
            $('#user_menu_open').fadeIn(400);
        }
        event.stopPropagation();
    });

    $(document).click(function (event) {
        var clicka;
        clicka = event.target.id;
        if (!$('#' + clicka).parents('.topmenux').length) {
            $('#user_menu_open').removeClass('iosfixed').hide(400);
        }
    });

    ////////////////////////////////////////////////////////////////////////////
    // multiple cur set cookige
    ////////////////////////////////////////////////////////////////////////////

    $('.list_sidebar_currency li').click(function () {
        var ajaxurl, data, pos, symbol, coef, curpos;
        data = $(this).attr('data-value');
        pos = $(this).attr('data-pos');
        symbol = $(this).attr('data-symbol');
        coef = $(this).attr('data-coef');
        curpos = $(this).attr('data-curpos');

        ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'    :   'wpestate_set_cookie_multiple_curr',
                'curr'      :   data,
                'pos'       :   pos,
                'symbol'    :   symbol,
                'coef'      :   coef,
                'curpos'    :   curpos
            },
            success: function (data) {
                location.reload();
            },
            error: function (errorThrown) {}
        });//end ajax     
    });


   

    ////////////////////////////////////////////////////////////////////////////
    ///prop list header
    ////////////////////////////////////////////////////////////////////////////     
    $('#adv_extended_options_text_adv').click(function () {
        $('#extended_search_check_filter').slideDown();
        $('#adv_extended_close_adv').show();
        $(this).hide();
    });

    $('#adv_extended_close_adv').click(function () {
        $(this).hide();
        $('#extended_search_check_filter').slideUp();
        $('#adv_extended_options_text_adv').show();
    });

    $('#adv_extended_options_show_filters').click(function () {
        $('#advanced_search_map_list').addClass('move_to_fixed');
        $('#extended_search_check_filter').slideDown();
        $('#adv_extended_close_adv').show();
        $('#adv_extended_options_text_adv').hide();
    });

    check_in_out_enable('check_in_list', 'check_out_list');// half map search
    check_in_out_enable('booking_from_date', 'booking_to_date'); // owner contact
    check_in_out_enable('check_in', 'check_out'); //advanced search
    check_in_out_enable('check_in_mobile', 'check_out_mobile'); //advanced search mobile
    check_in_out_enable('checkinwidget', 'checkoutwidget'); //search form widget
    check_in_out_enable('checkinshortcode', 'checkoutshortcode'); //search form shortcode search
    check_in_out_enable2('start_date', 'end_date'); //booking form search

   // today = new Date();
    jQuery("#testx").datepicker({ dateFormat: "yy-m-d" });
    $('#ui-datepicker-div').css('clip', 'auto');
    
  

    ////////////////////////////////////////////////////////////////////////////
    /// stripe
    ////////////////////////////////////////////////////////////////////////////
    $('#pack_select').change(function () {
        var stripe_pack_id, stripe_ammount, the_pick;
        $("#pack_select option:selected").each(function () {
            stripe_pack_id = $(this).val();
            stripe_ammount = parseFloat($(this).attr('data-price')) * 100;
            the_pick = $(this).attr('data-pick');
        });

        $('#pack_id').val(stripe_pack_id);
        $('#pay_ammout').val(stripe_ammount);
        $('#stripe_form').attr('data-amount', stripe_ammount);
        $('.stripe_buttons').each(function () {
            $(this).hide();
            if ($(this).attr('id') === the_pick) {
                $(this).show();
            }
        });

    });

    $('#pack_recuring').click(function () {
        if ($(this).attr('checked')) {
            $('#stripe_form').append('<input type="hidden" name="stripe_recuring" id="stripe_recuring" value="1">');
        } else {
            $('#stripe_recuring').remove();
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////
    // listing menu
    /////////////////////////////////////////////////////////////////////////////////////////
    $('.check_avalability, .property_menu_wrapper_hidden a').click(function () {
        var target;
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            target = $(this.hash);
            if (target.selector === '#carousel-control-theme-next' || target.selector === '#carousel-control-theme-prev' || target.selector === '#carousel-listing' || target.selector === '#carousel-example-generic' || target.selector === '#post_carusel_right') {
                return;
            }
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 140
                }, 1000);
                return false;
            }
        }
    });
    ////////////////////////////////////////////////////////////////////////////
    // listing map actions
    ////////////////////////////////////////////////////////////////////////////
    $('.listing_wrapper').hover(
        function () {
            var listing_id = $(this).attr('data-listid');
            if (typeof wpestate_hover_action_pin == 'function') { 
                wpestate_hover_action_pin(listing_id);
            }
        },
        function () {
            var listing_id = $(this).attr('data-listid');
            if (typeof wpestate_return_hover_action_pin == 'function') { 
                wpestate_return_hover_action_pin(listing_id);
            }
        }
    );

    componentForm = {
        establishment: 'long_name',
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        administrative_area_level_2: 'long_name',
        administrative_area_level_3: 'long_name',
        administrative_area_level_4: 'long_name',
        country: 'long_name',
        postal_code: 'short_name',
        postal_code_prefix : 'short_name',
        neighborhood: 'long_name',
        sublocality_level_1: 'long_name'
    };


    ////////////////// widget autocomplete 
    function fillInAddress_filter_widget(place) {
        var i, addressType, temp, val, have_city, admin_area;
        $('#advanced_area_widget').val('');
        $('#advanced_city_widget').val('');
        $('#advanced_country_widget').val('');
        admin_area='';
        have_city=0;

        for (i = 0; i < place.address_components.length; i++) {
            addressType = place.address_components[i].types[0];
            temp = '';
            val = place.address_components[i][componentForm[addressType]];

            if (typeof (val) !== 'undefined') {
                val = val.toLowerCase();
                val = val.split(' ').join('-');
            }
          
   
            if (addressType === 'neighborhood'  || addressType === 'sublocality_level_1' ) {
                $('#advanced_area_widget').attr('data-value', val);
                $('#advanced_area_widget').val(val);
            } else if (addressType === 'locality') {
                $('#advanced_city_widget').attr('data-value', val);
                $('#advanced_city_widget').val(val);
                if(val!==''){
                    have_city=1;
                }
            } else if(addressType === 'country'){
                $('#advanced_country_widget').attr('data-value', val);
                $('#advanced_country_widget').val(val);
            }else if (addressType === 'administrative_area_level_4') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_3') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_2') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_1') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            }
        }
        if(have_city===0){
            second_measure_city('advanced_city_widget',place.adr_address);
        }
        
    }
    
    function fillInAddress_filter_shortocode(place) {
        var i, addressType, temp, val,have_city, admin_area;
        have_city   =   0;
        admin_area  =   '';
        $('#advanced_area_shortcode').val('');
        $('#advanced_city_shortcode').val('');
        $('#advanced_country_shortcode').val('');
            
        for (i = 0; i < place.address_components.length; i++) {
            addressType = place.address_components[i].types[0];
            temp = '';
            val = place.address_components[i][componentForm[addressType]];

            if (typeof (val) !== 'undefined') {
                val = val.toLowerCase();
                val = val.split(' ').join('-');
            }
            
            
            
            if (addressType === 'neighborhood'  || addressType === 'sublocality_level_1' ) {
                $('#advanced_area_shortcode').attr('data-value', val);
                $('#advanced_area_shortcode').val(val);
            } else if (addressType === 'locality') {
                $('#advanced_city_shortcode').attr('data-value', val);
                $('#advanced_city_shortcode').val(val);
                if(val!==''){
                    have_city=1;
                }
            } else if(addressType === 'country'){
                $('#advanced_country_shortcode').attr('data-value', val);
                $('#advanced_country_shortcode').val(val);
            }else if (addressType === 'administrative_area_level_4') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_3') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_2') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_1') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            }
        }
        if(have_city===0){
            second_measure_city('advanced_city_shortcode',place.adr_address);
        }
    }


    if (document.getElementById('search_location_filter_widget')) {
        var input, autocomplete_widget;
        input = (document.getElementById('search_location_filter_widget'));
        var options = {
            bounds: defaultBounds,
            types: ['geocode']
        };
        autocomplete_widget = new google.maps.places.Autocomplete(input, options);

        google.maps.event.addListener(autocomplete_widget, 'place_changed', function () {
            var place = autocomplete_widget.getPlace();
            fillInAddress_filter_widget(place);
        });
    }
    
    
    if (document.getElementById('search_location_filter_shortcode')) {
        var input, autocomplete_wid;
        input = (document.getElementById('search_location_filter_shortcode'));
        var options = {
            bounds: defaultBounds,
            types: ['geocode']
        };
        autocomplete_wid = new google.maps.places.Autocomplete(input, options);

        google.maps.event.addListener(autocomplete_wid, 'place_changed', function () {
            var place = autocomplete_wid.getPlace();
            fillInAddress_filter_shortocode(place);
        });
    }

    ////////////////////end widget autocomplete

    if (document.getElementById('search_location')) {
        var input, defaultBounds, autocomplete_normal;
        input = (document.getElementById('search_location'));
        defaultBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(-90, -180),
            new google.maps.LatLng(90, 180)
        );
        var options = {
            bounds: defaultBounds,
            types: ['geocode'],
           // types: ['(regions)'],
        };

        autocomplete_normal = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete_normal, 'place_changed', function () {
            var place = autocomplete_normal.getPlace();  
            fillInAddress_filter(place);
        
        });
    }
    
    
   

   
    //
    if (document.getElementById('search_location_mobile')) {
        var input, defaultBounds, autocomplete_mobile;
        input = (document.getElementById('search_location_mobile'));
        defaultBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(-90, -180),
            new google.maps.LatLng(90, 180)
        );
        var options = {
            bounds: defaultBounds,
            types: ['geocode']
        };

        autocomplete_mobile = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete_mobile, 'place_changed', function () {
            var place = autocomplete_mobile.getPlace();
            fillInAddress_filter_mobile(place);
        });
    }



    if (document.getElementById('search_location_filter')) {
        var input, defaultBounds, autocomplete_hm;
        input = (document.getElementById('search_location_filter'));
        defaultBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(-90, -180),
            new google.maps.LatLng(90, 180)
        );

        options = {
            bounds: defaultBounds,
            types: ['geocode']
        };

        autocomplete_hm = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete_hm, 'place_changed', function () {
            var place = autocomplete_hm.getPlace();
           
            fillInAddress_filter(place);
            start_filtering_ajax_map(1);
        });
    }

    function fillInAddress_filter(place) {
        var i, addressType, val, is_google_map,have_city,admin_area;
        
        have_city   =   0;
        admin_area  =   '';
       
        
        $('#advanced_area').val('');
        $('#advanced_city').val('');
        $('#advanced_country').val('');
        $('#search_location_area').val('');
        $('#search_location_city').val('');
        $('#search_location_country').val('');
        $('#property_admin_area').val('');
         
         
        
        for (i = 0; i < place.address_components.length; i++) {
            addressType = place.address_components[i].types[0];
            val = place.address_components[i][componentForm[addressType]];

            if (typeof (val) !== 'undefined') {
                val = val.toLowerCase();
                val = val.split(' ').join('-');
            }
             
            if (addressType === 'neighborhood'  || addressType === 'sublocality_level_1') {
                $('#advanced_area').attr('data-value', val);
                $('#advanced_area').val(val);
                $('#search_location_area').val(val);
            }else if (addressType === 'administrative_area_level_4') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_3') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_2') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_1') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            }else if (addressType === 'locality') {
            
                $('#advanced_city').attr('data-value', val);
                $('#advanced_city').val(val);
                $('#search_location_city').val(val);
                if(val!==''){
                    have_city=1;
                }
            } else if(addressType === 'country'){
                $('#advanced_country').attr('data-value', val);
                $('#advanced_country').val(val);
                $('#search_location_country').val(val);
                
            }


        }
        
        if(have_city===0){
            second_measure_city('advanced_city',place.adr_address);
            second_measure_city('search_location_city',place.adr_address);
        }
        
        is_google_map = parseInt(jQuery('#isgooglemap').attr('data-isgooglemap'), 10);
        if (is_google_map === 1) {
            var guest_val=$(this).attr('data-value');
            start_filtering_ajax_on_main_map(guest_val);
            
        }
    }

    function wpestate_build_admin_area(admin_area,val){
        if(admin_area ===''){
            admin_area = admin_area+val;
        }else{
            admin_area = admin_area+", "+val;
        }
        
        $('#property_admin_area,#property_admin_area_widget,#property_admin_area_shortcode,#property_admin_area_mobile').val(admin_area);
      
        return admin_area;
    }
    
 
    ////////
    function  second_measure_city(stringplace,adr_address){
        var new_city;
        new_city = $(adr_address).filter('span.locality').html() ;
    
        $('#'+stringplace).val(new_city);
    }





    function fillInAddress_filter_mobile(place) {
        var i, temp, addressType, val, is_google_map,have_city,admin_area;
        $('#advanced_area_mobile').val('');
        $('#advanced_city_mobile').val('');
        $('#advanced_country_mobile').val('');
        $('#search_location_area_mobile').val('');
        $('#search_location_city_mobile').val('');
        $('#search_location_country_mobile').val('');
        $('#property_admin_area_mobile').val('');
        have_city   =   0;
        admin_area  =   '';
        for (i = 0; i < place.address_components.length; i++) {
            addressType = place.address_components[i].types[0];
            val = place.address_components[i][componentForm[addressType]];

            if (typeof (val) !== 'undefined') {
                val = val.toLowerCase();
                val = val.split(' ').join('-');
            }
            
           
             
            if (addressType === 'neighborhood'  || addressType === 'sublocality_level_1') {
                $('#advanced_area_mobile').attr('data-value', val);
                $('#advanced_area_mobile').val(val);
                $('#search_location_area_mobile').val(val);
            } else if (addressType === 'locality') {
                $('#advanced_city_mobile').attr('data-value', val);
                $('#advanced_city_mobile').val(val);
                $('#search_location_city_mobile').val(val);
                if(val!==''){
                    have_city=1;
                }
            } else if(addressType === 'country'){
                $('#advanced_country_mobile').attr('data-value', val);
                $('#advanced_country_mobile').val(val);
                $('#search_location_country_mobile').val(val);
            }else if (addressType === 'administrative_area_level_4') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_3') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_2') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_1') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            }


        }
         if(have_city===0){
            second_measure_city('advanced_city_mobile',place.adr_address);
        }
        
        is_google_map = parseInt(jQuery('#isgooglemap').attr('data-isgooglemap'), 10);
        if (is_google_map === 1) {
            var guest_val=$(this).attr('data-value');
            start_filtering_ajax_on_main_map(guest_val);
            
        }
    }



    function fillInAddress() {
        var place, adr, country;
        place   =   autocomplete.getPlace();
        adr     =   place['address_components'];
        country =   adr[adr.length - 1]['long_name'];
        document.getElementById('property_city').value  = place['name'];
        document.getElementById('property_country').value   = country;
    }

    $('#check_out').change(function(){
        if( $('#check_in').val()!==''  ){
            var guest_val=$(this).attr('data-value');
            start_filtering_ajax_on_main_map(guest_val);
        }
    });
    
    $('#guest_no_main_list li').click(function(){
        var guest_val=$(this).attr('data-value');
        start_filtering_ajax_on_main_map(guest_val);  
    });
    
    

    ////////////////////////////////////////////////////////////////////////////
    // top bar login
    ////////////////////////////////////////////////////////////////////////////
    $('#topbarlogin').click(function (event) {
        show_login_form(1, 0, 0);
    });

    $('#topbarregister').click(function (event) {
        show_login_form(2, 0, 0);
    });

    ////////////////////////////////////////////////////////////////////////////
    /// slider price 
    ////////////////////////////////////////////////////////////////////////////

    price_low_val = parseInt($('#price_low').val(), 10);
    price_max_val = parseInt($('#price_max').val(), 10);

   

   

    wpestate_enable_slider('slider_price', 'price_low', 'price_max', 'amount', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef);
    /*
    $("#slider_price").slider({
        stop: function (event, ui) {
            show_pins();
        }
    });
    */
    wpestate_enable_slider('slider_price_widget', 'price_low_widget', 'price_max_widget', 'amount_wd', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef);
    wpestate_enable_slider('slider_price_sh', 'price_low_sh', 'price_max_sh', 'amount_sh', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef);
    wpestate_enable_slider('slider_price_mobile', 'price_low_mobile', 'price_max_mobile', 'amount_mobile', my_custom_curr_pos, my_custom_curr_symbol, my_custom_curr_cur_post,my_custom_curr_coef);



    function slider_control_left_function(element) {
        var step_size, margin_left, new_value, last_element, base_value, parent;
        parent = element.parent();
        step_size   =   parent.find('.shortcode_slider_list').width();
        margin_left =   parseInt(parent.find('.shortcode_slider_list').css('margin-left'), 10);
        new_value   =   margin_left - 389;
        base_value  =   3;
        parent.find('.shortcode_slider_list').css('margin-left', new_value + 'px');
        last_element = parent.find('.shortcode_slider_list li:last-child');
        parent.find('.shortcode_slider_list li:last-child').remove();
        parent.find('.shortcode_slider_list').prepend(last_element);
        restart_js_after_ajax();
        parent.find('.shortcode_slider_list').animate({
            'margin-left': base_value
        }, 800, function () {
        });
              
    }

    function slider_control_right_function(elemenet) {
        var step_size, margin_left, new_value, first_element, parent;
        parent = elemenet.parent();
        step_size   =   parent.find('.shortcode_slider_list').width();
        margin_left =   parseInt(parent.find('.shortcode_slider_list').css('margin-left'), 10);
        new_value   =   margin_left - 389;
        parent.find('.shortcode_slider_list').animate({
            'margin-left': new_value
        }, 800, function () {       
            first_element = parent.find('.shortcode_slider_list li:nth-child(1)');
            parent.find('.shortcode_slider_list li:nth-child(1)').remove();
            parent.find('.shortcode_slider_list').append(first_element);
            parent.find('.shortcode_slider_list').css('margin-left', 3 + 'px');
       
        restart_js_after_ajax();
        });
    }
    
    $('.slider_control_left').click(function () {
        slider_control_left_function($(this));
        //bLazy.revalidate();
    });
    
    $('.slider_control_right').click(function () {
        slider_control_right_function($(this));
        //bLazy.revalidate();
    });
    
    $('.slider_container ').each(function(){
        var element, wrapper;
        element = $(this).find(".slider_control_right");
        wrapper = $(this).find(".shortcode_slider_wrapper").attr('data-auto');
        
        autoscroll_slider = parseInt(wrapper, 10);
        if (autoscroll_slider !== 0) {
     
            setInterval(function () {
                slider_control_right_function(element);
            }, autoscroll_slider);
        }

    });
    
    
    $('#login_user_topbar,#login_pwd_topbar').on('focus', function (e) {
        $('#user_menu_open').addClass('iosfixed');
    });

    $('#estate-carousel .slider-content h3 a,#estate-carousel .slider-content .read_more ').click(function () {
        var new_link;
        new_link =  $(this).attr('href');
        window.open(new_link, '_self', false);
    });

    ////////////////////////////////////////////////////////////////////////////////////////////
    ///city-area-selection
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('#filter_city li').click(function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city   = String($(this).attr('data-value2')).toLowerCase();

        $('#filter_area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value   = String($(this).attr('data-value')).toLowerCase();
         
            if (is_city === value_city || value_city === 'all' || is_city==='undefined') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#sidebar_filter_city li').click(function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city   = String($(this).attr('data-value2')).toLowerCase();
        $('#sidebar_filter_area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value   = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#adv-search-city li').click(function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city   = String($(this).attr('data-value2')).toLowerCase();

        $('#adv-search-area li').each(function () {
            is_city      = String($(this).attr('data-parentcity')).toLowerCase();
            is_city      = is_city.replace(" ", "-");
            area_value   = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#property_city_submit').change(function () {
        var city_value, area_value;
        city_value = $(this).val();
        all_browsers_stuff = $('#property_area_submit_hidden').html();
        $('#property_area_submit').empty().append(all_browsers_stuff);
        $('#property_area_submit option').each(function () {
            area_value = $(this).attr('data-parentcity');
            if (city_value === area_value || area_value === 'all') {
                //  $(this).show();        
            } else {
                //$(this).hide();
                $(this).remove();
            }
        });
    });

    $('#adv_short_select_city li').click(function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city   = String($(this).attr('data-value2')).toLowerCase();
        $('#adv_short_select_area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value  = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#mobile-adv-city li').click(function (event) {
        event.preventDefault();
        var pick, value_city, parent, selected_city, is_city, area_value;
        value_city   = String($(this).attr('data-value2')).toLowerCase();
        $('#mobile-adv-area li').each(function () {
            is_city = String($(this).attr('data-parentcity')).toLowerCase();
            is_city = is_city.replace(" ", "-");
            area_value = String($(this).attr('data-value')).toLowerCase();
            if (is_city === value_city || value_city === 'all') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    ////////////////////////////////////////////////////////////////////////////////////////////
    ///mobile
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('#adv-search-header-mobile').click(function () {
        $('#adv-search-mobile').toggle('300');
    });

    ////////////////////////////////////////////////////////////////////////////////////////////
    ///navigational links
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('.nav-prev,.nav-next ').click(function (event) {
        event.preventDefault();
        var link = $(this).find('a').attr('href');
        window.open(link, '_self', false);
    });

    $('.featured_agent_details_wrapper, .agent-listing-img-wrapper').click(function () {
        var newl = $(this).attr('data-link');
        window.open(newl, '_self', false);
    });

    $('.see_my_list_featured').click(function (event) {
        event.stopPropagation();
    });

    $('.featured_cover').click(function () {
        var newl = $(this).attr('data-link');
        window.open(newl, '_self', false);
    });

    $('.agent_face').hover(
        function () {
            $(this).find('.agent_face_details').fadeIn('500');
        },
        function () {
            $(this).find('.agent_face_details').fadeOut('500');
        }
    );

    $('.agent_unit, .blog_unit,.blog_unit_back,.places_wrapper ,.featured_agent').click(function () {
        var link;
        link = $(this).attr('data-link');
        window.open(link, '_self');
    });
    
    $('.property_listing').click(function (event) {
        var link, classevent;
        classevent=$(event.target);
        
        if(classevent.hasClass('carousel-control')  || classevent.hasClass('fa-angle-left') || classevent.hasClass('fa-angle-right') ){
            return;
        }
        
        link = $(this).attr('data-link');
        window.open(link, '_self');
    });
    
    jQuery('#imagelist i').click(function () {
        var curent = '';
        jQuery(this).parent().remove();
        jQuery('#imagelist .uploaded_images').each(function () {
            curent = curent + ',' + jQuery(this).attr('data-imageid');
        });
        jQuery('#attachid').val(curent);
    });

    jQuery('#imagelist img').dblclick(function () {
        jQuery('#imagelist .uploaded_images .thumber').each(function () {
            jQuery(this).remove();
        });
        jQuery(this).parent().append('<i class="fa thumber fa-star"></i>');
        jQuery('#attachthumb').val(jQuery(this).parent().attr('data-imageid'));
    });

    $('.advanced_search_sidebar li').click(function (event) {
        event.preventDefault();
        var pick, value, parent;
        pick = $(this).text();
        value = $(this).attr('data-value');
        parent = $(this).parent().parent();
        parent.find('.filter_menu_trigger').text(pick).append('<span class="caret caret_sidebar"></span>').attr('data-value', value);
        parent.find('input').val(value);
    });

    $('.adv-search-mobile li').click(function (event) {
        event.preventDefault();
        var pick, value, parent;
        pick = $(this).text();
        value = $(this).attr('data-value');
        parent = $(this).parent().parent();
        parent.find('.filter_menu_trigger').text(pick).append('<span class="caret caret_filter"></span>').attr('data-value', value);
        parent.find('input').val(value);
    });

    $('#switch').click(function () {
        $('.main_wrapper').toggleClass('wide');
    });

    $('#accordion_prop_addr, #accordion_prop_details, #accordion_prop_features').on('shown.bs.collapse', function () {
        $(this).find('h4').removeClass('carusel_closed');
    });

    $('#accordion_prop_addr, #accordion_prop_details, #accordion_prop_features').on('hidden.bs.collapse', function () {
        $(this).find('h4').addClass('carusel_closed');
    });

    $('#adv-search-1 li,#advanced_search_shortcode li,#advanced_search_map_list li').click(function () {
        var pick, value, parent;
        pick = $(this).text();
        value = $(this).attr('data-value');
        parent = $(this).parent().parent();
        parent.find('.filter_menu_trigger').text(pick).append('<span class="caret caret_filter"></span>').attr('data-value', value);
        parent.find('input').val(value);
    });

    $('.advanced_search_map_list_container li').click(function () {
        start_filtering_ajax_map(1);
    });


    

    
    

    $('#check_out_list').change(function () {
        var start_date = $('#check_in_list').val();
        if (start_date !== '') {
            start_filtering_ajax_map(1);
        }
    });

    $('#check_out_list').change(function () {
        var start_date = $('#check_in_list').val();
        if(start_date !== '') {
            start_filtering_ajax_map(1);
        }
    });

    $('#extended_search_check_filter input[type="checkbox"]').click(function () {
        start_filtering_ajax_map(1);
    });

    $("#google_map_prop_list_sidebar #slider_price").slider({
        stop: function (event, ui) {
            show_pins();
            start_filtering_ajax_map(1);
        }
    });

    $('#showinpage,#showinpage_mobile').click(function (event) {
        event.preventDefault();
        if ($('#gmap-full').hasClass('spanselected')) {
            $('#gmap-full').trigger('click');
        }
        start_filtering_ajax(1);
   
    });

    $('#openmap').click(function () {
        if ($(this).find('i').hasClass('fa-angle-down')) {
            $(this).empty().append('<i class="fa fa-angle-up"></i>' + control_vars.close_map);
            if (control_vars.show_adv_search_map_close === 'no') {
                $('.search_wrapper').addClass('adv1_close');
                adv_search_click();
            }
        } else {
            $(this).empty().append('<i class="fa fa-angle-down"></i>' + control_vars.open_map);
        }
        new_open_close_map(2);
    });

    $('#gmap-full').click(function () {
        if ($('#gmap_wrapper').hasClass('fullmap')) {
            $('#gmap_wrapper').removeClass('fullmap').css('height', wrap_h + 'px');
            $('#googleMap').removeClass('fullmap').css('height', map_h + 'px');
            $('#search_wrapper').removeClass('fullscreen_search');
            $('#search_wrapper').removeClass('fullscreen_search_open');
            $('.master_header').removeClass('hidden');
            $('#gmap-controls-wrapper ').removeClass('fullscreenon');
            $('.content_wrapper,#colophon,#openmap').show();
            $('#gmap-controls-wrapper ').removeClass('fullscreenon');

            $('body,html').animate({
                scrollTop: 0
            }, "slow");
            $('#openmap').show();
            $(this).removeClass('spanselected');
        } else {
            wrap_h = $('#gmap_wrapper').outerHeight();
            map_h = $('#googleMap').outerHeight();
            $('#gmap_wrapper,#googleMap').css('height', '100%').addClass('fullmap');
            $('#search_wrapper').addClass('fullscreen_search');
            $('.master_header ').addClass('hidden');
            $('.content_wrapper,#colophon,#openmap').hide();
            $('#gmap-controls-wrapper ').addClass('fullscreenon');
            $(this).addClass('spanselected');
        }

        if ($('#google_map_prop_list_wrapper').hasClass('halfmapfull')) {
            $('#google_map_prop_list_wrapper').removeClass('halfmapfull');
            $('#google_map_prop_list_wrapper').removeClass('halfmapfullx');
            $('.master_header').removeClass('hidden');
            $('#gmap-controls-wrapper ').removeClass('fullscreenon');
             $(this).removeClass('spanselected');
        } else {
            $('#google_map_prop_list_wrapper').addClass('halfmapfull');
            $('#google_map_prop_list_wrapper').addClass('halfmapfullx');
            
        }
        google.maps.event.trigger(map, "resize");
    });

    $('#street-view').click(function () {
        toggleStreetView();
    });


    $('.videoitem iframe').click(function () {
        $('.estate_video_control').remove();
    });

    $(".icon-fav, .share_list,  .compare-action, .dashboad-tooltip, .pack-name, .normal_list_no, .mess_tooltip").hover(
        function () {
            $(this).tooltip('show');
        },
        function () {
            $(this).tooltip('hide');
        }
    );

    $('.share_list').click(function (event) {
        event.stopPropagation();
        var sharediv = $(this).parent().find('.share_unit');
        sharediv.toggle();
        $(this).toggleClass('share_on');
    });

    $('.backtop').click(function (event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0
        }, "slow");
    });

  

    $(".fancybox-thumb").lazyload();
    $(".fancybox-thumb").fancybox({
		prevEffect	: 'none',
		nextEffect	: 'none',
		helpers	: {
			title	: {
				type: 'outside'
			},
			thumbs	: {
				width	: 100,
				height	: 100
			}
		}
    });
    
    
  
    
    $('#carousel-listing .item img').click(function () {
        $("a[rel^='data-fancybox-thumb']:first").click();
    });
    
    $('.imagebody_new .image_gallery').click(function () {
        $("a[rel^='data-fancybox-thumb']:first").click();
    });
     
    
    
    
    $("#geolocation-button").hover(
        function () {
            $('#tooltip-geolocation').fadeIn();
            $('.tooltip').fadeOut("fast");
        },
        function () {
            $('#tooltip-geolocation').fadeOut();
        }
    );

    if (!jQuery.browser.mobile) {
        jQuery('body').on('click', 'a[href^="tel:"]', function () {
            jQuery(this).attr('href', jQuery(this).attr('href').replace(/^tel:/, 'callto:'));
        });
    }
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////
    /// adding total for featured listings  
    ///////////////////////////////////////////////////////////////////////////////////////////
    $('.extra_featured').change(function(){
       var parent= $(this).parent();
       var price_regular  = parseFloat( parent.find('.submit-price-no').text(),10 );
       var price_featured = parseFloat( parent.find('.submit-price-featured').text(),10 );
       var total= price_regular+price_featured;

       if( $(this).is(':checked') ){
            parent.find('.submit-price-total').text(total);
            parent.find('#stripe_form_featured').show();
            parent.find('#stripe_form_simple').hide();
       }else{
           //substract from total
            parent.find('.submit-price-total').text(price_regular);
            parent.find('#stripe_form_featured').hide();
            parent.find('#stripe_form_simple').show();
       }
    });

    






    $('.compare_wrapper').each(function () {
        var cols = $(this).find('.compare_item_head').length;
        $(this).addClass('compar-' + cols);
    });



    $('#list_view').click(function () {
        $(this).toggleClass('icon_selected');
        $('#listing_ajax_container').addClass('ajax12');
        $('#grid_view').toggleClass('icon_selected');
        $('.listing_wrapper').hide().removeClass('col-md-4').removeClass('col-md-3').addClass('col-md-12').fadeIn(400);
        $('.the_grid_view').fadeOut(10, function () {
            $('.the_list_view').fadeIn(300);
        });
    });

    $('#grid_view').click(function () {
        var class_type;
        class_type = $('.listing_wrapper:first-of-type').attr('data-org');
        $(this).toggleClass('icon_selected');
        $('#listing_ajax_container').removeClass('ajax12');
        $('#list_view').toggleClass('icon_selected');
        $('.listing_wrapper').hide().removeClass('col-md-12').addClass('col-md-' + class_type).fadeIn(400);
        $('.the_list_view').fadeOut(10, function () {
            $('.the_grid_view').fadeIn(300);
        });
    });

    $('#add-new-image').click(function () {
        $('<p><label for="file">New Image:</label><input type="file" name="upload_attachment[]" id="file_featured"></p> ').appendTo('#files_area');
    });

    $('.delete_image').click(function () {
        var image_id = $(this).attr('data-imageid');
        curent = $('#images_todelete').val();
        if (curent === '') {
            curent = image_id;
        } else {
            curent = curent + ',' + image_id;
        }

        $('#images_todelete').val(curent);
        $(this).parent().remove();
    });

    $('#googleMap').bind('mousemove', function (e) {
        $('.tooltip').css({'top': e.pageY, 'left': e.pageX, 'z-index': '1'});
    });

    setTimeout(function () {
        $('.tooltip').fadeOut("fast");
    }, 10000);
});




function show_capture() {
    "use strict";
    var position, slideno, slidedif, tomove, curentleft;
    jQuery('#googleMapSlider').hide();
    position = parseInt(jQuery('#carousel-listing .carousel-inner .active').index(), 10);
    jQuery('#carousel-listing  .caption-wrapper span').removeClass('active');
    jQuery("#carousel-listing  .caption-wrapper span[data-slide-to='" + position + "'] ").addClass('active');
    slideno = position + 1;

    slidedif = slideno * 146;
    if (slidedif > 810) {
        tomove = 810 - slidedif;
        jQuery('.post-carusel .carousel-indicators').css('left', tomove + "px");
    } else {
        position = jQuery('.post-carusel .carousel-indicators').css('left', tomove + "px").position();
        curentleft = position.left;

        if (curentleft < 0) {
            tomove = 0;
            jQuery('.post-carusel .carousel-indicators').css('left', tomove + "px");
        }
    }
}

function raisePower(x, y) {
    "use strict";
    return Math.pow(x, y);
}

function shortcode_google_map_load(containermap, lat, long, mapid) {
    "use strict";
    var myCenter, mapOptions, map, marker;
    myCenter = new google.maps.LatLng(lat, long);
    mapOptions = {
        flat: false,
        noClear: false,
        zoom: 15,
        scrollwheel: false,
        draggable: true,
        center: myCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        },
        disableDefaultUI: true
    };

    map = new google.maps.Map(document.getElementById(mapid), mapOptions);
    google.maps.visualRefresh = true;
    marker = new google.maps.Marker({
        position: myCenter,
        map: map
    });

    marker.setMap(map);

}