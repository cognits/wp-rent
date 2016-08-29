<?php
if ($color_scheme == 'yes') {
    $main_color                     =  esc_html ( get_option('wp_estate_main_color','') );
    $background_color               = esc_html( get_option('wp_estate_background_color', '') );
    $content_back_color             = esc_html( get_option('wp_estate_content_back_color', '') );
    $header_color                   = esc_html( get_option('wp_estate_header_color', '') );
    $breadcrumbs_font_color         = esc_html(get_option('wp_estate_breadcrumbs_font_color', '') );
    $font_color                     = esc_html(get_option('wp_estate_font_color', '') );
    /* ---- */   $link_color                     = esc_html(get_option('wp_estate_link_color', '') );
    $headings_color                 = esc_html(get_option('wp_estate_headings_color', '') );
    $footer_back_color              = esc_html(get_option('wp_estate_footer_back_color', '') );
    $footer_font_color              = esc_html(get_option('wp_estate_footer_font_color', '') );
    $footer_copy_color              = esc_html(get_option('wp_estate_footer_copy_color', '') );
    $sidebar_widget_color           = esc_html(get_option('wp_estate_sidebar_widget_color', '') );
    $sidebar_heading_color          = esc_html ( get_option('wp_estate_sidebar_heading_color','') );
    $sidebar_heading_boxed_color    = esc_html ( get_option('wp_estate_sidebar_heading_boxed_color','') );
    $sidebar2_font_color            = esc_html(get_option('wp_estate_sidebar2_font_color', '') );
    $menu_font_color                = esc_html(get_option('wp_estate_menu_font_color', '') );
    $menu_hover_back_color          = esc_html(get_option('wp_estate_menu_hover_back_color', '') );
    $menu_hover_font_color          = esc_html(get_option('wp_estate_menu_hover_font_color', '') );
    $agent_color                    = esc_html(get_option('wp_estate_agent_color', '') );
    $top_bar_back                   = esc_html ( get_option('wp_estate_top_bar_back','') );
    $top_bar_font                   = esc_html ( get_option('wp_estate_top_bar_font','') );
    $adv_search_back_color          = esc_html ( get_option('wp_estate_adv_search_back_color ','') );
    $adv_search_font_color          = esc_html ( get_option('wp_estate_adv_search_font_color','') );
    $box_content_back_color         =  esc_html ( get_option('wp_estate_box_content_back_color','') );
    $box_content_border_color       =  esc_html ( get_option('wp_estate_box_content_border_color','') );
    $hover_button_color       =  esc_html ( get_option('wp_estate_hover_button_color ','') );
   
/// Custom Colors
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($main_color != '') {
print'
.similar_listings_wrapper{
    background:transparent;
}
.owner_area_wrapper_sidebar,
.listing_type_1 .listing_main_image_price,
.owner-page-wrapper{
    background-image:none;
}
.property_header2 .property_categs .check_avalability:hover,
.listing_type_1 .check_avalability{
background-color:transparent!important;
}

#agent_submit_contact,
.panel-title-arrow,
.owner_area_wrapper_sidebar,
.listing_type_1 .listing_main_image_price,
.property_listing .tooltip-inner,
.pack-info .tooltip-inner,
.pack-unit .tooltip-inner,
.adv-2-header,
.check_avalability:hover,
.property_header2 .property_categs .check_avalability,
.owner-page-wrapper,
.calendar-legend-reserved,   
.featured_div,
.wpestate_tour .ui-tabs .ui-tabs-nav li.ui-tabs-active,
.ll-skin-melon td .ui-state-active,
.ll-skin-melon td .ui-state-hover,
.price-day,
.calendar-legend-reserved,
.calendar-reserved,
#slider_price_mobile .ui-widget-header,
#slider_price_sh .ui-widget-header,
#slider_price .ui-widget-header,
#slider_price_widget .ui-widget-header,
.slider_control_left,
.slider_control_right,   
.wpestate_accordion_tab .ui-state-active,
.wpestate_accordion_tab .ui-state-active ,
.wpestate_accordion_tab .ui-state-active,
.wpestate_tabs .ui-tabs .ui-tabs-nav li.ui-tabs-active,
.wpestate_progress_bar.vc_progress_bar .vc_single_bar.bar_blue .vc_bar,
.wpestate_posts_grid.wpb_teaser_grid .categories_filter li, 
.wpestate_posts_grid.wpb_categories_filter li,
.featured_second_line,    
.presenttw,
#colophon .social_sidebar_internal a:hover,
#primary .social_sidebar_internal a:hover ,
.comment-form #submit,
.property_menu_item i:hover,
.ball-pulse > div ,
.icon-fav-on-remove,
.share_unit,
#submit_action:hover,    
#adv-search-header-mobile,    
.red,
.pack-info .tooltip-inner,
.pack-unit .tooltip-inner,
.user_mobile_menu_list li:hover,
.theme-slider-view,
.listing-hover,
.ui-widget-content .ui-state-hover, 
.ui-widget-header .ui-state-hover, 
.ui-state-focus, 
.ui-widget-content .ui-state-focus, 
.ui-widget-header .ui-state-focus,
#wp-submit-register, 
#wp-forgot-but, 
#wp-login-but, 
.comment-form #submit, 
#wp-forgot-but_shortcode, 
#wp-login-but-wd, 
#wp-submit-register_wd,
#advanced_submit_shorcode,
.search_dashborad_header .wpestate_vc_button,
#submit_mess_front,
.modal-content #wp-forgot-but_mod,
#imagelist .uploaded_images i,
#aaiu-uploader ,
#aaiu-uploader:hover,
.user_dashboard_panel_guide .active:after,
.user_dashboard_panel_guide .guide_past:before,
.user_dashboard_panel_guide .guide_past:after,
.mess_send_reply_button, #change_pass, #update_profile, 
#book_dates, 
#aaiu-uploader,
#wp-submit-register_wd_mobile,
#wp-forgot-but_mobile,
#wp-login-but-wd-mobile,
#set_price_dates,
.action1_booking,
.generate_invoice,
#add_inv_expenses,
#add_inv_discount,
#book_dates,
#allinone_set_custom,
#edit_prop_ammenities,
#edit_calendar,
#edit_prop_locations,
#google_capture,
#edit_prop_details,
#edit_prop_image,
#edit_prop_price,
#edit_prop_1, 
#form_submit_1, 
#submit_mess_front, 
.modal-content #wp-login-but,
#wp-login-but_sh,
#wp-submit-register_sh{
    background-color: #' . $main_color . '!important;
}

.user_mobile_menu_list li:hover, .mobilex-menu li:hover,
.double-bounce1, .double-bounce2{
  background-color: #' . $main_color . ';
}

.property_header2 .property_categs .check_avalability:hover,
.pack-name,.user_dashboard_links a:hover i,
.property_ratings_agent i, .property_ratings i,
.prop_pricex,.property_unit_v2 .price_unit,
.wpestate_recent_tweets .item:after,
.panel-title:hover,
.blog_featured.type_1_class:hover .blog-title-link, .places_wrapper.type_1_class:hover .featured_listing_title, .featured_property .property_listing:hover .featured_listing_title,
.signuplink:hover,#submit_action:hover,.category_details_wrapper a:hover ,
.agent-flex:hover .agent-title-link,
.property_flex:hover .listing_title_unit,
#access > ul > li:hover > a ,
#amount_wd,
#amount, 
#amount_sh,
.more_list:hover,
.single-content p a:hover,
#contact_me_long_owner:hover, #contact_me_long:hover,
#view_more_desc,
input[type="checkbox"]:checked:before,
.user_dashboard_panel_guide .active{
    color: #' . $main_color . '!important;
}

.property_ratings_agent .owner_total_reviews,
.property_ratings_agent i {
    color: #fff!important;
}
.mobile-trigger-user:hover i, .mobile-trigger:hover i,
.carousel-control-theme-prev:hover, .carousel-control-theme-next:hover,
.hover_price,
#user_terms_register_topbar_link:hover,
#amount_mobile,
#amount_sh,
#amount_wd,
#amount,
.front_plan_row:hover,
.delete_search:hover,
.wpestate_posts_grid .vc_read_more,
.featured_article:hover h2 a,
.featured_article:hover .featured_article_right,
.user_dashboard_listed a,
.pack-listing-title,
.user_dashboard_links .user_tab_active i,
.idx-price,
#infobox_title:hover,
.info_details a:hover,
.contact_info_details h2,
#colophon .widget-container li:hover:before,
#colophon .widget-container li:hover,
#colophon .widget-container li:hover a,
.compare_item_head .property_price,
.adv_extended_options_text:hover,
#adv_extended_options_show_filters,
.show_filters,
.adv_extended_options_text,
#showinpage,
#contactinfobox,
.company_headline a:hover i,
#primary .contact_sidebar_wrap p:hover a,
#colophon .contact_sidebar_wrap p:hover a,
.twitter_wrapper a,
.twitter_time,
.wpestate_recent_tweets .item:after,
.widget_nav_menu .sub-menu li:hover a,
.widget_nav_menu  .sub-menu li:hover,
.top_bar .social_sidebar_internal a:hover,
.agent_unit_social_single a:hover,
.price_area,
i.checkon,
.listing_main_image_price ,
.meta-info a:hover,
.blog_unit_back:hover .blog-title-link,
#colophon .category_name a:hover,
.icon-fav,
.share_unit a:hover,
.share_list,
.listing_unit_price_wrapper,
.property_listing:hover .listing_title_unit,
.icon_selected,
#grid_view:hover,
#list_view:hover,
#user_menu_open  > a:hover i, 
#user_menu_open  > a:focus i,
.menu_user_tools,
.user_menu,
.breadcrumb a:hover,
.breadcrumb .active,
.slider-content .read_more,
.slider-title h2 a:hover,
#access .current-menu-item,
#access .current-menu-item >a,
#access .current-menu-parent>a,
#access .current-menu-ancestor>a,
 a,a:hover, a:focus{
    color: #' . $main_color . ';
}

.property_flex:hover .blog_unit_back,
.property_flex:hover .property_listing,
.listing_type_1 .check_avalability,
.check_avalability,
.menu_user_picture,      
.theme-slider-view,
.scrollon,
#submit_action{
    border-color: #'. $main_color .' ;
}

.share_unit:after{
    border-top: 8px solid  #'. $main_color .';
}
.agentpict{
    border-bottom: 3px solid #'. $main_color .';
}

#adv_extended_options_show_filters,
.show_filters{
    border: 2px solid #'. $main_color .';
}

.user_dashboard_links a:hover i,
.user_dashboard_links a:hover,
.edit_class, .user_dashboard_links .user_tab_active{
    border-left-color: #'. $main_color .';
}

blockquote{
    border-left:5px solid #'. $main_color .';
}

.wpestate_tabs .ui-widget-header {
   border-bottom: 2px solid #'. $main_color .';
}

.booking-calendar-wrapper-in .end_reservation,
.all-front-calendars .end_reservation,
.ll-skin-melon .ui-datepicker td.freetobook.end_reservation{
   
    background: #fff9f9; 
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjYjg4MWZjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNDklIiBzdG9wLWNvbG9yPSIjYjg4MWZjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iI2ZmZjlmOSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgPC9saW5lYXJHcmFkaWVudD4KICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2dyYWQtdWNnZy1nZW5lcmF0ZWQpIiAvPgo8L3N2Zz4=);
    background: -moz-linear-gradient(-45deg,  #'. $main_color .' 0%, #'. $main_color .' 49%, #ffffff 50%, #ffffff 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#'. $main_color .'), color-stop(49%,#'. $main_color .'), color-stop(50%,#ffffff), color-stop(100%,#ffffff)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#ffffff 50%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#ffffff 50%,#ffffff 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#ffffff 50%,#ffffff 100%); /* IE10+ */
    background: linear-gradient(135deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#ffffff 50%,#ffffff 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=#'. $main_color .', endColorstr=#ffffff,GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
}
.ll-skin-melon .ui-datepicker .ui-state-disabled.end_reservation{
    background: #fff9f9; 
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjYjg4MWZjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNDklIiBzdG9wLWNvbG9yPSIjYjg4MWZjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iI2ZmZjlmOSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgPC9saW5lYXJHcmFkaWVudD4KICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2dyYWQtdWNnZy1nZW5lcmF0ZWQpIiAvPgo8L3N2Zz4=);
    background: -moz-linear-gradient(-45deg,  #'. $main_color .' 0%, #'. $main_color .' 49%, #F8F8F8 50%, #F8F8F8 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#'. $main_color .'), color-stop(49%,#'. $main_color .'), color-stop(50%,#F8F8F8), color-stop(100%,#F8F8F8)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#F8F8F8 50%,#F8F8F8 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#F8F8F8 50%,#F8F8F8 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#F8F8F8 50%,#F8F8F8 100%); /* IE10+ */
    background: linear-gradient(135deg,  #'. $main_color .' 0%,#'. $main_color .' 49%,#F8F8F8 50%,#F8F8F8 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=#'. $main_color .', endColorstr=#ffffff,GradientType=1 ); /* IE6-8 fallback on horizontal gradient */

}



.booking-calendar-wrapper-in .calendar-reserved.start_reservation ,
.all-front-calendars  .calendar-reserved.start_reservation ,
.ll-skin-melon .ui-datepicker td.calendar-reserved.start_reservation{   
    /*  background: -webkit-gradient(linear, right bottom, left top, color-stop(50%,#'. $main_color .'), color-stop(50%,#fff))!important;    */
   background: #fff9f9; /* Old browsers */
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjZmZmOWY5IiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTElIiBzdG9wLWNvbG9yPSIjYjg4MWZjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iI2I4ODFmYyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgPC9saW5lYXJHcmFkaWVudD4KICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2dyYWQtdWNnZy1nZW5lcmF0ZWQpIiAvPgo8L3N2Zz4=);
    background: -moz-linear-gradient(-45deg,  #ffffff 0%, #ffffff 50%, #'. $main_color .' 51%, #'. $main_color .' 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#ffffff), color-stop(50%,#ffffff), color-stop(51%,#'. $main_color .'), color-stop(100%,#'. $main_color .')); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(-45deg,  #ffffff 0%,#ffffff 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(-45deg,  #ffffff 0%,#ffffff 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(-45deg,  #ffffff 0%,#ffffff 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* IE10+ */
    background: linear-gradient(135deg,  #ffffff 0%,#ffffff 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=#ffffff, endColorstr=#'. $main_color .',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
}

.ll-skin-melon .ui-datepicker .ui-state-disabled.start_reservation{
    /*  background: -webkit-gradient(linear, right bottom, left top, color-stop(50%,#'. $main_color .'), color-stop(50%,#fff))!important;    */
   background: #fff9f9; /* Old browsers */
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjZmZmOWY5IiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTElIiBzdG9wLWNvbG9yPSIjYjg4MWZjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iI2I4ODFmYyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgPC9saW5lYXJHcmFkaWVudD4KICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2dyYWQtdWNnZy1nZW5lcmF0ZWQpIiAvPgo8L3N2Zz4=);
    background: -moz-linear-gradient(-45deg,  #'. $main_color .' 0%, #'. $main_color .' 50%, #'. $main_color .' 51%, #'. $main_color .' 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#'. $main_color .'), color-stop(50%,#'. $main_color .'), color-stop(51%,#'. $main_color .'), color-stop(100%,#'. $main_color .')); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(-45deg,  #'. $main_color .' 0%,#'. $main_color .' 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* IE10+ */
    background: linear-gradient(135deg,  #'. $main_color .' 0%,#'. $main_color .' 50%,#'. $main_color .' 51%,#'. $main_color .' 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=#ffffff, endColorstr=#'. $main_color .',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */

}
';   
    

} 

    
if ($background_color != '') {
print'.wide,#google_map_prop_list_sidebar,.content_wrapper,.main_wrapper {background-color: #' . $background_color . ';} ';        
} // end $background_color

/*
if ($content_back_color != '') {
print '{ background-color: #' . $content_back_color . ';} ';
}// end content_back_color
*/

if ($header_color != '') {
print' .master_header,.customnav
      {background-color: #' . $header_color . ' }
    
  
    
   
    '; 
} // end $header_color


if ($breadcrumbs_font_color != '') {
print '
.review-date,
.category_icon_wrapper a,
.category_text,
.breadcrumb a,
.top_bar,
.top_bar a,
.listing-details,
.property_location .inforoom,
.property_location .infoguest,
.property_location .infosize,
.meta-element-head,
.meta-info,
.meta-info a,
.navigational_links a,
.agent_meta,
.agent_meta a,
.agent_pos,
.comment_date,
#adv_extended_close_adv,
#adv_extended_close_mobile,
#inforoom,
#infoguest,
#infosize,
.featured_article_secondline,
.featured_article_right{
    color: #' . $breadcrumbs_font_color . ';
}

#street-view{
    background-color: #' . $breadcrumbs_font_color . ';
}

';
} // end $breadcrumbs_font_color 


if ($font_color != '') {
print' 
    body,
    a,
    label,
    input[type=text], 
    input[type=password], 
    input[type=email], 
    input[type=url], 
    input[type=number], 
    textarea, 
    .slider-content, 
    .listing-details, 
    .form-control, 
    #user_menu_open i,
    #grid_view, 
    #list_view, 
    .listing_details a, 
    .notice_area, 
    .social-agent-page a, 
    .prop_detailsx, 
    #reg_passmail_topbar,
    #reg_passmail, 
    .testimonial-text,
    .wpestate_tabs .ui-widget-content, 
    .wpestate_tour  .ui-widget-content, 
    .wpestate_accordion_tab .ui-widget-content, 
    .wpestate_accordion_tab .ui-state-default, 
    .wpestate_accordion_tab .ui-widget-content .ui-state-default, 
    .wpestate_accordion_tab .ui-widget-header .ui-state-default,
    .filter_menu,
    blockquote p , 
    .panel-body p, 
    .owner_details_content p, 
    .property_menu_item_title,
    .property_menu_item,
    .item_head,
    .listing_detail,
    .blog-unit-content,
    .property_menu_item a,
    .social_icons_owner i,
    .social_icons_owner i:hover{
        color: #'.$font_color.';}
    
    .form-control::-webkit-input-placeholder{
        color: #'.$font_color.';}';

print '.caret,  .caret_sidebar, .advanced_search_shortcode .caret_filter{ border-bottom: 6px solid #' . $font_color . ';}';

} // end $font_color #a0a5a8

if ($link_color != '') {
    
print '
a,
#user_menu_open a,
.category_tagline a,
.property_listing a,
#user_terms_register_wd_label a, 
#user_terms_register_wd_label, 
#user_terms_register_topbar_link,
.single-content p a,
.owner_read_more {
    color: #'.$link_color.';
}
.more_list{
 color: #'.$link_color.'!important;
}
';
    
} // end $link_color

if ($headings_color != '') {
print 'h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a , 
 .featured_property h2 a, 
 .featured_property h2,
 .blog_unit h3, 
 .blog_unit h3 a,
 .submit_container_header,
 .panel-title,
 #other_listings,
 .entry-title-agent,
 .blog-title-link,
 .agent-title-link,
 .listing_title a,
 .listing_title_book a,
 #primary .listing_title_unit,

 #listing_reviews, .agent_listings_title_similar, #listing_calendar, #listing_description .panel-title-description{
    color: #'.$headings_color.';
  }
  
 .listing_title_unit{
    color: #'.$headings_color.'!important;
 }
    ';
} // end $headings_color 

if ($footer_back_color != '') {
print '#colophon {background-color: #'.$footer_back_color.';}';
} // end 


if ($footer_font_color != '') {
print '#colophon, #colophon a, #colophon li a,.widget-title-footer {color: #'.$footer_font_color.';}';
} 

if ($footer_copy_color != '') {
print '.sub_footer, .subfooter_menu a, .subfooter_menu li a {color: #'.$footer_copy_color.'!important;}';
} 


if ($sidebar_widget_color != '') {
print '.twitter_wrapper,.booking_form_request, .loginwd_sidebar .widget-title-sidebar, .advanced_search_sidebar .widget-title-sidebar,.advanced_search_sidebar,.loginwd_sidebar {background-color: #'.$sidebar_widget_color.';}';
} 

if($sidebar_heading_color!=''){
    print '.widget-title-sidebar,.agent_listings_title_similar{color: #'.$sidebar_heading_color.';}';
}

if($sidebar_heading_boxed_color!=''){
    print '.wpestate_recent_tweets h3,.loginwd_sidebar .widget-title-sidebar, .advanced_search_sidebar .widget-title-sidebar{color: #'.$sidebar_heading_boxed_color.';}';
}

if ($sidebar2_font_color != '') {
print '#primary,#primary a,#primary label {color: #'.$sidebar2_font_color.';}'; 
} 

if ($menu_font_color != '') {
    print '.signuplink,#submit_action,#access a,#access ul ul a{color:#'.$menu_font_color.';}';  
} 

if ($menu_hover_back_color != '') {
    print '
    #access ul ul{
        background-color: #'.$menu_hover_back_color.';
    }
    #access ul ul:after{
        border-bottom: 13px solid #'.$menu_hover_back_color.';
    }
   
    ';
} // end $menu_hover_back_color


if ($menu_hover_font_color != '') {
    print '.filter_menu li:hover,#access .sub-menu li:hover>a, #access .sub-menu li:hover>a:active, #access .sub-menu li:hover>a:focus {color: #'.$menu_hover_font_color.';}';
} // end $menu_hover_font_color

if($top_bar_back!=''){
    print '.top_bar_wrapper{background-color:#'.$top_bar_back.';}';
}    

if($top_bar_font!=''){
    print '.top_bar,.top_bar a{color:#'.$top_bar_font.';}';
}

if ($box_content_back_color != '') {
    print '.featured_article_title,
    .testimonial-text,
    .adv1-holder,   
    .advanced_search_shortcode, 
    .featured_secondline ,  
    .property_listing ,
    .agent_unit, 
    .blog_unit_back,
    .dasboard-prop-listing,
    .message_header,
    .invoice_unit{ 
        background-color:#'.$box_content_back_color.';}
            

    .testimonial-text:after{
        border-top-color: #'.$box_content_back_color.';
    }'
       ;
    
    
    
} 

if ($box_content_border_color != '') {
    print '
    .featured_article, .loginwd_sidebar, .advanced_search_sidebar, .advanced_search_shortcode,  #access ul ul, .testimonial-text, .submit_container,   
    .featured_property, .property_listing ,.agent_unit,.blog_unit_back ,property_listing,.booking_form_request{
        border-color:#'.$box_content_border_color.';
    } 
 
    
    .adv1-holder,.notice_area,  .listing_filters    {
        border-bottom: 1px solid #'.$box_content_border_color.';
    }
    
   
    .testimonial-text:before{
        border-top-color: #'.$box_content_border_color.';
    }
    '; 
} 

if($hover_button_color!=''){
    print '.social_icons_owner i,.owner-image-container,.owner_listing_image{
    border-color:#'.$hover_button_color.';}';
    
    
    print '#submit_action:hover,
    .theme-slider-view:hover,
    .comment-form #submit:hover,
    .vc_button.wpb_btn-info:active, 
    .vc_button.wpb_btn-info.active, 
    .vc_button.wpb_btn-info.disabled, 
    .vc_button.wpb_btn-info[disabled]{
        background-color:#'.$hover_button_color.'!important;
        border:1px solid #'.$hover_button_color.';
    }
   
    #wp-submit-register:hover, 
    #wp-forgot-but:hover, 
    #wp-login-but:hover, 
    .comment-form #submit:hover, 
    #wp-forgot-but_shortcode:hover, 
    #wp-login-but-wd:hover, 
    #wp-submit-register_wd:hover,
    #advanced_submit_shorcode:hover,
    #submit_mess_front:hover,
    .modal-content #wp-forgot-but_mod:hover{
        background-color:#'.$hover_button_color.'!important;
    }';
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
// End colors

} //////////// end if color scheme
/////// Custom css
?>