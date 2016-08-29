/*global google,  Modernizr, InfoBox, window, googlecode_property_vars, document, jQuery, control_vars, setOms, map_cluster, oms, OverlappingMarkerSpiderfier, setMarkers, googlecode_regular_vars2, setMarkers_contact, mapfunctions_vars, close_adv_search, show_advanced_search*/
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
var selected_id = jQuery('#gmap_wrapper').attr('data-post_id');
var curent_gview_lat = jQuery('#gmap_wrapper').attr('data-cur_lat');
var curent_gview_long = jQuery('#gmap_wrapper').attr('data-cur_long');
var heading = 0;
var panorama;
var oms;
var map_intern = 0;
var external_action_ondemand=0;
var is_fit_bounds_zoom=0
var map_geo_first_load=0;
var mcluster;
var is_drag_end=0;
var is_zoom_end=0;

function initialize() {
    "use strict";
    var viewPlace, mapOptions, mapOptions_intern, styles;
    if (curent_gview_lat === '') {
        curent_gview_lat = googlecode_property_vars.general_latitude;
    }

    if (curent_gview_long === '') {
        curent_gview_long = googlecode_property_vars.general_longitude;
    }
    viewPlace = new google.maps.LatLng(curent_gview_lat, curent_gview_long);


    mapOptions = {
        flat: false,
        noClear: false,
        zoom: parseInt(googlecode_property_vars.page_custom_zoom, 10),
        scrollwheel: false,
        draggable: true,
        center: new google.maps.LatLng(curent_gview_lat, curent_gview_long),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        },
        disableDefaultUI: true
    };

    mapOptions_intern = {
        flat: false,
        noClear: false,
        zoom: parseInt(googlecode_property_vars.page_custom_zoom, 10),
        scrollwheel: false,
        draggable: true,
        center: new google.maps.LatLng(googlecode_property_vars.general_latitude, googlecode_property_vars.general_longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        },
        disableDefaultUI: true
    };


    if (document.getElementById('googleMap')) {
        map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
    }

    if (document.getElementById('google_map_on_list')) {
        map = new google.maps.Map(document.getElementById('google_map_on_list'), mapOptions_intern);
        map_intern = 1;
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
        //map.setOptions({'draggable': false});
    }

    if (map_intern === 0) {
        ///////////////////////////////////////////////////////////////// header map
        if (googlecode_property_vars.generated_pins === '0') {
            pins = googlecode_property_vars.markers;
            markers = jQuery.parseJSON(pins);
        } else {
            if (typeof (googlecode_regular_vars2) !== 'undefined' && googlecode_regular_vars2.markers2.length > 2) {
                pins = googlecode_regular_vars2.markers2;
                markers = jQuery.parseJSON(pins);
            }
        }

        if (markers.length > 0) {
            setMarkers(map, markers);
        }
        
        if(found_id!== undefined){
            google.maps.event.trigger(gmarkers[found_id], 'click');
        }
        map_cluster();
        ///////////////////////////////////////////////////////////// end header map       
    } else {
        /////////////////////////////////////////////////////////////////  listing map

        pins = googlecode_property_vars.single_marker;
        markers = jQuery.parseJSON(pins);
        if (markers.length > 0) {
            setMarkers(map, markers);
        }
     
        if(found_id!== undefined){
           google.maps.event.trigger(gmarkers[found_id], 'click');
        }
        
        curent_gview_lat = jQuery('#google_map_on_list').attr('data-cur_lat');
        curent_gview_long = jQuery('#google_map_on_list').attr('data-cur_long');
        viewPlace = new google.maps.LatLng(curent_gview_lat, curent_gview_long);
        map.setCenter(viewPlace);
        if (Modernizr.mq('only all and (max-width: 1025px)')) {
           
        }

    }



    panorama = map.getStreetView();
    panorama.setPosition(viewPlace);
    heading = parseInt(googlecode_property_vars.camera_angle, 10);

    panorama.setPov(({
        heading: heading,
        pitch: 0
    }));

    function scrollwhel(event) {
        if (map.scrollwheel === true) {
            event.stopPropagation();
        }
    }

    if (document.getElementById('googleMap')) {
        google.maps.event.addDomListener(document.getElementById('googleMap'), 'mousewheel', scrollwhel);
        google.maps.event.addDomListener(document.getElementById('googleMap'), 'DOMMouseScroll', scrollwhel);
    }

    google.maps.event.addListener(panorama, "closeclick", function () {
        jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus,#gmapstreet').show();
        jQuery('#street-view').removeClass('mapcontrolon');
    });


    oms = new OverlappingMarkerSpiderfier(map);
    setOms(gmarkers);
    oms.addListener('spiderfy', function (markers) {
    });

    oms.addListener('unspiderfy ', function (markers) {
    });

}
///////////////////////////////// end initialize
/////////////////////////////////////////////////////////////////////////////////// 


if (typeof google === 'object' && typeof google.maps === 'object') {
    google.maps.event.addDomListener(window, 'load', initialize);
}

function toggleStreetView() {
    "use strict";
    if (panorama.visible) {
        panorama.setVisible(false);
        jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus,#gmapstreet').show();
        jQuery('#street-view').removeClass('mapcontrolon');
        jQuery('#street-view').html('<i class="fa fa-location-arrow"></i> ' + control_vars.street_view_on);
    } else {
        panorama.setVisible(true);
        jQuery('#gmap-next,#gmap-prev ,#geolocation-button,#gmapzoomminus,#gmapzoomplus,#gmapstreet').hide();
        jQuery('#street-view').addClass('mapcontrolon');
        jQuery('#street-view').html('<i class="fa fa-location-arrow"></i> ' + control_vars.street_view_off);
    }
}