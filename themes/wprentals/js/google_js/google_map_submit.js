/*global google,  Modernizr, InfoBox, window, alert, setTimeout, googlecode_property_vars, document, placeSavedMarker, placeMarker, removeMarkers, googlecode_home_vars, google_map_submit_vars, jQuery, control_vars, setOms, map_cluster, oms, OverlappingMarkerSpiderfier, setMarkers, googlecode_regular_vars2, setMarkers_contact, mapfunctions_vars, close_adv_search, show_advanced_search*/
var geocoder;
var map;
var selected_id = '';

var gmarkers = [];

function initialize() {
    "use strict";
    var mapOptions, point, styles, listing_lat, listing_lon, infowindow;
    geocoder = new google.maps.Geocoder();

    listing_lat = jQuery('#property_latitude').val();
    listing_lon = jQuery('#property_longitude').val();

    if (listing_lat === '' || listing_lat === 0 || listing_lat === '0') {
        listing_lat = google_map_submit_vars.general_latitude;
    }

    if (listing_lon === '' || listing_lon === 0 || listing_lon === '0') {
        listing_lon = google_map_submit_vars.general_longitude;
    }

    mapOptions = {
        flat: false,
        noClear: false,
        zoom: 17,
        scrollwheel: false,
        draggable: true,
        disableDefaultUI: false,
        center: new google.maps.LatLng(listing_lat, listing_lon),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    if (document.getElementById('googleMapsubmit')) {
        map = new google.maps.Map(document.getElementById('googleMapsubmit'), mapOptions);
    }else{
        return;
    }
    google.maps.visualRefresh = true;




    point = new google.maps.LatLng(listing_lat, listing_lon);
    placeSavedMarker(point);

    if (mapfunctions_vars.map_style !== '') {
        styles = JSON.parse(mapfunctions_vars.map_style);
        map.setOptions({styles: styles});
    }

    google.maps.event.addListener(map, 'click', function (event) {
        placeMarker(event.latLng);
    });
}



function placeSavedMarker(location) {
    "use strict";
    var infowindow, marker;
    removeMarkers();
    marker = new google.maps.Marker({
        position: location,
        map: map
    });
    gmarkers.push(marker);
    infowindow = new google.maps.InfoWindow({
        content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
    });
    infowindow.open(map, marker);
}


function codeAddress() {
    "use strict";
    var address, full_addr, country, city, infowindow;
    address = document.getElementById('property_address').value;
    city = jQuery("#property_city_submit").val();
    full_addr = address + ',' + city;
    country = document.getElementById('property_country').value;
    if (country) {
        full_addr = full_addr + ',' + country;
    }

    geocoder.geocode({'address': full_addr}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            gmarkers.push(marker);
            infowindow = new google.maps.InfoWindow({
                content: 'Latitude: ' + results[0].geometry.location.lat() + '<br>Longitude: ' + results[0].geometry.location.lng()
            });

            infowindow.open(map, marker);
            document.getElementById("property_latitude").value = results[0].geometry.location.lat();
            document.getElementById("property_longitude").value = results[0].geometry.location.lng();
        } else {
            alert(google_map_submit_vars.geo_fails + status);
        }
    });
}



function placeMarker(location) {
    "use strict";
    var infowindow, marker;
    removeMarkers();
    marker = new google.maps.Marker({
        position: location,
        map: map
    });
    gmarkers.push(marker);

    infowindow = new google.maps.InfoWindow({
        content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
    });

    infowindow.open(map, marker);
    document.getElementById("property_latitude").value = location.lat();
    document.getElementById("property_longitude").value = location.lng();

}


////////////////////////////////////////////////////////////////////
/// set markers function
//////////////////////////////////////////////////////////////////////

function removeMarkers() {
    "use strict";
    var i;
    for (i = 0; i < gmarkers.length; i++) {
        gmarkers[i].setMap(null);
    }
}

function setMarkers(map, locations) {
    "use strict";
}// end setMarkers


jQuery('#open_google_submit').click(function () {
    "use strict";
    setTimeout(function () {
        initialize();
        google.maps.event.trigger(map, "resize");
    }, 300);

});


jQuery('#google_capture').click(function (event) {
    "use strict";
    removeMarkers();
    event.preventDefault();
    codeAddress();
});
google.maps.event.addDomListener(window, 'load', initialize);