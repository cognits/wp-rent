/*global google, Modernizr, InfoBox, googlecode_regular_vars, document, window, setOms, OverlappingMarkerSpiderfier, mapfunctions_vars, jQuery, googlecode_regular_vars2, map_cluster, setMarkers*/
var gmarkers = [];
var current_place = 0;
var actions = [];
var categories = [];
var vertical_pan = -190;
var map_open = 0;
var vertical_off = 150;
var pins = '';
var markers = '';
var infoBox = null;
var category = null;
var width_browser = null;
var infobox_width = null;
var wraper_height = null;
var info_image = null;
var map;
var found_id;
var selected_id = '';
var javamap;
var oms;
var bounds_list;
var external_action_ondemand=0;
var is_fit_bounds_zoom=0
var map_geo_first_load=0;
var mcluster;
var is_drag_end=0;
var is_zoom_end=0;
var map_is_moved=0;

function initialize() {
    "use strict";
    var mapOptions, styles;
    mapOptions = {
        flat: false,
        noClear: false,
        zoom: parseInt(googlecode_regular_vars.page_custom_zoom, 10),
        scrollwheel: false,
        draggable: true,
        center: new google.maps.LatLng(googlecode_regular_vars.general_latitude, googlecode_regular_vars.general_longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        },
        disableDefaultUI: true
    };


    if (document.getElementById('googleMap')) {
        map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
        bounds_list = new google.maps.LatLngBounds();
    } else if (document.getElementById('google_map_prop_list')) {
        map = new google.maps.Map(document.getElementById('google_map_prop_list'), mapOptions);
        bounds_list = new google.maps.LatLngBounds();
        
    } else {
        return;
    }

    google.maps.visualRefresh = true;

    if (mapfunctions_vars.map_style !== '') {
        styles = JSON.parse(mapfunctions_vars.map_style);
        map.setOptions({styles: styles});
    }


    google.maps.event.addListener(map, 'tilesloaded', function () {
        jQuery('#gmap-loading').remove();
    });

    if (Modernizr.mq('only all and (max-width: 1025px)')) {
        map.setOptions({'draggable': false});
    }


    if (googlecode_regular_vars.generated_pins === '0') {
        pins = googlecode_regular_vars.markers;
        markers = jQuery.parseJSON(pins);
    } else {
        if (typeof (googlecode_regular_vars2.markers2) !== 'undefined' && googlecode_regular_vars2.markers2.length > 2) {
            pins = googlecode_regular_vars2.markers2;
         
            markers = jQuery.parseJSON(pins);
          
        }
    }

    if (markers.length > 0) {
        setMarkers(map, markers);
    }

    //set map cluster
    map_cluster();
    function scrollwhel(event) {
        if (map.scrollwheel === true) {
            event.stopPropagation();
        }
    }

    if (document.getElementById('googleMap')) {
        google.maps.event.addDomListener(document.getElementById('googleMap'), 'mousewheel', scrollwhel);
        google.maps.event.addDomListener(document.getElementById('googleMap'), 'DOMMouseScroll', scrollwhel);
    }

    if (document.getElementById('google_map_prop_list')) {
      
        if (!bounds_list.isEmpty()) {
            wpestate_fit_bounds(bounds_list);
        }else{
            wpestate_fit_bounds_nolsit();
        }

        google.maps.event.addDomListener(document.getElementById('google_map_prop_list'), 'mousewheel', scrollwhel);
        google.maps.event.addDomListener(document.getElementById('google_map_prop_list'), 'DOMMouseScroll', scrollwhel);
    }else if (document.getElementById('googleMap')) {
      
        if (!bounds_list.isEmpty()) {
            wpestate_fit_bounds(bounds_list);
        }else{
            wpestate_fit_bounds_nolsit();
        }

    }
    oms = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true, legWeight: 3});
    setOms(gmarkers);


   

    if (googlecode_regular_vars.on_demand_pins==='yes' && mapfunctions_vars.is_tax!=1 && mapfunctions_vars.is_property_list==='1'){
        
        map.addListener('idle', function() {
            wpestate_ondenamd_map_moved();
        });  
    }

}

function wpestate_ondenamd_map_moved(){
    //console.log("map_geo_first_load "+ map_geo_first_load+"is_fit_bounds_zoom "+is_fit_bounds_zoom+" external_action_ondemand+"  +external_action_ondemand);
    if(  map_geo_first_load===1 && is_fit_bounds_zoom===0 && external_action_ondemand==0){
        map_is_moved=1;
        wpestate_reload_pins_onmap(1);
    }else{
     
        console.log(gmarkers);
       
    }
    map_geo_first_load=1;
    external_action_ondemand=0;     
    return;
}

///////////////////////////////// end initialize
/////////////////////////////////////////////////////////////////////////////////// 


if (typeof google === 'object' && typeof google.maps === 'object') {
    google.maps.event.addDomListener(window, 'load', initialize);
}

function wpestate_get_coordinates(container,newpage,NE,SW){
    var return_array=[];
    var container_id = "#"+container;
    
    if( newpage == 1 || newpage == '1' ){
       
    
        if(page_tracker===0){
         
            return_array['ne_lat']  =   NE.lat();
            return_array['ne_long'] =   NE.lng();
            return_array['sw_lat'] =   SW.lat();
            return_array['sv_long'] =   SW.lng()
            jQuery(container_id).attr('data-ne_lat',return_array['ne_lat']);
            jQuery(container_id).attr('data-ne_long',return_array['ne_long']);
            jQuery(container_id).attr('data-sw_lat',  return_array['sw_lat'] );
            jQuery(container_id).attr('data-sv_long', return_array['sv_long']);
        }else{
         
            return_array['ne_lat']  =   jQuery(container_id).attr('data-ne_lat');
            return_array['ne_long'] =   jQuery(container_id).attr('data-ne_long');
            return_array['sw_lat']  =   jQuery(container_id).attr('data-sw_lat' );
            return_array['sv_long'] =   jQuery(container_id).attr('data-sv_long');
            
            
            page_tracker=0;
            
            
            
            
        }
    }else{
       
        return_array['ne_lat']  =   jQuery(container_id).attr('data-ne_lat');
        return_array['ne_long'] =   jQuery(container_id).attr('data-ne_long');
        return_array['sw_lat']  =   jQuery(container_id).attr('data-sw_lat' );
        return_array['sv_long'] =   jQuery(container_id).attr('data-sv_long');
        page_tracker=1;
     

        if(    return_array['ne_lat']  === undefined ||    return_array['ne_lat']  === null){
            return_array['ne_lat']  =   NE.lat();
            return_array['ne_long'] =   NE.lng();
            return_array['sw_lat'] =   SW.lat();
            return_array['sv_long'] =   SW.lng();
          
        }

            
        jQuery(container_id).attr('data_page_tracker',page_tracker);
    }
    
    return return_array;

}

function wpestate_reload_pins_onmap(newpage){
 
    var curentbounds = map.getBounds();
  
    var NE = curentbounds.getNorthEast();
    var SW = curentbounds.getSouthWest();

    //console.log ( NE.lat()+" / "+NE.lng()+" / "+SW.lat()+" / "+SW.lng() ); 

    if (document.getElementById('google_map_prop_list')) {
        var coordinates_array=wpestate_get_coordinates('google_map_prop_list',newpage,NE,SW);
        start_filtering_ajax_map_with_map_geo(newpage, coordinates_array['ne_lat'], coordinates_array['ne_long'],  coordinates_array['sw_lat'],coordinates_array['sv_long'] );
    }else if(document.getElementById('googleMap')){
        var coordinates_array=wpestate_get_coordinates('googleMap',newpage,NE,SW);
        start_filtering_ajax_map_with_map_geo(newpage, coordinates_array['ne_lat'], coordinates_array['ne_long'],  coordinates_array['sw_lat'],coordinates_array['sv_long'] );
    }
}