/*global google,  Modernizr, InfoBox, window, googlecode_contact_vars, document, jQuery, setMarkers_contact, mapfunctions_vars, close_adv_search, show_advanced_search*/
var gmarkers = [];
var map_open = 0;
var first_time = 1;
var pins = '';
var markers = '';
var infoBox = null;
var vertical_off = '';
var map;
var selected_id = '';
var width_browser = null;
var infobox_width = null;
var wraper_height = null;
var info_image = null;

function initialize() {
    "use strict";
    var styles, mapOptions;
    mapOptions = {
        zoom: parseInt(googlecode_contact_vars.page_custom_zoom, 10),
        scrollwheel: false,
        center: new google.maps.LatLng(googlecode_contact_vars.hq_latitude, googlecode_contact_vars.hq_longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        },
        disableDefaultUI: true
    };

    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
    if (Modernizr.mq('only all and (max-width: 1025px)')) {
        map.setOptions({'draggable': false});
    }

    google.maps.event.addListener(map, 'tilesloaded', function () {
        jQuery('#gmap-loading').remove();
    });

    if (mapfunctions_vars.map_style !== '') {
        styles = JSON.parse(mapfunctions_vars.map_style);
        map.setOptions({styles: styles});
    }

    pins = googlecode_contact_vars.markers;
    markers = jQuery.parseJSON(pins);
    setMarkers_contact(map, markers);
    google.maps.event.trigger(gmarkers[0], 'click');

    function scrollwhel(event) {
        if (map.scrollwheel === true) {
            event.stopPropagation();
        }
    }

    google.maps.event.addDomListener(document.getElementById('googleMap'), 'mousewheel', scrollwhel);
    google.maps.event.addDomListener(document.getElementById('googleMap'), 'DOMMouseScroll', scrollwhel);
}



////////////////////////////////////////////////////////////////////
/// custom pin function
//////////////////////////////////////////////////////////////////////

function custompincontact(image) {
    "use strict";
    image = {
        url: googlecode_contact_vars.path + '/sale.png',
        size: new google.maps.Size(59, 59),
        origin: new google.maps.Point(0, 0),
       anchor: new google.maps.Point(16, 59)
    };
    return image;
}

////////////////////////////////////////////////////////////////////
/// set markers function
//////////////////////////////////////////////////////////////////////


function setMarkers_contact(map, beach) {
    "use strict";
    var shape, boxText, myOptions, myLatLng, marker;
    shape = {
        coord: [1, 1, 1, 38, 38, 59, 59, 1],
        type: 'poly'
    };

    boxText = document.createElement("div");
    myOptions = {
        content: boxText,
        disableAutoPan: true,
        maxWidth: 500,
        pixelOffset: new google.maps.Size(-90, -210),
        zIndex: null,
        closeBoxMargin: "-13px 0px 0px 0px",
        closeBoxURL: "",
        draggable: true,
        infoBoxClearance: new google.maps.Size(1, 1),
        isHidden: false,
        pane: "floatPane",
        enableEventPropagation: false
    };
    infoBox = new InfoBox(myOptions);

    myLatLng = new google.maps.LatLng(googlecode_contact_vars.hq_latitude, googlecode_contact_vars.hq_longitude);
    marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: custompincontact(beach[8]),
        shape: shape,
        title: decodeURIComponent(beach[0].replace(/\+/g, ' ')),
        zIndex: 1,
        image: beach[4],
        price: beach[5],
        type: beach[6],
        type2: beach[7],
        infoWindowIndex: 0
    });

    gmarkers.push(marker);
    google.maps.event.addListener(marker, 'click', function () {
        infoBox.setContent('<div class="info_details contact_info_details"><h2 id="contactinfobox">' + this.title + '</h2><div class="contactaddr">' + googlecode_contact_vars.address + '</div></div>');
        infoBox.open(map, this);
        map.setCenter(this.position);
        map.panBy(0, -120);
    });
}// end setMarkers
google.maps.event.addDomListener(window, 'load', initialize);