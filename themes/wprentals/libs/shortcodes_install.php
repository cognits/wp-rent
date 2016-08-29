<?php

///////////////////////////////////////////////////////////////////////////////////////////
/////// register shortcodes
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_shortcodes(){
    wpestate_register_shortcodes();
    wpestate_tiny_short_codes_register();
    add_filter('widget_text', 'do_shortcode');
}

///////////////////////////////////////////////////////////////////////////////////////////
// register tiny plugins functions
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_tiny_short_codes_register() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'wpestate_add_plugin');
        add_filter('mce_buttons_3', 'wpestate_register_button');    
    }

}

/////////////////////////////////////////////////////////////////////////////////////////
/////// push the code into Tiny buttons array
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_register_button($buttons) {
    array_push($buttons, "|", "slider_recent_items");  
     
    array_push($buttons, "|", "testimonials");
    array_push($buttons, "|", "recent_items");  
    array_push($buttons, "|", "featured_agent"); 
    array_push($buttons, "|", "featured_article");
    array_push($buttons, "|", "featured_property");
    array_push($buttons, "|", "list_items_by_id"); 
    array_push($buttons, "|", "login_form"); 
    array_push($buttons, "|", "register_form");
    array_push($buttons, "|", "advanced_search");
    array_push($buttons, "|", "font_awesome");
    array_push($buttons, "|", "spacer"); 
    array_push($buttons, "|", "icon_container");
    array_push($buttons, "|", "places_list");
    array_push($buttons, "|", "featured_place");
    
    return $buttons;
}



///////////////////////////////////////////////////////////////////////////////////////////
/////// poins to the right js 
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_add_plugin($plugin_array) {   
    $plugin_array['slider_recent_items']        = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['testimonials']               = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['recent_items']               = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_agent']             = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_article']           = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_property']          = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['login_form']                 = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['register_form']              = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['list_items_by_id']           = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['advanced_search']            = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['font_awesome']               = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['spacer']                     = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['icon_container']             = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['places_list']                = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_place']             = get_template_directory_uri() . '/js/shortcodes.js';
    
    return $plugin_array;
}

///////////////////////////////////////////////////////////////////////////////////////////
/////// register shortcodes
///////////////////////////////////////////////////////////////////////////////////////////


function wpestate_register_shortcodes() {
    add_shortcode('slider_recent_items', 'wpestate_slider_recent_posts_pictures');
        
    add_shortcode('spacer', 'wpestate_spacer_shortcode_function');
    add_shortcode('recent-posts', 'wpestate_recent_posts_function');
    add_shortcode('testimonial', 'wpestate_testimonial_function');
    add_shortcode('recent_items', 'wpestate_recent_posts_pictures');
    add_shortcode('featured_agent', 'wpestate_featured_agent');
    add_shortcode('featured_article', 'wpestate_featured_article');
    add_shortcode('featured_property', 'wpestate_featured_property');
    add_shortcode('login_form', 'wpestate_login_form_function');
    add_shortcode('register_form', 'wpestate_register_form_function');
    add_shortcode('list_items_by_id', 'wpestate_list_items_by_id_function');
    add_shortcode('advanced_search', 'wpestate_advanced_search_function');
    add_shortcode('font_awesome', 'wpestate_font_awesome_function');
    add_shortcode('icon_container', 'wpestate_icon_container_function');
    add_shortcode('places_list', 'wpestate_places_list_function');
    add_shortcode('featured_place', 'wpestate_featured_place');
}



////////////////////////////////////////////////////////////////////////////////
// add shortcodes to visual composer
////////////////////////////////////////////////////////////////////////////////

if( function_exists('vc_map') ):
     vc_map(
    array(
       "name" => esc_html__( "Featured Place","wpestate"),
       "base" => "featured_place",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Place Shortcode','wpestate'),
       "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Place Id","wpestate"),
                "param_name" => "id",
                "value" => "0",
                "description" => esc_html__( "Place Id (city or neighborhood) ","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type","wpestate"),
                "param_name" => "type",
                "value" => "type1",
                "description" => esc_html__( "Design Type (type1 or type2) ","wpestate")
            )
           
       )
    )
    );
    
    vc_map( array(
        "name" => esc_html__( "List Cities or Areas","wpestate"),//done
        "base" => "places_list",
        "class" => "",
        "category" => esc_html__( 'Content','wpestate'),
        'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
        'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
        'weight'=>100,
        'icon'   =>'wpestate_vc_logo',
        'description'=>esc_html__( 'List Cities or Areas','wpestate'),  
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Cities or Areas IDs, separated by comma","wpestate"),
                "param_name" => "place_list",
                "value" => "",
                "description" => esc_html__( "Cities or Areas IDs, separated by comma","wpestate")
            )  ,
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Places per row","wpestate"),
                "param_name" => "place_per_row",
                "value" => "4",
                "description" => esc_html__( "How many items listed per row?","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wpestate"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wpestate")
            )
        )    
    ) 
    );    

    
    
    
    vc_map(
    array(
       "name" => esc_html__( "Recent Items Slider","wpestate"),//done
       "base" => "slider_recent_items",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Recent Items Slider Shortcode','wpestate'),
       "params" => array(
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Title","wpestate"),
             "param_name" => "title",
             "value" => "",
             "description" => esc_html__( "Section Title","wpestate")
          ),
          
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Category Id's","wpestate"),
             "param_name" => "category_ids",
             "value" => "",
             "description" => esc_html__( "list of category id's sepearated by comma (*only for properties)","wpestate")
          ),
             array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Action Id's","wpestate"),
             "param_name" => "action_ids",
             "value" => "",
             "description" => esc_html__( "list of action ids separated by comma (*only for properties)","wpestate")
          ), 
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "City Id's ","wpestate"),
             "param_name" => "city_ids",
             "value" => "",
             "description" => esc_html__( "list of city ids separated by comma (*only for properties)","wpestate")
          ),
            array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Area Id's","wpestate"),
             "param_name" => "area_ids",
             "value" => "",
             "description" => esc_html__( "list of area ids separated by comma (*only for properties)","wpestate")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "No of items","wpestate"),
             "param_name" => "number",
             "value" => 4,
             "description" => esc_html__( "how many items","wpestate")
          ),array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Show featured listings only?","wpestate"),
             "param_name" => "show_featured_only",
             "value" => "no",
             "description" => esc_html__( "Show featured listings only? (yes/no)","wpestate")
          ), array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wpestate"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wpestate")
            ) ,array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Auto scroll period","wpestate"),
             "param_name" => "autoscroll",
             "value" => "0",
             "description" => esc_html__( "Auto scroll period in seconds - 0 for manual scroll, 1000 for 1 second, 2000 for 2 seconds and so on.","wpestate")
          ) 
        )
    )
    );








      vc_map( array(
       "name" => esc_html__( "Icon content box","wpestate"),//done
       "base" => "icon_container",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
        'icon'   =>'wpestate_vc_logo',
        'description'=>esc_html__( 'Icon Content Box Shortcode','wpestate'),  
       "params" => array(
          array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Box Title","wpestate"),
             "param_name" => "title",
             "value" => "Title",
             "description" => esc_html__( "Box Title goes here","wpestate")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Image url","wpestate"),
             "param_name" => "image",
             "value" => "",
             "description" => esc_html__( "Image or Icon url","wpestate")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Content of the box","wpestate"),
             "param_name" => "content_box",
             "value" => "Content of the box goes here",
             "description" => esc_html__( "Content of the box goes here","wpestate")
          )
          ,
          
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Link","wpestate"),
             "param_name" => "link",
             "value" => "",
             "description" => esc_html__( "The link with http:// in front","wpestate")
          ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Icon/Image Postion","wpestate"),
                "param_name" => "icon_type",
                "value" => "left",
                "description" => esc_html__( "left or central","wpestate")
             ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Title Font Size","wpestate"),
                "param_name" => "title_font_size",
                "value" => "24",
                "description" => esc_html__( "Title Font Size","wpestate")
            )
          
       )
    ) );    




      vc_map(
           array(
           "name" => esc_html__( "Spacer","wpestate"),
           "base" => "spacer",
           "class" => "",
           "category" => esc_html__( 'Content','wpestate'),
           'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
           'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
           'weight'=>102,
            'icon'   =>'wpestate_vc_logo',
            'description'=>esc_html__( 'Spacer Shortcode','wpestate'),
           "params" => array(
               array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Spacer Type","wpestate"),
                 "param_name" => "type",
                 "value" => "1",
                 "description" => esc_html__( "Space Type : 1 with no middle line, 2 with middle line","wpestate")
              )   ,
               array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Space height","wpestate"),
                 "param_name" => "height",
                 "value" => "40",
                 "description" => esc_html__( "Space height in px","wpestate")
              )   
           )
        )   
    );



    vc_map( array(
       "name" => esc_html__( "List items by ID","wpestate"),//done
       "base" => "list_items_by_id",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
        'icon'   =>'wpestate_vc_logo',
        'description'=>esc_html__( 'List Items by ID Shortcode','wpestate'),
       "params" => array(
            array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Title","wpestate"),
             "param_name" => "title",
             "value" => "",
             "description" => esc_html__( "Section Title","wpestate")
          ),
          array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "What type of items","wpestate"),
             "param_name" => "type",
             "value" => "properties",
             "description" => esc_html__( "List properties or articles","wpestate")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Items IDs","wpestate"),
             "param_name" => "ids",
             "value" => "",
             "description" => esc_html__( "List of IDs separated by comma","wpestate")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "No of items","wpestate"),
             "param_name" => "number",
             "value" => "3",
             "description" => esc_html__( "How many items do you want to show ?","wpestate")
          ) ,
            
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "No of items per row","wpestate"),
             "param_name" => "rownumber",
             "value" => 4,
             "description" => esc_html__( "The number of items per row","wpestate")
          ) , 
         
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Link to global listing","wpestate"),
             "param_name" => "link",
             "value" => "#",
             "description" => esc_html__( "link to global listing with http","wpestate")
          ) ,array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wpestate"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wpestate")
            )
       )
    ) );    

   

    vc_map(
           array(
           "name" => esc_html__( "Testimonial",'wpestate'),
           "base" => "testimonial",
           "class" => "",
           "category" => esc_html__( 'Content','wpestate'),
           'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
           'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
           'weight'=>102,
           'icon'   =>'wpestate_vc_logo',
           'description'=>esc_html__( 'Testiomonial Shortcode','wpestate'),
           "params" => array(
              array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Client Name","wpestate"),
                 "param_name" => "client_name",
                 "value" => "Name Here",
                 "description" => esc_html__( "Client name here","wpestate")
              ),
               array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Title Client","wpestate"),
                 "param_name" => "title_client",
                 "value" => "happy client",
                 "description" => esc_html__( "title or client postion ","wpestate")
              ),
               array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Image","wpestate"),
                 "param_name" => "imagelinks",
                 "value" => "",
                 "description" => esc_html__( "Path to client picture, (best size 120px  x 120px) ","wpestate")
              ) ,
               array(
                 "type" => "textarea",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Testimonial Text Here.","wpestate"),
                 "param_name" => "testimonial_text",
                 "value" => "",
                 "description" => esc_html__( "Testimonial Text Here. ","wpestate")
              ),
                array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wpestate"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wpestate")
                )
           )
        )   
    );
    
    vc_map(
    array(
       "name" => esc_html__( "Recent Items","wpestate"),//done
       "base" => "recent_items",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Recent Items Shortcode','wpestate'),
       "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Use without spaces between listings(yes/no)? (If yes, title or link to global listing will not show)","wpestate"),
                "param_name" => "full_row",
                "value" => "yes",
                "description" => esc_html__( "Use without spaces between listings? (If yes, title or link to global listing will not show)","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Title","wpestate"),
                "param_name" => "title",
                "value" => "",
                "description" => esc_html__( "Section Title","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "What type of items","wpestate"),
                "param_name" => "type",
                "value" => "properties",
                "description" => esc_html__( "list properties or articles","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Category Id's","wpestate"),
                "param_name" => "category_ids",
                "value" => "",
                "description" => esc_html__( "list of category ids separated by comma","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Action Id's","wpestate"),
                "param_name" => "action_ids",
                "value" => "",
                "description" => esc_html__( "list of action ids separated by comma (*only for properties)","wpestate")
            ), 
            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "City Id's ","wpestate"),
              "param_name" => "city_ids",
              "value" => "",
              "description" => esc_html__( "list of city ids separated by comma (*only for properties)","wpestate")
            ),
            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "Area Id's","wpestate"),
              "param_name" => "area_ids",
              "value" => "",
              "description" => esc_html__( "list of area ids separated by comma (*only for properties)","wpestate")
            ),
            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "No of items","wpestate"),
              "param_name" => "number",
              "value" => 4,
              "description" => esc_html__( "how many items","wpestate")
            ) , 
            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "No of items per row","wpestate"),
              "param_name" => "rownumber",
              "value" => 4,
              "description" => esc_html__( "The number of items per row","wpestate")
            ) , 

            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "Link to global listing","wpestate"),
              "param_name" => "link",
              "value" => "",
              "description" => esc_html__( "link to global listing","wpestate")
            ),array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "Show featured listings only?","wpestate"),
              "param_name" => "show_featured_only",
              "value" => "no",
              "description" => esc_html__( "Show featured listings only? (yes/no)","wpestate")
            ) ,
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wpestate"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wpestate")
            ),array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Random Pick (yes/no) ","wpestate"),
             "param_name" => "random_pick",
             "value" => "no",
             "description" => esc_html__( "Choose if properties should display randomly on page refresh. (*only for properties)","wpestate")
          ) 
        )
    )
    );

  
    
    vc_map(
    array(
       "name" => esc_html__( "Featured Owner","wpestate"),
       "base" => "featured_agent",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Owner Shortcode','wpestate'),
       "params" => array(
          array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Owner Id","wpestate"),
             "param_name" => "id",
             "value" => "0",
             "description" => esc_html__( "Owner Id","wpestate")
          ),
           array(
             "type" => "textarea",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Notes","wpestate"),
             "param_name" => "notes",
             "value" => "",
             "description" => esc_html__( "Notes for featured owner","wpestate")
          )
       )
    )
    );
    
    vc_map(
       array(
       "name" => esc_html__( "Featured Article","wpestate"),
       "base" => "featured_article",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Article Shortcode','wpestate'),
       "params" => array(
            array(
               "type" => "textfield",
               "holder" => "div",
               "class" => "",
               "heading" => esc_html__( "Id of the article","wpestate"),
               "param_name" => "id",
               "value" => "",
               "description" => esc_html__( "The id of the article","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type","wpestate"),
                "param_name" => "type",
                "value" => "type1",
                "description" => esc_html__( "Design Type (type1 or type2) ","wpestate")
            )
        )
    )
    );
    
    vc_map(
    array(
       "name" => esc_html__( "Featured Property","wpestate"),
       "base" => "featured_property",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Property Shortcode','wpestate'),
       "params" => array(
            array(
               "type" => "textfield",
               "holder" => "div",
               "class" => "",
               "heading" => esc_html__( "Property id","wpestate"),
               "param_name" => "id",
               "value" => "",
               "description" => esc_html__( "Property id","wpestate")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type","wpestate"),
                "param_name" => "type",
                "value" => "type1",
                "description" => esc_html__( "Design Type (type1 or type2) ","wpestate")
            )
        )
    )
    );

    
    vc_map(array(
       "name" => esc_html__( "Login Form","wpestate"),
       "base" => "login_form",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Login Form Shortcode','wpestate'),  
       "params" => array( array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Register link text","wpestate"),
             "param_name" => "register_label",
             "value" => "",
             "description" => esc_html__( "Register link text","wpestate")
            )     , 
            array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Register page url","wpestate"),
             "param_name" => "register_url",
             "value" => "",
             "description" => esc_html__( "Register page url","wpestate")
          )      )
    )
    );
    
    
    vc_map(
     array(
       "name" => esc_html__( "Register Form","wpestate"),
       "base" => "register_form",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Register Form Shortcode','wpestate'),    
       "params" => array()
    )
            
    );
    
    
    
    
    vc_map(
        array(
       "name" => esc_html__( "Advanced Search","wpestate"),
       "base" => "advanced_search",
       "class" => "",
       "category" => esc_html__( 'Content','wpestate'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Advanced Search Shortcode','wpestate'),     
       "params" => array(
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Title","wpestate"),
             "param_name" => "title",
             "value" => "",
             "description" => esc_html__( "Section Title","wpestate")
          ))
    )
           
            
    );
    
    
endif;



function custom_css_wpestate($class_string, $tag) {
    if ($tag =='vc_row' ) {
        $class_string .= ' wpestate_row'; 
    }
    
    if ($tag =='vc_row_inner' ) {
        $class_string .= ' wpestate_row_inner'; 
    }
    
    
    if ($tag =='vc_tabs' ) {
      $class_string .= ' wpestate_tabs'; 
    }

    if ($tag =='vc_tour' ) {
      $class_string .= ' wpestate_tour'; 
    }

    if ($tag =='vc_accordion' ) {
      $class_string .= ' wpestate_accordion'; 
    }

    if ($tag =='vc_accordion_tab' ) {
      $class_string .= ' wpestate_accordion_tab'; 
    }

    if ($tag =='vc_carousel' ) {
      $class_string .= ' wpestate_carousel'; 
    }

    if ($tag =='vc_progress_bar' ) {
      $class_string .= ' wpestate_progress_bar'; 
    }

    if ($tag =='vc_toggle' ) {
      $class_string .= ' wpestate_toggle'; 
    }

    if ($tag =='vc_message' ) {
      $class_string .= ' wpestate_message'; 
    }

    if ($tag =='vc_posts_grid' ) {
      $class_string .= ' wpestate_posts_grid'; 
    }

    if ($tag =='vc_cta_button' ) {
      $class_string .= ' wpestate_cta_button '; 
    }

    if ($tag =='vc_cta_button2' ) {
      $class_string .= ' wpestate_cta_button2 '; 
    }

    if ($tag =='vc_button' ) {
      $class_string .= ' wpestate_vc_button '; 
    }
  
  return $class_string.' '.$tag;
}


// Filter to Replace default css class for vc_row shortcode and vc_column
add_filter('vc_shortcodes_css_class', 'custom_css_wpestate', 10,2)

?>
