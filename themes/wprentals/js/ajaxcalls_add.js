/*global $, jQuery, ajaxcalls_vars, document, control_vars, window, control_vars, submit_change,timeConverter, ajaxcalls_add_vars, dashboard_vars, google, fillInAddress, check_booking_valability_internal, mark_as_booked_actions*/
jQuery(document).ready(function ($) {
    "use strict";
    
    $('#allinone_set_custom').click(function(event){
        
        $('#allinone_set_custom').text(ajaxcalls_vars.saving);   
        if (jQuery('#block_dates').is(':checked')  ){
            check_booking_valability_internal_allinone();
        }else{
            wpestate_allinone_owner_insert_customprice_internal();
        }
    
    });
    
function    wpestate_allinone_owner_insert_customprice_internal(){
    var   period_extra_price_per_guest, period_price_per_weekeend, period_checkin_change_over, period_checkin_checkout_change_over, period_min_days_booking,start_from, end_to, listing_edit, new_price, ajaxurl;
     
    start_from      =   jQuery('#start_date_owner_book').val();
    end_to          =   jQuery('#end_date_owner_book').val();
    listing_edit    =   jQuery('#property_id').val();
    new_price       =   jQuery('#new_custom_price').val();
    period_min_days_booking             =   jQuery('#period_min_days_booking').val();
    period_extra_price_per_guest        =   jQuery('#period_extra_price_per_guest').val();
    period_price_per_weekeend           =   jQuery('#period_price_per_weekeend').val();
    period_checkin_change_over          =   jQuery('#period_checkin_change_over').val();
    period_checkin_checkout_change_over =   jQuery('#period_checkin_checkout_change_over').val();
    
    ajaxurl         =   control_vars.admin_url + 'admin-ajax.php';
           
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action'            :   'wpestate_ajax_add_allinone_custom',
            'book_from'         :   start_from,
            'book_to'           :   end_to,
            'listing_id'        :   listing_edit,
            'new_price'         :   new_price,
            'period_min_days_booking'               :   period_min_days_booking,
            'period_extra_price_per_guest'          :   period_extra_price_per_guest,
            'period_price_per_weekeend'             :   period_price_per_weekeend,
            'period_checkin_change_over'            :   period_checkin_change_over,
            'period_checkin_checkout_change_over'   :   period_checkin_checkout_change_over
        },
        success: function (data) {
        
            location.reload();


        },
        error: function (errorThrown) {
        }

    });
}  
    
    
    
function check_booking_valability_internal_allinone() {
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
                allin_one_owner_insert_book_internal();
       
            } else {
                jQuery('#book_dates').empty().text(ajaxcalls_vars.reserve);
            }
        },
        error: function (errorThrown) {
        }
    });
}

function allin_one_owner_insert_book_internal() {
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
            
            wpestate_allinone_owner_insert_customprice_internal();
        },
        error: function (errorThrown) {
        }
    });
}
    
    
    
    
    
    
    
    
    
    
    
    var all_calendar_click = 0;
    var curent_id;
    $('.booking-calendar-wrapper-allinone .has_future').click(function (event) {
       
        var has_reservation, parent, detect_start, start_date, end_date;
        has_reservation =   0;
        detect_start    =   0;
    
        if ($(this).hasClass('calendar-reserved') || $(this).hasClass('pick_block_dates')) { // click on a booked spot
            return;
        }else{
            $(this).addClass('calendar-selected');
        }

     

        if (all_calendar_click === 0) { // start a new period
            all_calendar_click = 1;
            $(this).addClass('calendar-reserved-start');
            curent_id=$(this).attr('data-curent-id');
            
            $('.booking-calendar-wrapper-allinone .has_future[data-curent-id!='+curent_id+']').addClass('pick_block_dates');
            
        } else {
            var curent_id = $(this).attr('data-curent-id');
            
            all_calendar_click = 0;
            $(this).addClass('calendar-reserved-stop');
            parent = $(this).parent().parent();
            $('.has_future[data-curent-id="'+curent_id+'"]').each(function () {
              
                
                if ($(this).hasClass('calendar-reserved-start')) {
                    detect_start = 1;
                    start_date = $(this).attr('data-curent-date');
                }
                
            
                if (detect_start === 1) {
                   // $(this).addClass('calendar-reserved');
                    if ( $(this).hasClass('calendar-reserved') ){ 
                        has_reservation=1;
                    }
                }
                
                if ($(this).hasClass('calendar-reserved-stop')) {
                    detect_start = 0;
                    //$(this).addClass('calendar-reserved');
                    end_date = $(this).attr('data-curent-date');
                }
                
            });
            
            
            $('.clean_reservation').show();
            if(has_reservation==1){
                $('.clean_reservation').hide();
            }
            
            
            $('.booking-calendar-wrapper-allinone .calendar-selected').removeClass('calendar-selected ');
            $('.booking-calendar-wrapper-allinone .has_future').removeClass('pick_block_dates calendar-reserved-stop calendar-reserved-start');
            allinone_mark_as_booked(parent, start_date, end_date,curent_id);
        }   
    });
    
    
    function allinone_mark_as_booked(parent, start_date, end_date,curent_id) {
        jQuery('#allinone_reservation_modal').modal();
        jQuery('#start_date_owner_book').val(timeConverter_noconver(start_date));
        jQuery('#end_date_owner_book').val(timeConverter_noconver(end_date));
      
        jQuery('#property_id').val(curent_id);
        jQuery('#listing_edit').val(curent_id);
       // mark_as_booked_actions();
    }

    
    // delete custom period
    $('.delete_custom_period a').click(function (event) {
        event.preventDefault();
        var parent,ajaxurl, edit_id,from_date,to_date;
        ajaxurl     = ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        
        edit_id     = parseInt( jQuery(this).parent().attr('data-editid'),10  );
        from_date   = parseInt( jQuery(this).parent().attr('data-fromdate'),10  );  
        to_date     = parseInt( jQuery(this).parent().attr('data-todate'),10  );
        parent      =    jQuery(this).parent().parent();
         
     
         $.ajax({
            type:       'POST',
            url:        ajaxurl,
            
            data: {
                'action'            :  'wpestate_ajax_delete_custom_period',
                'edit_id'           :  edit_id,
                'from_date'         :  from_date,
                'to_date'           :  to_date,
               
            },
            success: function (data) {
                parent.remove();
                location.reload();
            },
            error: function (errorThrown) {
            }
        });
         
    });
    
    
    
    
    $('#form_submit_1').click(function () {
      
        if( !$(this).hasClass('externalsubmit') ){
            return;
        }
        var security,ajaxurl,title,prop_category,prop_action_category,property_city,property_area_front,property_country,property_description,guest_no,new_estate;
        
        title               = jQuery('#title').val();
        prop_category       = jQuery('#prop_category_submit').val();
        prop_action_category= jQuery('#prop_action_category_submit').val();
        property_city       = jQuery('#property_city').val();
        
        if(property_city === '' || typeof(property_city)==='undefined'){
            property_city       = jQuery('#property_city_front_autointernal').val(); 
        }
        
        
        property_area_front = jQuery('#property_area_front').val();
        property_country    = jQuery('#property_country').val();
        property_description= jQuery('#property_description').val();
        guest_no            = jQuery('#guest_no').val();
        new_estate          = jQuery('#new_estate').val();
        security            =   jQuery('#security-login-submit').val();
        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
       
        //dataType:   'json',
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            
            data: {
                'action'                :  'wpestate_ajax_front_end_submit',
                'title'                 :  title,
                'prop_category'         :  prop_category,
                'prop_action_category'  :  prop_action_category,
                'property_city'         :  property_city,
                'property_area_front'   :  property_area_front,
                'property_country'      :  property_country,
                'property_description'  :  property_description,
                'guest_no'              :  guest_no,
                'new_estate'            :  new_estate,
                'security'              :  security
            },
            success: function (data) {
             
                jQuery("#new_estate").val('');
                jQuery("#title,#prop_category_submit,#prop_action_category_submit,#guest_no,#property_city_front,#property_country,#property_city,#property_area_front,#property_description").val("");
                jQuery("#new_post").remove();
                show_login_form(1,0,data); 

            },
            error: function (errorThrown) {
            }
        });
        
    });
    
    
    
    
    
    
    var curent_m,curent_m_set, input , defaultBounds, options, componentForm, autocomplete, place, calendar_click, calendar_click_price;
    curent_m=2;
    curent_m_set=1;
    
    $('#calendar-next').click(function () {
        if (curent_m < 10) {
            curent_m = curent_m + 1;
        } else {
            curent_m = 12;
        }

        $('.booking-calendar-wrapper').hide();
        $('.booking-calendar-wrapper').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m + 1) {
                $(this).fadeIn();
            }
        });
    });

    $('#calendar-prev').click(function () {
        if (curent_m > 3) {
            curent_m = curent_m - 1;
        } else {
            curent_m = 2;
        }

        $('.booking-calendar-wrapper').hide();
        $('.booking-calendar-wrapper').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m - 1) {
                $(this).fadeIn();
            }
        });
    });


    $('#calendar-next-internal').click(function () {
        if (curent_m < 10) {
            curent_m = curent_m + 1;
        } else {
            curent_m = 11;
        }

        $(".booking-calendar-wrapper-in").hide();
        $('.booking-calendar-wrapper-in').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m + 1 || curent === curent_m + 2) {
               // $(this).fadeIn();
                $(this).css('display','inline-block');
            }
        });

    });

    $('#calendar-prev-internal').click(function () {
        if (curent_m > 3) {
            curent_m = curent_m - 1;
        } else {
            curent_m = 3;
        }

        $('.booking-calendar-wrapper-in').hide();
        $('.booking-calendar-wrapper-in').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m - 1  || curent === curent_m - 2) {
                //$(this).fadeIn();
                 $(this).css('display','inline-block');
            }
        });
    });
    
    $('#calendar-prev-internal-set').click(function () {
        if (curent_m_set > 1) {
            curent_m_set = curent_m_set - 1;
        } else {
            curent_m_set = 1;
        }

        $('.booking-calendar-wrapper-in').hide();
        $('.booking-calendar-wrapper-in').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m_set ) {
                //$(this).fadeIn();
                 $(this).css('display','inline-block');
            }
        });
    });
    
       $('#calendar-next-internal-set').click(function () {
        if (curent_m_set < 10) {
            curent_m_set = curent_m_set + 1;
        } else {
            curent_m_set = 11;
        }

        $(".booking-calendar-wrapper-in").hide();
        $('.booking-calendar-wrapper-in').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m_set ) {
               // $(this).fadeIn();
                $(this).css('display','inline-block');
            }
        });

    });
    
     $('#calendar-prev-internal-allinone').click(function () {
        if (curent_m_set > 1) {
            curent_m_set = curent_m_set - 1;
        } else {
            curent_m_set = 1;
        }

        $('.booking-calendar-wrapper-allinone').hide();
        $('.booking-calendar-wrapper-allinone').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m_set ) {
                //$(this).fadeIn();
                 $(this).css('display','inline-block');
            }
        });
    });
    
    $('#calendar-next-internal-allinone').click(function () {
      
        if (curent_m_set < 10) {
            curent_m_set = curent_m_set + 1;
        } else {
            curent_m_set = 11;
        }

        $(".booking-calendar-wrapper-allinone ").hide();
        $('.booking-calendar-wrapper-allinone ').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m_set ) {
               // $(this).fadeIn();
                $(this).css('display','inline-block');
            }
        });

    });
    
    
    
    
   // booking-calendar-wrapper-in-price
    $('#calendar-next-internal-price').click(function () {
        if (curent_m < 10) {
            curent_m = curent_m + 1;
        } else {
            curent_m = 11;
        }

        $(".booking-calendar-wrapper-in-price").hide();
        $('.booking-calendar-wrapper-in-price').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m + 1 ) {
                $(this).fadeIn();
            }
        });

    });

    $('#calendar-prev-internal-price').click(function () {
        if (curent_m > 2) {
            curent_m = curent_m - 1;
        } else {
            curent_m = 2;
        }

        $('.booking-calendar-wrapper-in-price').hide();
        $('.booking-calendar-wrapper-in-price').each(function () {
            var curent;
            curent   =   parseInt($(this).attr('data-mno'), 10);
            if (curent === curent_m || curent === curent_m - 1 ) {
                $(this).fadeIn();
            }
        });
    });
    

    $('#title, #prop_category_submit, #prop_action_category_submit, #guest_no, #property_city_front,#property_city_front_autointernal').change(function (event) {
        event.preventDefault();
       
        submit_change();
    });

    $('#close_custom_price_internal').click(function () {
        $('.booking-calendar-wrapper-in-price td').each(function () {
            $(this).removeClass('calendar-reserved-start-price');
            $(this).removeClass('calendar-reserved-stop-price');
            $(this).removeClass('calendar-reserved-price');
        });
    });

    $('#close_reservation_internal').click(function () {
        var start_remove = 0;
        $('.calendar-reserved').each(function () {
            if ($(this).hasClass('calendar-reserved-start')) {
                $(this).removeClass('calendar-reserved-start');
                $(this).removeClass('calendar-reserved');
                start_remove = 1;
            }
            if (start_remove === 1) {
                $(this).removeClass('calendar-reserved');
            }

            if ($(this).hasClass('calendar-reserved-stop')) {
                $(this).removeClass('calendar-reserved-stop');
                $(this).removeClass('calendar-reserved');
                start_remove = 0;
            }
        });
    });

    function submit_change() {
        "use strict";
        var title, prop_category_submit, prop_action_category_submit, guest_no, property_city, error_report, has_err;
        title = $('#title').val();
        prop_category_submit = $('#prop_category_submit').val();
        prop_action_category_submit = $('#prop_action_category_submit').val();
        guest_no = $('#guest_no').val();
        property_city = $('#property_city').val();
        
    
        if (document.getElementById('property_city_front_autointernal')) {
            property_city = $('#property_city_front_autointernal').val();
        }
 
        has_err = 0;

        if (title === '') {
            error_report = error_report + dashboard_vars.err_title + "</br>";
            has_err = 1;
        }

        if (prop_category_submit === '' || prop_category_submit === -1 || prop_category_submit === '-1') {
            error_report = error_report + dashboard_vars.err_category + "</br>";
            has_err = 1;
        }

        if (prop_action_category_submit === '' || prop_action_category_submit === -1 || prop_action_category_submit === '-1') {
            error_report = error_report + dashboard_vars.err_type + "</br>";
            has_err = 1;
        }

        if (guest_no === '' || guest_no === 0 || guest_no === '0') {
            error_report = error_report + dashboard_vars.err_guest + "</br>";
            has_err = 1;
        }

        if (property_city === '') {
            has_err = 1;
        }

        if (has_err === 1) {
        } else {
            $('#form_submit_1').prop("disabled", false);
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    /// autocomplete for submission step 1
    ////////////////////////////////////////////////////////////////////////////
    input = (document.getElementById('property_city_front'));
    defaultBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(-90, -180),
        new google.maps.LatLng(90, 180)
    );

    options = {
        bounds: defaultBounds,
        types: ['(cities)'],
     
    };

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
        postal_code_prefix: 'short_name',
        neighborhood: 'long_name'
    };


    if (document.getElementById('property_city_front')) {
        autocomplete = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            place = autocomplete.getPlace();
            fillInAddress(place);
         //  console.log(place);
        });
    }

    function fillInAddress(place) {
        var i, addressType, temp, val ,have_city,admin_area;
        have_city=0;
        admin_area='';
        
        for (i = 0; i < place.address_components.length; i++) {
            addressType = place.address_components[i].types[0];
            temp = '';
            val = place.address_components[i][componentForm[addressType]];
            if (addressType === 'street_number' || addressType === 'route') {
              //  document.getElementById('property_address').value =  document.getElementById('property_address').value +', '+ val;
            } else if (addressType === 'neighborhood') {

            } else if (addressType === 'postal_code_prefix') {

            } else if (addressType === 'postal_code') {

            } else if (addressType === 'administrative_area_level_4') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_3') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_2') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'administrative_area_level_1') {
                admin_area = wpestate_build_admin_area(admin_area,val);
            } else if (addressType === 'locality') {
                $('#property_city').val(val); have_city=1;
            } else if (addressType === 'country') {
                $('#property_country').val(val); have_city=1;
            } else {

            }
            if(have_city===0){
                second_measure_city_submit('property_city',place.adr_address);
            }
        }
        submit_change();
    }
 
    function  second_measure_city_submit(stringplace,adr_address){
        var new_city;
        new_city = $(adr_address).filter('span.locality').html() ;
      
        $('#'+stringplace).val(new_city);
    }
    
    function wpestate_build_admin_area(admin_area,val){
        if(admin_area ===''){
            admin_area = admin_area+val;
        }else{
            admin_area = admin_area+", "+val;
        }
        
        $('#property_admin_area').val(admin_area);
      
        return admin_area;
    }
    
 
    ////////////////////////////////////////////////////////////////////////////
    //calendar function
    //////////////////////////////////////////////////////////////////////////// 
    function custom_price_set(parent, start_date, end_date) {
        jQuery('#owner_price_modal').modal();
        jQuery('#start_date_owner_book').val(timeConverter_noconver(start_date));
        jQuery('#end_date_owner_book').val(timeConverter_noconver(end_date));
        mark_as_price_actions();
    }
    
    function mark_as_price_actions(){
        "use strict";
        var   period_extra_price_per_guest, period_price_per_weekeend, period_checkin_change_over, period_checkin_checkout_change_over, period_min_days_booking,start_from, end_to, listing_edit, new_price, ajaxurl;
        $('#set_price_dates').click(function () {
            jQuery('#book_dates').empty().text(ajaxcalls_vars.saving);
            start_from      =   jQuery('#start_date_owner_book').val();
            end_to          =   jQuery('#end_date_owner_book').val();
            listing_edit    =   jQuery('#listing_edit').val();
            new_price       =   jQuery('#new_custom_price').val();
            period_min_days_booking             =   jQuery('#period_min_days_booking').val();
            period_extra_price_per_guest        =   jQuery('#period_extra_price_per_guest').val();
            period_price_per_weekeend           =   jQuery('#period_price_per_weekeend').val();
            period_checkin_change_over          =   jQuery('#period_checkin_change_over').val();
            period_checkin_checkout_change_over =   jQuery('#period_checkin_checkout_change_over').val();
            
            
            ajaxurl         =   control_vars.admin_url + 'admin-ajax.php';
            if(new_price===''){
              //  return;
            }
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_ajax_add_custom_price',
                    'book_from'         :   start_from,
                    'book_to'           :   end_to,
                    'listing_id'        :   listing_edit,
                    'new_price'         :   new_price,
                    'period_min_days_booking'               :   period_min_days_booking,
                    'period_extra_price_per_guest'          :   period_extra_price_per_guest,
                    'period_price_per_weekeend'             :   period_price_per_weekeend,
                    'period_checkin_change_over'            :   period_checkin_change_over,
                    'period_checkin_checkout_change_over'   :   period_checkin_checkout_change_over
                },
                success: function (data) {
                   
                    jQuery(this).removeClass('calendar-reserved-stop-price');
                    jQuery(this).removeClass('calendar-reserved-start-price');
                    jQuery('#owner_price_modal').modal('hide');
                    jQuery('.booking-calendar-wrapper-in-price .calendar-reserved-price .price-day').remove();
                    jQuery('.booking-calendar-wrapper-in-price .calendar-reserved-price .custom_set_price').remove();
                    jQuery('.booking-calendar-wrapper-in-price .calendar-reserved-price').append('<span class="custom_set_price">'+data+'</span>');
                    jQuery('.booking-calendar-wrapper-in-price .calendar-reserved-price').removeClass('calendar-reserved-price,calendar-reserved-start-price,calendar-reserved-stop-price');
                    jQuery('#book_dates').empty().text(ajaxcalls_vars.reserve);
                    jQuery('#book_notes').val('');
                    jQuery('.booking-calendar-wrapper-in-price td').removeClass('calendar-reserved-stop-price');
                    jQuery('.booking-calendar-wrapper-in-price td').removeClass('calendar-reserved-start-price');
                    jQuery('.booking-calendar-wrapper-in-price td').removeClass('calendar-reserved-price');
                    location.reload();
                },
                error: function (errorThrown) {
                }
            });
        });
    }
    
    
    function mark_as_booked(parent, start_date, end_date) {
        jQuery('#owner_reservation_modal').modal();
        jQuery('#start_date_owner_book').val(timeConverter_noconver(start_date));
        jQuery('#end_date_owner_book').val(timeConverter_noconver(end_date));

        mark_as_booked_actions();
    }

    function mark_as_booked_actions() {
        $('#book_dates').unbind('click');
        $('#book_dates').click(function () {
            check_booking_valability_internal();
        });
    }

    calendar_click = 0;
    $('.booking-calendar-wrapper-in .has_future').click(function (event) {
  
        if ($(this).hasClass('calendar-reserved')) { // click on a booked spot
            if (!$(this).hasClass('start_reservation') ){
                return;
            } else{
               
                if(calendar_click === 0){
                    return;
                }else{
                    $(this).addClass('calendar-selected');
                }
                
            }   
            
        }else{
            $(this).addClass('calendar-selected');
        }

        var parent, detect_start, start_date, end_date;
        detect_start = 0;

        if (calendar_click === 0) { // start a new period
            calendar_click = 1;
            $(this).addClass('calendar-reserved-start');
        } else {
            calendar_click = 0;
            $(this).addClass('calendar-reserved-stop');
            parent = $(this).parent().parent();
            $('.has_future').each(function () {
                if ($(this).hasClass('calendar-reserved-start')) {
                    detect_start = 1;
                    start_date = $(this).attr('data-curent-date');
                }
                if ($(this).hasClass('calendar-reserved-stop')) {
                    detect_start = 0;
                    $(this).addClass('calendar-reserved');
                    end_date = $(this).attr('data-curent-date');
                }
                if (detect_start === 1) {
                    $(this).addClass('calendar-reserved');
                }
            });
            $('.booking-calendar-wrapper-in .calendar-selected').removeClass('calendar-selected');
            mark_as_booked(parent, start_date, end_date);
        }   
    });

    // custom adding price
    calendar_click_price = 0;
    $('.booking-calendar-wrapper-in-price .has_future').click(function () {
       
        $(this).addClass('calendar-selected');
     

        var parent, detect_start, start_date, end_date;
        detect_start = 0;

        if (calendar_click_price === 0) { // start a new period
            calendar_click_price = 1;
            $(this).addClass('calendar-reserved-start-price');
        } else {
            calendar_click_price = 0;
            $(this).addClass('calendar-reserved-stop-price');
            parent = $(this).parent().parent();
            $('.has_future').each(function () {
                if ($(this).hasClass('calendar-reserved-start-price')) {
                    detect_start = 1;
                    start_date = $(this).attr('data-curent-date');
                }
                if ($(this).hasClass('calendar-reserved-stop-price')) {
                    detect_start = 0;
                    $(this).addClass('calendar-reserved-price');
                    end_date = $(this).attr('data-curent-date');
                }
                if (detect_start === 1) {
                    $(this).addClass('calendar-reserved-price');
                }
            });
            $('.booking-calendar-wrapper-in-price .calendar-selected').removeClass('calendar-selected');
            custom_price_set(parent, start_date, end_date);
        }   
    });
    
     function timeConverter_noconver(UNIX_timestamp) {
        var a, year, month, date, time;
    
       
        a       = new Date(UNIX_timestamp * 1000);
        year    = a.getUTCFullYear();
        month   = a.getUTCMonth() + 1;
        date    = a.getUTCDate();
   
        time    = year + '-' + ('0' + month).slice(-2)   + '-' + date;
        return time;
    }

    
    function timeConverter(UNIX_timestamp) {
        var a, year, month, date, time;
        var d = new Date()
        var n = d.getTimezoneOffset();
      
    
        a       = new Date(UNIX_timestamp * 1000+n*60000);
        year    = a.getUTCFullYear();
        month   = a.getMonth() + 1;
        date    = a.getDate();
        
    
        
        time    = year + '-' + ('0' + month).slice(-2)   + '-' + date;
        return time;
    }

    ////////////////////////////////////////////////////////////////////////////
    //edit property location
    //////////////////////////////////////////////////////////////////////////// 
    $('#edit_prop_ammenities').click(function () {
        var jsonData, ajaxurl, listing_edit, custom_fields_amm, counter, i;
        listing_edit    =  jQuery('#listing_edit').val();

        jsonData = JSON.parse(ajaxcalls_add_vars.transport_custom_array_amm);
        for (i = 0; i < jsonData.length; i++) {
            counter = jsonData[i];
            if (jQuery("#" + counter).prop('checked')) {
                custom_fields_amm = custom_fields_amm + "~1";
            } else {
                custom_fields_amm = custom_fields_amm + "~0";
            }
        }

        ajaxurl  =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        $('#profile_message').empty().append('<div class="login-alert">' +  ajaxcalls_vars.saving + '<div>');
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            dataType:   'json',
            data: {
                'action'                :  'wpestate_ajax_update_listing_ammenities',
                'listing_edit'          :  listing_edit,
                'custom_fields_amm'     :  custom_fields_amm
            },
            success: function (data) {
                if (data.edited) {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                } else {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                }
                var redirect = jQuery('.next_submit_page').attr('href');
                window.location = redirect;
            },
            error: function (errorThrown) {
            }
        });
    });
    ////////////////////////////////////////////////////////////////////////////
    //edit property location
    //////////////////////////////////////////////////////////////////////////// 
    $('#edit_prop_locations').click(function () {
        var jsonData, ajaxurl, listing_edit, property_county, property_state, property_address, property_zip, property_latitude, property_longitude, google_camera_angle, property_google_view;
        jsonData = JSON.parse(ajaxcalls_add_vars.tranport_custom_array);
        property_address        =  jQuery('#property_address').val();
        property_zip            =  jQuery('#property_zip').val();
        property_county         =  jQuery('#property_county').val();
        property_state          =  jQuery('#property_state').val();
            
        property_latitude       =  jQuery('#property_latitude').val();
        property_longitude      =  jQuery('#property_longitude').val();
        google_camera_angle     =  jQuery('#google_camera_angle').val();
        listing_edit            =  jQuery('#listing_edit').val();
       
        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        $('#profile_message').empty().append('<div class="login-alert">' +  ajaxcalls_vars.saving + '<div>');
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            dataType:   'json',
            data: {
                'action'                    :  'wpestate_ajax_update_listing_location',
                'property_address'          :  property_address,
                'property_zip'              :  property_zip,
                'property_latitude'         :  property_latitude,
                'property_longitude'        :  property_longitude,
                'google_camera_angle'       :  google_camera_angle,
                'property_state'            :   property_state,
                'property_county'           :   property_county,
                'listing_edit'              :   listing_edit

            },
            success: function (data) {

                if (data.edited) {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                } else {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                }
                var redirect = jQuery('.next_submit_page').attr('href');
                window.location = redirect;
            },
            error: function (errorThrown) {
            }
        });
    });
        ////////////////////////////////////////////////////////////////////////////
    //edit property calendar
    //////////////////////////////////////////////////////////////////////////// 
    $('#edit_calendar').click(function () {
        var jsonData, ajaxurl, listing_edit, property_icalendar_import;
       
        property_icalendar_import   =  jQuery('#property_icalendar_import').val();
        listing_edit                =  jQuery('#listing_edit').val();
       
        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        $('#profile_message2').empty().append('<div class="login-alert">' +  ajaxcalls_vars.saving + '<div>');
        
     
               
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            dataType:   'json',
            data: {
                'action'                    :  'wpestate_ajax_update_ical_feed',
                'property_icalendar_import' :  property_icalendar_import,
                'listing_edit'              :   listing_edit

            },
            success: function (data) {
             
                if (data.edited) {
                    //console.log('data'+data.response)
                    $('#profile_message2').empty().append('<div class="login-alert">' + data.response + '<div>');
                } else {
                    $('#profile_message2').empty().append('<div class="login-alert">' + data.response + '<div>');
                }

            },
            error: function (errorThrown) {
            
            }
        });
    });
    
    
    $('#delete_imported_dates').click(function(event){
        event.preventDefault();
        var edit_id, ajaxurl;
       
        edit_id         =  jQuery(this).attr('data-edit-id');
        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
          
            data: {
                'action'    :  'wpestate_ajax_delete_imported_dates',
                'edit_id'   : edit_id,
            },
            success: function (data) {
                if(data=='done'){
                 
                    location.reload();
                }

            },
            error: function (errorThrown) {
            }
        });
    });
        
  
    
    ////////////////////////////////////////////////////////////////////////////
    //edit property details
    ////////////////////////////////////////////////////////////////////////////  
    $('#edit_prop_details').click(function () {
        var i, jsonData, ajaxurl, property_size, property_rooms, property_bedrooms, property_bathrooms, listing_edit, custom_fields_val, variable, counter;
        property_size       =  jQuery('#property_size').val();
        property_rooms      =  jQuery('#property_rooms').val();
        property_bedrooms   =  jQuery('#property_bedrooms').val();
        property_bathrooms  =  jQuery('#property_bathrooms').val();
        listing_edit        =  jQuery('#listing_edit').val();
        custom_fields_val   =   '';
        jsonData = JSON.parse(ajaxcalls_add_vars.tranport_custom_array);
        for (i = 0; i < jsonData.length; i++) {
            counter = jsonData[i];
            custom_fields_val = custom_fields_val + "~" + $("#" + counter).val();
        }

        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        $('#profile_message').empty().append('<div class="login-alert">' +  ajaxcalls_vars.saving + '<div>');

        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            dataType:   'json',
            data: {
                'action'                :  'wpestate_ajax_update_listing_details',
                'property_size'         :  property_size,
                'property_rooms'        :  property_rooms,
                'property_bedrooms'     :  property_bedrooms,
                'property_bathrooms'    :  property_bathrooms,
                'listing_edit'          :  listing_edit,
                'custom_fields_val'     :  custom_fields_val
            },
            success: function (data) {

                if (data.edited) {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                } else {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                }
                
                var redirect = jQuery('.next_submit_page').attr('href');
                window.location = redirect;
            },
            error: function (errorThrown) {
            }
        });
    });
    ////////////////////////////////////////////////////////////////////////////
    //edit property images
    ////////////////////////////////////////////////////////////////////////////  
    $('#edit_prop_image').click(function () {
        var ajaxurl, video_type, video_id, attachid, attachthumb, listing_edit;
        video_type    =  jQuery('#embed_video_type').val();
        video_id      =  jQuery('#embed_video_id').val();
        attachid      =  jQuery('#attachid').val();
        attachthumb   =  jQuery('#attachthumb').val();
        listing_edit  =  jQuery('#listing_edit').val();

        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        $('#profile_message').empty().append('<div class="login-alert">' +  ajaxcalls_vars.saving + '<div>');
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            dataType:   'json',
            data: {
                'action'         :  'wpestate_ajax_update_listing_images',
                'video_type'     :  video_type,
                'video_id'       :  video_id,
                'attachid'       :  attachid,
                'attachthumb'    :  attachthumb,
                'listing_edit'   :  listing_edit

            },
            success: function (data) {
                if (data.edited) {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                } else {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                }
                var redirect = jQuery('.next_submit_page').attr('href');
                window.location = redirect;
            },
            error: function (errorThrown) {
            }
        });
    });


    ////////////////////////////////////////////////////////////////////////////
    //edit property price
    ////////////////////////////////////////////////////////////////////////////   
    $('#edit_prop_price').click(function () {
        var overload_guest,ajaxurl,checkin_checkout_change_over, checkin_change_over, price_per_weekeend,extra_price_per_guest,price_per_guest_from_one, price, price_label, price_week, price_month, listing_edit, city_fee, cleaning_fee,cleaning_fee_per_day,city_fee_per_day,min_days_booking;
        price           =  jQuery('#property_price').val();
        city_fee        =  jQuery('#city_fee').val();
        cleaning_fee    =  jQuery('#cleaning_fee').val();
        price_label     =  jQuery('#property_label').val();
        price_week      =  jQuery('#property_price_per_week').val();
        price_month     =  jQuery('#property_price_per_month').val();
        listing_edit    =  jQuery('#listing_edit').val();
        
        
        cleaning_fee_per_day        =   0;
        city_fee_per_day            =   0;
        price_per_guest_from_one    =   0;
        overload_guest              =   0;
        if (jQuery('#cleaning_fee_per_day').is(':checked') ){
            cleaning_fee_per_day        =  1;
        }
        if (jQuery('#city_fee_per_day').is(':checked')  ){
            city_fee_per_day        =  1;
        }
        if (jQuery('#price_per_guest_from_one').is(':checked')  ){
            price_per_guest_from_one        =  1;
        }
        if (jQuery('#overload_guest').is(':checked')  ){
            overload_guest        =  1;
        }
        
        
      
        min_days_booking            =  jQuery('#min_days_booking').val();
        extra_price_per_guest       =  jQuery('#extra_price_per_guest').val();
        price_per_weekeend          =  jQuery('#price_per_weekeend').val();
        checkin_change_over         =  jQuery('#checkin_change_over').val();
        checkin_checkout_change_over=  jQuery('#checkin_checkout_change_over').val();


       
        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        $('#profile_message').empty().append('<div class="login-alert">' +  ajaxcalls_vars.saving + '<div>');
         // 
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            dataType:   'json',
            data: {
                'action'                        :   'wpestate_ajax_update_listing_price',
                'price'                         :   price,
                'price_week'                    :   price_week,
                'price_month'                   :   price_month,
                'listing_edit'                  :   listing_edit,
                'city_fee'                      :   city_fee,
                'cleaning_fee'                  :   cleaning_fee,
                'cleaning_fee_per_day'          :   cleaning_fee_per_day,
                'city_fee_per_day'              :   city_fee_per_day,
                'min_days_booking'              :   min_days_booking,
                'price_per_guest_from_one'      :   price_per_guest_from_one,
                'price_per_weekeend'            :   price_per_weekeend,
                'checkin_change_over'           :   checkin_change_over,
                'checkin_checkout_change_over'  :   checkin_checkout_change_over,
                'extra_price_per_guest'         :   extra_price_per_guest,
                'overload_guest'                :   overload_guest
            },
            success: function (data) {
            
                if (data.edited) {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                } else {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                }
                var redirect = jQuery('.next_submit_page').attr('href');
                window.location = redirect;
            },
            error: function (errorThrown) {
            }
        });
    });

    ////////////////////////////////////////////////////////////////////////////
    //edit property description
    ////////////////////////////////////////////////////////////////////////////
    $('#edit_prop_1').click(function () {
        var ajaxurl, title, category, action_category, guests, city, country, area,listing_edit,prop_desc,property_admin_area;
        title           =  jQuery('#title').val();
        category        =  jQuery('#prop_category_submit').val();
        action_category =  jQuery('#prop_action_category_submit').val();
        guests          =  jQuery('#guest_no').val();
        city            =  jQuery('#property_city').val();
        if(city ===''){
            city            =  jQuery('#property_city_front_autointernal').val(); 
        }
        
        area            =  jQuery('#property_area_front').val();
        country         =  jQuery('#property_country').val();
        listing_edit    =  jQuery('#listing_edit').val();
        prop_desc       =  jQuery('#property_description').val();
        property_admin_area = jQuery ('#property_admin_area').val();
        ajaxurl         =  ajaxcalls_add_vars.admin_url + 'admin-ajax.php';
        
    
      
        $('#profile_message').empty().append('<div class="login-alert">' +  ajaxcalls_vars.saving + '<div>');
        $.ajax({
            type:       'POST',
            url:        ajaxurl,
            dataType:   'json',
            data: {
                'action'            :   'wpestate_ajax_update_listing_description',
                'title'             :   title,
                'category'          :   category,
                'action_category'   :   action_category,
                'guests'            :   guests,
                'city'              :   city,
                'area'              :   area,
                'country'           :   country,
                'listing_edit'      :   listing_edit,
                'prop_desc'         :   prop_desc,
                'property_admin_area':  property_admin_area
            },
            success: function (data) {
                if (data.edited) {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                } else {
                    $('#profile_message').empty().append('<div class="login-alert">' + data.response + '<div>');
                }
                var redirect = jQuery('.next_submit_page').attr('href');
                window.location = redirect;
            },
            error: function (errorThrown) {
            }
        });
    });
}); // end jquery