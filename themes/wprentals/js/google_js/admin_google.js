/*global admin_google_vars, document, google, window, jQuery, alert, placeMarker*/
var map = '';
var selected_city = '';
var geocoder;
var gmarkers = [];

function initialize() {
    "use strict";
    var myPlace, mapOptions, marker;
    geocoder = new google.maps.Geocoder();
    myPlace = new google.maps.LatLng(admin_google_vars.general_latitude, admin_google_vars.general_longitude);
    mapOptions = {
        flat: false,
        noClear: false,
        zoom: 17,
        scrollwheel: true,
        draggable: true,
        center: myPlace,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
    google.maps.visualRefresh = true;


    marker = new google.maps.Marker({
        position: myPlace
    });
    marker.setMap(map);
    gmarkers.push(marker);
    google.maps.event.addListener(map, 'click', function (event) {
        placeMarker(event.latLng);
    });
}



function placeMarker(location) {
    "use strict";
    var infowindow, marker;
    removeMarkersadmin();
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


google.maps.event.addDomListener(document.getElementById('estate_property-googlemap').getElementsByClassName("handlediv")[0], 'click', function () {
    "use strict";
    google.maps.event.trigger(map, "resize");
});

google.maps.event.addDomListener(window, 'load', initialize);

function removeMarkersadmin(){
    for (i = 0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    }
}

function admin_codeAddress() {
    "use strict";
    var address, full_addr, state, country, infowindow;
    address = document.getElementById('property_address').value;
    full_addr = address + ',' + selected_city;
    state = document.getElementById('property_state').value;

    if (state) {
        full_addr = full_addr + ',' + state;
    }

    country = document.getElementById('property_country').value;
    if (country) {
        full_addr = full_addr + ',' + country;
    }


    geocoder.geocode({'address': full_addr}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            removeMarkersadmin();
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
            alert(admin_google_vars.geo_fails + status);
        }
    });
}

jQuery('#admin_place_pin').click(function (event) {
    "use strict";
    event.preventDefault();
    admin_codeAddress();
});

jQuery('#property_citychecklist label').click(function (event) {
    "use strict";
    selected_city = jQuery(this).text();
});