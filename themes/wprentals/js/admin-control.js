/*global $, jQuery, document, window, tb_show, tb_remove ,admin_control_vars*/

jQuery(document).ready(function ($) {
    "use strict";
    
    
    
    $('.css_modal_close').click(function(){
        $('#css_modal').hide();
    })
    
    $('#copycsscode').click(function(){
        $('#css_modal').html();
      
    })
    
    
    $('#activate_pack_reservation_fee').click(function(){
        var book_id, invoice_id,ajaxurl,type;
        
        book_id     = $(this).attr('data-item');
        invoice_id  = $(this).attr('data-invoice');
        type        = $(this).attr('data-type');
        ajaxurl     =   admin_control_vars.ajaxurl;
    
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_admin_activate_reservation_fee',
            'book_id'       :   book_id,
            'invoice_id'    :   invoice_id,
        },
        success: function (data) {  
            jQuery("#activate_pack_reservation_fee").remove();
            jQuery("#invnotpaid").remove(); 
        },
        error: function (errorThrown) {
        }
    });//end ajax  
        
    });
    
    
    
     $('#activate_pack_listing').click(function(){
        var item_id, invoice_id,ajaxurl,type;
        
        item_id     = $(this).attr('data-item');
        invoice_id  = $(this).attr('data-invoice');
        type        = $(this).attr('data-type');
        ajaxurl     =   admin_control_vars.ajaxurl;
    
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_activate_purchase_listing',
            'item_id'       :   item_id,
            'invoice_id'    :   invoice_id,
            'type'          :   type
           
        },
        success: function (data) {  
            jQuery("#activate_pack_listing").remove();
            jQuery("#invnotpaid").remove(); 
          
           
        },
        error: function (errorThrown) {}
    });//end ajax  
        
    });
    
     ///////////////////////////////////////////////////////////////////////////////
    /// activate purchase
    ///////////////////////////////////////////////////////////////////////////////
    
     $('#activate_pack').click(function(){
        var item_id, invoice_id,ajaxurl;
        
        item_id     =   $(this).attr('data-item');
        invoice_id  =   $(this).attr('data-invoice');
        ajaxurl     =   admin_control_vars.ajaxurl;
    
    
      
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
        data: {
            'action'        :   'wpestate_activate_purchase',
            'item_id'       :   item_id,
            'invoice_id'    :   invoice_id
           
        },
        success: function (data) {   
            jQuery("#activate_pack").remove();
            jQuery("#invnotpaid").remove(); 
           
        },
        error: function (errorThrown) {}
    });//end ajax  
        
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    var formfield, imgurl;
    $('#page_custom_image_button').click(function () {
        formfield = $('#page_custom_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            $('#page_custom_image').val(imgurl);
            tb_remove();
        };
        return false;
    });

    $('.category_featured_image_button').click(function () {
        var parent = $(this).parent();
        formfield  = parent.find('#category_featured_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            parent.find('#category_featured_image').val(imgurl);
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
    });
    
    $('.category_icon_image_button').click(function () {
        var parent = $(this).parent();
        formfield  = parent.find('#category_icon_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        window.send_to_editor = function (html) {
            imgurl = $('img', html).attr('src');
            parent.find('#category_icon_image').val(imgurl);
            var theid = $('img', html).attr('class');
            var thenum = theid.match(/\d+$/)[0];
            parent.find('#category_attach_id').val(thenum);
            tb_remove();
        };
        return false;
    });

});