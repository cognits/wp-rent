/*global google, $, Modernizr, InfoBox, window, alert, setTimeout, pan_to_last_pin, alert, showMyPosition, errorCallback, new_show_advanced_search, MarkerClusterer, navigator, new_hide_advanced_search, adv_search_click, createMarker, infoBox, map, gmarkers, bounds_list, new_open_close_map, custompin,googlecode_property_vars, document, placeSavedMarker, placeMarker, removeMarkers, googlecode_home_vars, google_map_submit_vars, jQuery, control_vars, setOms, map_cluster, oms, OverlappingMarkerSpiderfier, setMarkers, googlecode_regular_vars2, setMarkers_contact, mapfunctions_vars, close_adv_search, show_advanced_search*/

var page_tracker=0;
var pin_images = mapfunctions_vars.pin_images;
var images = jQuery.parseJSON(pin_images);
var ipad_time = 0;
var infobox_id = 0;
var shape = {
    coord: [1, 1, 1, 38, 38, 59, 59, 1],
    type: 'poly'
};

var mcOptions;
var mcluster;
var clusterStyles;
var infoBox;
var infobox_width;




function show_pins_filters_from_file() {
    "use strict";
 
 
   
   if(jQuery("#a_filter_action").length == 0) {
        var action      =   jQuery('#second_filter_action').attr('data-value');
        var category    =   jQuery('#second_filter_categ').attr('data-value');
        var city        =   jQuery('#second_filter_cities').attr('data-value');
        var area        =   jQuery('#second_filter_areas').attr('data-value'); 
        var county      =   jQuery('#second_filter_county').attr('data-value');   
    
    }else{
        var action      =   jQuery('#a_filter_action').attr('data-value');
        var category    =   jQuery('#a_filter_categ').attr('data-value');
        var city        =   jQuery('#a_filter_cities').attr('data-value');
        var area        =   jQuery('#a_filter_areas').attr('data-value');
        var county      =   jQuery('#a_filter_county').attr('data-value');
    }
   
    jQuery('#a_filter_action').attr('data-value','All Sizes');
    jQuery('#a_filter_categ').attr('data-value','All Types');
    jQuery('#a_filter_cities').attr('data-value','All Cities');
    jQuery('#a_filter_areas').attr('data-value','All Areas');
 
 
    if( typeof(action)!=='undefined'){
        action      = action.toLowerCase().trim().replace(" ", "-");
    }
    
    if( typeof(action)!=='undefined'){
        category    = category.toLowerCase().trim().replace(" ", "-");
    }
    
    if( typeof(action)!=='undefined'){
        city        = city.toLowerCase().trim().replace(" ", "-");
    }
    
    if( typeof(action)!=='undefined'){
        area        = area.toLowerCase().trim().replace(" ", "-");
    }
    
    if(  typeof infoBox!=='undefined' && infoBox!== null ){
        infoBox.close(); 
    }
   
 
    var bounds = new google.maps.LatLngBounds();
    
    if(!isNaN(mcluster) ){
        mcluster.setIgnoreHidden(true);
    }

    if(  typeof gmarkers!=='undefined'){

        for (var i=0; i<gmarkers.length; i++) {
                if( !wpestate_classic_form_tax_visible (gmarkers[i].action, action) && action!='all' && action!='all' && action!='all-sizes' ){
                    gmarkers[i].setVisible(false);
                
                }else if (!wpestate_classic_form_tax_visible (gmarkers[i].category, category) && category!='all' && category!='all-types') {   
                    gmarkers[i].setVisible(false);
                
                }else if( !wpestate_classic_form_tax_visible (gmarkers[i].area, area) && area!='all'  && area!='all-areas'){
                    gmarkers[i].setVisible(false);
                
                }else if( !wpestate_classic_form_tax_visible (gmarkers[i].city, city)  && city!='all' && city!='all-cities' ){
                    gmarkers[i].setVisible(false);
                
                }else{
                    gmarkers[i].setVisible(true);
                    bounds.extend( gmarkers[i].getPosition() );       
                }                    
        }//end for
        if(!isNaN(mcluster) ){
            mcluster.repaint();
        }
    }//end if
       
        if( !bounds.isEmpty() ){
            wpestate_fit_bounds(bounds);
        } 
        
}


function wpestate_fit_bounds_nolsit(){
   
    map.setZoom(3);  is_fit_bounds_zoom=0; 
      
}


function wpestate_fit_bounds(bounds){
    is_fit_bounds_zoom=1;
 
  
    if(gmarkers.length===1){
        var center = gmarkers[0].getPosition();
        map.setCenter(center);
        map.setZoom(10);
       
        google.maps.event.addListenerOnce(map, 'idle', function() {
            is_fit_bounds_zoom=0;  
        });
    }else{
        map.fitBounds(bounds);
        google.maps.event.addListenerOnce(map, 'idle', function() {
            is_fit_bounds_zoom=0;  
        });
    }

    
}





 function wpestate_classic_form_tax_visible($onpin,$onreq){
    $onpin = $onpin.toLowerCase();
    $onpin = decodeURI($onpin);
    $onreq = $onreq.toLowerCase();
    $onreq = decodeURI($onreq);            
    $onpin = $onpin.split(' ').join('-');
    $onreq = $onreq.split(' ').join('-');
    
    $onpin = $onpin.latinise();
    $onreq = $onreq.latinise() 

    if($onpin.indexOf($onreq)> -1 ){
        return true;
    } else{
        return false;
    }
 }
 
 
/////////////////////////////////////////////////////////////////////////////////////////////////
//  set markers... loading pins over map
/////////////////////////////////////////////////////////////////////////////////////////////////  

function setMarkers(map, locations) {
    "use strict";
    var  beach, id, lat, lng, title, pin, counter, image, price, single_first_type, single_first_action, link, city, area, cleanprice, rooms, baths, size, single_first_type_name, single_first_action_name, map_open, myLatLng, selected_id, open_height, boxText, closed_height, width_browser, infobox_width, vertical_pan, myOptions,status, i, slug1, val1, how1, slug2, val2, how2, slug3, val3, how3, slug4, val4, how4, slug5, val5, how5, slug6, val6, how6, slug7, val7, how7, slug8, val8, how8;
    selected_id = parseInt(jQuery('#gmap_wrapper').attr('data-post_id'), 10);
    if (isNaN(selected_id)) {
        selected_id = parseInt(jQuery('#google_map_on_list').attr('data-post_id'), 10);
    }

    open_height = parseInt(mapfunctions_vars.open_height, 10);
    closed_height = parseInt(mapfunctions_vars.closed_height, 10);
    boxText = document.createElement("div");
    width_browser = jQuery(window).width();

    infobox_width = 700;
    vertical_pan = -215;
    if (width_browser < 900) {
        infobox_width = 500;
    }
    if (width_browser < 600) {
        infobox_width = 400;
    }
    if (width_browser < 400) {
        infobox_width = 200;
    }


    myOptions = {
        content: boxText,
        disableAutoPan: true,
        maxWidth: infobox_width,
        boxClass: "mybox",
        zIndex: null,
        closeBoxMargin: "-13px 0px 0px 0px",
        closeBoxURL: "",
        infoBoxClearance: new google.maps.Size(1, 1),
        isHidden: false,
        pane: "floatPane",
        enableEventPropagation: false
    };
    infoBox = new InfoBox(myOptions);

    for (i = 0; i < locations.length; i++) {
        beach                       = locations[i];
        id                          = beach[10];
        lat                         = beach[1];
        lng                         = beach[2];
        title                       = decodeURIComponent(beach[0]);
        pin                         = beach[8];
        counter                     = beach[3];
        image                       = decodeURIComponent(beach[4]);
        price                       = decodeURIComponent(beach[5]);
        single_first_type           = decodeURIComponent(beach[6]);
        single_first_action         = decodeURIComponent(beach[7]);
        link                        = decodeURIComponent(beach[9]);
        city                        = decodeURIComponent(beach[11]);
        area                        = decodeURIComponent(beach[12]);
        cleanprice                  = beach[13];
        rooms                       = beach[14];
        baths                       = beach[15];
        size                        = beach[16];
        single_first_type_name      = decodeURIComponent(beach[17]);
        single_first_action_name    = decodeURIComponent(beach[18]);
        status                      = decodeURIComponent(beach[19]);

        if (mapfunctions_vars.custom_search === 'yes') {
            slug1 = beach[19];
            val1 = beach[20];
            how1 = beach[21];
            slug2 = beach[22];
            val2 = beach[23];
            how2 = beach[24];
            slug3 = beach[25];
            val3 = beach[26];
            how3 = beach[27];
            slug4 = beach[28];
            val4 = beach[29];
            how4 = beach[30];
            slug5 = beach[31];
            val5 = beach[32];
            how5 = beach[33];
            slug6 = beach[34];
            val6 = beach[35];
            how6 = beach[36];
            slug7 = beach[37];
            val7 = beach[38];
            how7 = beach[39];
            slug8 = beach[40];
            val8 = beach[41];
            how8 = beach[42];
        }

        createMarker(infobox_width ,size, i, id, lat, lng, pin, title, counter, image, price, single_first_type, single_first_action, link, city, area, rooms, baths, cleanprice, slug1, val1, how1, slug2, val2, how2, slug3, val3, how3, slug4, val4, how4, slug5, val5, how5, slug6, val6, how6, slug7, val7, how7, slug8, val8, how8, single_first_type_name, single_first_action_name,status);
        // found the property

        if (selected_id === id) {
            found_id = i;
        }
    }//end for

    // pan to the latest pin for taxonmy and adv search  

    if (mapfunctions_vars.generated_pins !== '0') {
        myLatLng = new google.maps.LatLng(lat, lng);
  
        pan_to_last_pin(myLatLng);
        oms = new OverlappingMarkerSpiderfier(map);
        setOms(gmarkers);
        oms.addListener('spiderfy', function (markers) {
        });
        oms.addListener('unspiderfy ', function (markers) {
        });
    }
    
    if(mapfunctions_vars.is_prop_list==='1' || mapfunctions_vars.is_tax==='1' ){
        show_pins_filters_from_file();
    }
    
}// end setMarkers


/////////////////////////////////////////////////////////////////////////////////////////////////
//  create marker
/////////////////////////////////////////////////////////////////////////////////////////////////  

function createMarker(infobox_width, size, i, id, lat, lng, pin, title, counter, image, price, single_first_type, single_first_action, link, city, area, rooms, baths, cleanprice, slug1, val1, how1, slug2, val2, how2, slug3, val3, how3, slug4, val4, how4, slug5, val5, how5, slug6, val6, how6, slug7, val7, how7, slug8, val8, how8, single_first_type_name, single_first_action_name,status) {
    "use strict";
    var marker, myLatLng;

    myLatLng = new google.maps.LatLng(lat, lng);
    if (mapfunctions_vars.custom_search === 'yes') {
        marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: custompin(pin),
            custompin: pin,
            shape: shape,
            title: decodeURIComponent(title.replace(/\+/g, ' ')),
            zIndex: counter,
            image: image,
            idul: id,
            price: price,
            category: single_first_type,
            action: single_first_action,
            link: link,
            city: city,
            area: area,
            infoWindowIndex: i,
            rooms: rooms,
            guest_no: baths,
            size: size,
            cleanprice: cleanprice,
            category_name: single_first_type_name,
            action_name: single_first_action_name,
            slug1: slug1,
            val1: val1,
            howto1: how1,
            slug2: slug2,
            val2: val2,
            howto2: how2,
            slug3: slug3,
            val3: val3,
            howto3: how3,
            slug4: slug4,
            val4: val4,
            howto4: how4,
            slug5: slug5,
            val5: val5,
            howto5: how5,
            slug6: slug6,
            val6: val6,
            howto6: how7,
            slug7: slug7,
            val7: val7,
            howto7: how7,
            slug8: slug8,
            val8: val8,
            howto8: how8,
         
        });

    } else {
        marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: custompin(pin),
            custompin: pin,
            shape: shape,
            title: title,
            zIndex: counter,
            image: image,
            idul: id,
            price: price,
            category: single_first_type,
            action: single_first_action,
            link: link,
            city: city,
            area: area,
            infoWindowIndex: i,
            rooms: rooms,
            guest_no: baths,
            cleanprice: cleanprice,
            size: size,
            category_name: single_first_type_name,
            action_name: single_first_action_name,
            status:status
        });

    }

    gmarkers.push(marker);

    if (typeof (bounds_list) !== "undefined") {
        bounds_list.extend(marker.getPosition());
    }


    google.maps.event.addListener(marker, 'click', function (event) {
        var title, info_image, category, action, category_name, action_name, in_type, infoguest, inforooms,  vertical_off, status_html,status;
        new_open_close_map(1);
        external_action_ondemand=1;
     
        if (this.image === '') {
            info_image = '<img src="' + mapfunctions_vars.path + '/idxdefault.jpg" alt="image" />';
        } else {
            info_image = this.image;
        }

        status = decodeURIComponent(this.status.replace(/-/g, ' '));
        category = decodeURIComponent(this.category.replace(/-/g, ' '));
        action = decodeURIComponent(this.action.replace(/-/g, ' '));
        category_name = decodeURIComponent(this.category_name.replace(/-/g, ' '));
        action_name = decodeURIComponent(this.action_name.replace(/-/g, ' '));
       
        status_html='';
        if (status!=='normal' && status!==''){
            status_html='<div class="property_status status_'+status+'">'+status+'</div>';
        }


        in_type = mapfunctions_vars.in_text;
        if (category === '' || action === '') {
            in_type = " ";
        }
        in_type = " / ";

        if (this.guest_no !== '') {
            infoguest = '<span id="infoguest">' + this.guest_no + '</span>';
        } else {
            infoguest = '';
        }

        if (this.rooms !== '') {
            inforooms = '<span id="inforoom">' + this.rooms + '</span>';
        } else {
            inforooms = '';
        }

        title = this.title.toString();
        title = title.substr(0, 22);
        if (this.title.length > 22) {
            title = title + "...";
        }
        infoBox.setContent('<div class="info_details"><span id="infocloser" onClick=\'javascript:infoBox.close();\' ></span>'+status_html+'<a href="' + this.link + '"><div class="infogradient"></div><div class="infoimage" style="background-image:url(' + info_image + ')"  ></div></a><a href="' + this.link + '" id="infobox_title">' + title + '</a><div class="prop_detailsx">' + category_name + " " + in_type + " " + action_name + '</div><div class="infodetails">' + infoguest + inforooms + '</div><div class="prop_pricex">' + this.price + '</div></div>');

        infoBox.open(map, this);
      
        map.setCenter(this.position);

        switch (infobox_width) {
            case 700:
                if (!document.getElementById('google_map_on_list')) {
                    if (mapfunctions_vars.listing_map === 'top') {
                        if( document.getElementById('google_map_prop_list') ){
                            map.panBy(0, -100);   
                         
                        }else{
                            map.panBy(100, -100);   
                        }
                    } else {        
                        map.panBy(10, -110);
                    }
                } else {
                 
                    map.panBy(0, -160);
                }
                vertical_off = 0;
                break;
            case 500:
                if( document.getElementById('google_map_prop_list') ){
                    map.panBy(50, -120);   
                }else{
                    map.panBy(50, -150);   
                }
                break;
            case 400:
              
                if( document.getElementById('google_map_prop_list') ){
                     map.panBy(100, -220); 
                }else{
                    map.panBy(0, -150);   
                }
                break;
            case 200:
                map.panBy(20, -170);
                break;
        }

        if (control_vars.show_adv_search_map_close === 'no') {
            $('.search_wrapper').addClass('adv1_close');
            adv_search_click();
        }
        close_adv_search();
    });/////////////////////////////////// end event listener

    if (mapfunctions_vars.generated_pins !== '0') {
        pan_to_last_pin(myLatLng);
    }
}

function pan_to_last_pin(myLatLng) {
    "use strict";
    map.setCenter(myLatLng);
}

/////////////////////////////////////////////////////////////////////////////////////////////////
//  map set search
/////////////////////////////////////////////////////////////////////////////////////////////////  
function setOms(gmarkers) {
    "use strict";
    var i;
    for (i = 0; i < gmarkers.length; i++) {
        if (typeof oms !== 'undefined') {
            oms.addMarker(gmarkers[i]);
        } 
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//  open close map
/////////////////////////////////////////////////////////////////////////////////////////////////  
function new_open_close_map(type) {
    "use strict";
    var current_height, closed_height, open_height, googleMap_h, gmapWrapper_h, vertical_off;
    if (jQuery('#gmap_wrapper').hasClass('fullmap')) {
        return;
    }

    if (mapfunctions_vars.open_close_status !== '1') { // we can resize map

        current_height = jQuery('#googleMap').outerHeight();
        closed_height = parseInt(mapfunctions_vars.closed_height, 10);
        open_height = parseInt(mapfunctions_vars.open_height, 10);
        googleMap_h = open_height;
        gmapWrapper_h = open_height;

        if (infoBox !== null) {
            infoBox.close();
        }

        if (current_height === closed_height) {
            googleMap_h = open_height;
            if (Modernizr.mq('only all and (max-width: 940px)')) {
                gmapWrapper_h = open_height;
            } else {
                jQuery('#gmap-menu').show();
                gmapWrapper_h = open_height;
            }

            new_show_advanced_search();
            vertical_off = 0;
            jQuery('#openmap').empty().append('<i class="fa fa-angle-up"></i>' + mapfunctions_vars.close_map);

        } else if (type === 2) {
            jQuery('#gmap-menu').hide();
            jQuery('#advanced_search_map_form').hide();
            googleMap_h = gmapWrapper_h = closed_height;
            // hide_advanced_search();
            new_hide_advanced_search();
            vertical_off = 150;
        }

        jQuery('#googleMap').animate({'height': googleMap_h + 'px'});
        jQuery('#gmap_wrapper').animate({'height': gmapWrapper_h + 'px'}, 500, function () {
            google.maps.event.trigger(map, "resize");
            map.setOptions({'scrollwheel': true});
            jQuery('#googleMap').addClass('scrollon');
            jQuery('.tooltip').fadeOut("fast");
        });

    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//  build map cluter
/////////////////////////////////////////////////////////////////////////////////////////////////   
function map_cluster() {
    "use strict";
    if (mapfunctions_vars.user_cluster === 'yes') {
        clusterStyles = [
            {
                textColor: '#ffffff',
                opt_textColor: '#ffffff',
                url: mapfunctions_vars.path + '/cloud.png',
                height: 72,
                width: 72,
                textSize: 15
            }
        ];
        mcOptions = {gridSize: 50,
            ignoreHidden: true,
            maxZoom: parseInt( mapfunctions_vars.zoom_cluster,10),
            styles: clusterStyles
        };
        mcluster = new MarkerClusterer(map, gmarkers, mcOptions);
        mcluster.setIgnoreHidden(true);
    }

}



/////////////////////////////////////////////////////////////////////////////////////////////////
/// zoom
/////////////////////////////////////////////////////////////////////////////////////////////////


if (document.getElementById('gmapzoomplus')) {
    google.maps.event.addDomListener(document.getElementById('gmapzoomplus'), 'click', function () {
        "use strict";
        var current = parseInt(map.getZoom(), 10);
        current = current + 1;
        if (current > 20) {
            current = 20;
        }
        map.setZoom(current);
    });
}


if (document.getElementById('gmapzoomminus')) {
    google.maps.event.addDomListener(document.getElementById('gmapzoomminus'), 'click', function () {
        "use strict";
        var current = parseInt(map.getZoom(), 10);
        current = current - 1;
        if (current < 0) {
            current = 0;
        }
        map.setZoom(current);
    });
}




jQuery('#gmapstreet').click(function () {
    "use strict";
    toggleStreetView();
});

/////////////////////////////////////////////////////////////////////////////////////////////////
/// geolocation
/////////////////////////////////////////////////////////////////////////////////////////////////

if (document.getElementById('geolocation-button')) {
    google.maps.event.addDomListener(document.getElementById('geolocation-button'), 'click', function () {
        "use strict";
        myposition(map);
        close_adv_search();
    });
}


if (document.getElementById('mobile-geolocation-button')) {
    google.maps.event.addDomListener(document.getElementById('mobile-geolocation-button'), 'click', function () {
        "use strict";
        myposition(map);
        close_adv_search();
    });
}


jQuery('#mobile-geolocation-button,#geolocation-button').click(function () {
    "use strict";
    myposition(map);
});


/*
function myposition(map) {
    "use strict";
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showMyPosition, errorCallback, {timeout: 10000});
    } else {
        alert(mapfunctions_vars.geo_no_brow);
    }
}
*/
function myposition(map){    
    
    if(navigator.geolocation){
       // navigator.geolocation.getCurrentPosition(showMyPosition,errorCallback,{timeout:10000});
        var latLong;
        jQuery.getJSON("http://ipinfo.io", function(ipinfo){
            latLong = ipinfo.loc.split(",");
            showMyPosition (latLong);
        });
      
    }else{
        alert(mapfunctions_vars.geo_no_brow);
    }
}

function errorCallback() {
    "use strict";
    alert(mapfunctions_vars.geo_no_pos);
}

function showMyPosition(pos) {
    "use strict";
    var shape, MyPoint, marker, populationOptions, cityCircle, label;
    shape = {
        coord: [1, 1, 1, 38, 38, 59, 59, 1],
        type: 'poly'
    };

//    MyPoint = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
    MyPoint=  new google.maps.LatLng( pos[0], pos[1]);
    map.setCenter(MyPoint);
    map.setZoom(13);
    marker = new google.maps.Marker({
        position: MyPoint,
        map: map,
        icon: custompinchild(),
        shape: shape,
        title: '',
        zIndex: 999999999,
        image: '',
        price: '',
        category: '',
        action: '',
        link: '',
        infoWindowIndex: 99,
        radius: parseInt(mapfunctions_vars.geolocation_radius, 10) + ' ' + mapfunctions_vars.geo_message
    });

    populationOptions = {
        strokeColor: '#67cfd8',
        strokeOpacity: 0.6,
        strokeWeight: 1,
        fillColor: '#67cfd8',
        fillOpacity: 0.2,
        map: map,
        center: MyPoint,
        radius: parseInt(mapfunctions_vars.geolocation_radius, 10)
    };
    cityCircle = new google.maps.Circle(populationOptions);

    label = new Label({
        map: map
    });
    label.bindTo('position', marker);
    label.bindTo('text', marker, 'radius');
    label.bindTo('visible', marker);
    label.bindTo('clickable', marker);
    label.bindTo('zIndex', marker);

}



function custompinchild() {
    "use strict";
    var custom_img, image;
    var extension='';
    var ratio = jQuery(window).dense('devicePixelRatio');
    
    if(ratio>1){
        extension='_2x';
    }
    
    if (images['userpin'] === '') {
        custom_img = mapfunctions_vars.path + '/' + 'userpin' +extension+ '.png';
    } else {
        custom_img = images['userpin'];
        if(ratio>1){
            custom_img=custom_img.replace(".png","_2x.png");
        }
    }

    image = {
        url: custom_img,
        size: new google.maps.Size(59, 59),
        origin: new google.maps.Point(0, 0),
       // anchor: new google.maps.Point(16, 59)
    };
    
      
    if(ratio>1){
         
        var   image = {
            url: custom_img, 
           
            size :  new google.maps.Size(44, 50),
            scaledSize   :  new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
            optimized:false
  
        };
     
    }else{
       var   image = {
            url: custom_img, 
            size: new google.maps.Size(59, 59),
            origin: new google.maps.Point(0,0),
       
        };
    }
    
    
    return image;
}



// same thing as above but with ipad double click workaroud solutin
jQuery('#googleMap,#google_map_prop_list_wrapper').click(function (event) {
    "use strict";
    var time_diff;
    time_diff = event.timeStamp - ipad_time;

    if (time_diff > 300) {
        ipad_time = event.timeStamp;
        if (map.scrollwheel === false) {
            map.setOptions({'scrollwheel': true});
            jQuery('#googleMap').addClass('scrollon');
        } else {
            map.setOptions({'scrollwheel': false});
            jQuery('#googleMap').removeClass('scrollon');
        }
        jQuery('.tooltip').fadeOut("fast");


        if (Modernizr.mq('only all and (max-width: 1025px)')) {
            if (map.draggable === false) {
                map.setOptions({'draggable': true});
            } else {
                map.setOptions({'draggable': false});
            }
        }

    }
});

jQuery('#google_map_on_list').click(function (event) {
    if (Modernizr.mq('only all and (max-width: 1025px)')) {
        if (map.draggable === false) {
            map.setOptions({'draggable': true});
        } else {
            map.setOptions({'draggable': false});
        }
    }
});







/////////////////////////////////////////////////////////////////////////////////////////////////
/// 
/////////////////////////////////////////////////////////////////////////////////////////////////

if (document.getElementById('gmap')) {
    google.maps.event.addDomListener(document.getElementById('gmap'), 'mouseout', function () {
        "use strict";
        map.setOptions({'scrollwheel': true});
        google.maps.event.trigger(map, "resize");
    });
}


if (document.getElementById('search_map_button')) {
    google.maps.event.addDomListener(document.getElementById('search_map_button'), 'click', function () {
        "use strict";
        infoBox.close();
    });
}



if (document.getElementById('advanced_search_map_button')) {
    google.maps.event.addDomListener(document.getElementById('advanced_search_map_button'), 'click', function () {
        "use strict";
        infoBox.close();
    });
}

////////////////////////////////////////////////////////////////////////////////////////////////
/// navigate troguh pins 
///////////////////////////////////////////////////////////////////////////////////////////////

jQuery('#gmap-next').click(function () {
    "use strict";
    current_place++;
    external_action_ondemand=1;
    if (current_place > gmarkers.length) {
        current_place = 1;
    }
    while (gmarkers[current_place - 1].visible === false) {
        current_place++;
        if (current_place > gmarkers.length) {
            current_place = 1;
        }
    }

    if (map.getZoom() < 15) {
        map.setZoom(15);
    }
    google.maps.event.trigger(gmarkers[current_place - 1], 'click');
});


jQuery('#gmap-prev').click(function () {
    current_place--;
    external_action_ondemand=1;
    if (current_place < 1) {
        current_place = gmarkers.length;
    }
    while (gmarkers[current_place - 1].visible === false) {
        current_place--;
        if (current_place > gmarkers.length) {
            current_place = 1;
        }
    }
    if (map.getZoom() < 15) {
        map.setZoom(15);
    }
    google.maps.event.trigger(gmarkers[current_place - 1], 'click');
});


///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// filter pins 
//////////////////////////////////////////////////////////////////////////////////////////////////////////

jQuery('.advanced_action_div li').click(function () {
    "use strict";
    var action = jQuery(this).val();
});





if (document.getElementById('gmap-menu')) {
    google.maps.event.addDomListener(document.getElementById('gmap-menu'), 'click', function (event) {
        "use strict";
        var category;
        infoBox.close();

        if (event.target.nodeName === 'INPUT') {
            category = event.target.className;

            if (event.target.name === "filter_action[]") {
                if (actions.indexOf(category) !== -1) {
                    actions.splice(actions.indexOf(category), 1);
                } else {
                    actions.push(category);
                }
            }

            if (event.target.name === "filter_type[]") {
                if (categories.indexOf(category) !== -1) {
                    categories.splice(categories.indexOf(category), 1);
                } else {
                    categories.push(category);
                }
            }

            show(actions, categories);
        }

    });
}
//!visible_or_not(mapfunctions_vars.hows[0], gmarkers[i].val1, val1, mapfunctions_vars.slugs[0])

function visible_or_not(how, slug, value, read) {
    "use strict";
     var slider_min, slider_max, parsevalue;
    if (value !== '' && typeof (value) !== 'undefined') {
        // value = value.replace('%',''); 
    }
  
    //////////////////////////////////////////////
    // in case of slider - 
    if (read === 'property_price' && mapfunctions_vars.slider_price === 'yes') {
        slider_min = parseInt(jQuery('#price_low').val(), 10);
        slider_max = parseInt(jQuery('#price_max').val(), 10);
        if (slug >= slider_min && slug <= slider_max) {
            return true;
        } else {
            return false;
        }
    }
    //////////////////////////////////////////////
    // END in case of slider - 

    if (read === 'none') {
        return true;
    }

    if (value !== '' && value !== ' ' && value !== 'all') {
        parsevalue = parseInt(value, 10);
        if (how === 'greater') {
            if (slug >= parsevalue) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'smaller') {
            slug = parseInt(slug, 10);
            if (slug <= parsevalue) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'equal') {
            if (slug === value || value === 'all') {
                return true;
            } else {
                return false;
            }
        } else if (how === 'like') {
            slug = slug.toLowerCase();
            value = value.toLowerCase();
            if (slug.indexOf(value) > -1) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'date bigger') {
            slug = new Date(slug);
            value = new Date(value);
            if (slug >= value) {
                return true;
            } else {
                return false;
            }
        } else if (how === 'date smaller') {
            slug = new Date(slug);
            value = new Date(value);
            if (slug <= value) {
                return true;
            } else {
                return false;
            }
        }
        //return false;
    } else {
        return true;
    }
}


function get_custom_value(slug) {
    "use strict";
    var value;

    if (slug === 'adv_categ' || slug === 'adv_actions' || slug === 'advanced_city' || slug === 'advanced_area') {
        value = jQuery('#' + slug).attr('data-value');
    } else if (slug === 'property_price' && mapfunctions_vars.slider_price === 'yes') {
        value = jQuery('#price_low').val();
    } else {
        value = jQuery('#' + slug).val();
    }

    return value;
}



function show_pins() {
    "use strict";

    var is_google_map, results_no, city, area, guests, bounds, i;
    is_google_map = parseInt(jQuery('#isgooglemap').attr('data-isgooglemap'), 10);
    if (is_google_map !== 1) {
        return;
    }

    results_no = 0;
    city = jQuery('#advanced_city').attr('data-value');
    area = jQuery('#advanced_area').attr('data-value');
    guests = parseInt(jQuery('#guest_no').val(), 10);

    if (isNaN(guests)) {
        guests = 0;
    }

    if (typeof infoBox !== 'undefined' && infoBox !== null) {
        infoBox.close();
    }

    bounds = new google.maps.LatLngBounds();
    mcluster.setIgnoreHidden(true);
    if (typeof gmarkers !== 'undefined') {
        for (i = 0; i < gmarkers.length; i++) {
            if (gmarkers[i].area !== area && area !== 'all' && area !== '') {
                gmarkers[i].setVisible(false);
            } else if (gmarkers[i].city !== city && city !== 'all') {
                gmarkers[i].setVisible(false);
            } else if (parseInt(gmarkers[i].guests, 10) !== guests && guests !== 0) {
                gmarkers[i].setVisible(false);
            } else {
                gmarkers[i].setVisible(true);
                results_no = results_no + 1;
                bounds.extend(gmarkers[i].getPosition());
            }
        }//end for
        mcluster.repaint();
    }//end if

    if (mapfunctions_vars.generated_pins === '0') {
        if (results_no === 0) {
            jQuery('#gmap-noresult').show();
            jQuery('#results').hide();
        } else {
            jQuery('#gmap-noresult').hide();
            if (!bounds.isEmpty()) {
                wpestate_fit_bounds(bounds);
            }
            jQuery("#results, #showinpage,#showinpage_mobile").show();
    
            jQuery("#results_no").show().empty().append(results_no);
        }
    } else {
        wpestate_get_filtering_ajax_result();
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////
/// get pin image
/////////////////////////////////////////////////////////////////////////////////////////////////
function convertToSlug(Text) {
    "use strict";
    return Text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
}


function custompin(image) {
    "use strict";
    var custom_img;
    
    var extension='';
    var ratio = jQuery(window).dense('devicePixelRatio');
  
    if(ratio>1){
        extension='_2x';
    }

    if (image !== '') {
        if (images[image] === '') {
            custom_img = mapfunctions_vars.path + '/' + image + extension + '.png';
        } else {
            custom_img = images[image];
            if(ratio>1){
                custom_img=custom_img.replace(".png","_2x.png");
            }
        }
    } else {
        custom_img = mapfunctions_vars.path + '/none.png';
    }

    if (typeof (custom_img) === 'undefined') {
        custom_img = mapfunctions_vars.path + '/none.png';
    }

   
    if(ratio>1){
   
        image = {
            url: custom_img, 
            size :  new google.maps.Size(44, 50),
            scaledSize   :  new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
            optimized:false
        };
     
    }else{
            image = {
            url: custom_img,
            size: new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
          
        };
    }
    
    return image;
}


function custompinhover() {
    "use strict";
    var custom_img, image;
    
    var extension='';
    var ratio = jQuery(window).dense('devicePixelRatio');
    
    if(ratio>1){
        extension='_2x';
    }
    
    custom_img = mapfunctions_vars.path + '/hover'+extension+'.png';


    image = {
        url: custom_img,
        size: new google.maps.Size(44, 50),
        origin: new google.maps.Point(0, 0),
       // anchor: new google.maps.Point(26, 25)
    };
    
     if(ratio>1){
  
        image = {
            url: custom_img, 
            size :  new google.maps.Size(44, 50),
            scaledSize   :  new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
            optimized:false
            
          };
    
    }else{
        image = {
            url: custom_img,
            size: new google.maps.Size(44, 50),
            origin: new google.maps.Point(0, 0),
        };
    }
   
    return image;
}

function custompin2(image) {
    "use strict";
    image = {
        url: mapfunctions_vars.path + '/' + image + '.png',
        size: new google.maps.Size(59, 59),
        origin: new google.maps.Point(0, 0),
      //  anchor: new google.maps.Point(16, 59)
    };
    return image;
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//// Circle label
/////////////////////////////////////////////////////////////////////////////////////////////////

function Label(opt_options) {
    "use strict";
    // Initialization
    this.setValues(opt_options);
    // Label specific
    var span = this.span_ = document.createElement('span');
    span.style.cssText = 'position: relative; left: -50%; top: 8px; ' +
            'white-space: nowrap;  ' +
            'padding: 2px; background-color: white;opacity:0.7';


    var div = this.div_ = document.createElement('div');
    div.appendChild(span);
    div.style.cssText = 'position: absolute; display: none';
}
;
Label.prototype = new google.maps.OverlayView;


// Implement onAdd
Label.prototype.onAdd = function () {
    var pane = this.getPanes().overlayImage;
    pane.appendChild(this.div_);

    // Ensures the label is redrawn if the text or position is changed.
    var me = this;
    this.listeners_ = [
        google.maps.event.addListener(this, 'position_changed', function () {
            me.draw();
        }),
        google.maps.event.addListener(this, 'visible_changed', function () {
            me.draw();
        }),
        google.maps.event.addListener(this, 'clickable_changed', function () {
            me.draw();
        }),
        google.maps.event.addListener(this, 'text_changed', function () {
            me.draw();
        }),
        google.maps.event.addListener(this, 'zindex_changed', function () {
            me.draw();
        }),
        google.maps.event.addDomListener(this.div_, 'click', function () {
            if (me.get('clickable')) {
                google.maps.event.trigger(me, 'click');
            }
        })
    ];
};


// Implement onRemove
Label.prototype.onRemove = function () {
    this.div_.parentNode.removeChild(this.div_);
    // Label is removed from the map, stop updating its position/text.
    for (var i = 0, I = this.listeners_.length; i < I; ++i) {
        google.maps.event.removeListener(this.listeners_[i]);
    }
};


// Implement draw
Label.prototype.draw = function () {
    var projection = this.getProjection();
    var position = projection.fromLatLngToDivPixel(this.get('position'));
    var div = this.div_;
    div.style.left = position.x + 'px';
    div.style.top = position.y + 'px';


    var visible = this.get('visible');
    div.style.display = visible ? 'block' : 'none';


    var clickable = this.get('clickable');
    this.span_.style.cursor = clickable ? 'pointer' : '';


    var zIndex = this.get('zIndex');
    div.style.zIndex = zIndex;


    this.span_.innerHTML = this.get('text').toString();
};



/////////////////////////////////////////////////////////////////////////////////////////////////
/// close advanced search
/////////////////////////////////////////////////////////////////////////////////////////////////
function close_adv_search() {

}


function new_show_advanced_search() {
    "use strict";
    jQuery("#search_wrapper").removeClass("hidden");
}

function new_hide_advanced_search() {
    "use strict";
    if (mapfunctions_vars.show_adv_search === 'no') {
        jQuery("#search_wrapper").addClass("hidden");
    }
}


function wpestate_set_filter_pins(map, new_markers) {
    "use strict";


    for (var i = 0; i < gmarkers.length; i++) {
        gmarkers[i].setVisible(false);
        gmarkers[i].setMap(null);
    }
    gmarkers = [];
    
    
    if( typeof (mcluster)!=='undefined'){
        mcluster.clearMarkers();  
    }
    
    if (new_markers.length > 0) {  
        bounds_list = new google.maps.LatLngBounds();
        setMarkers(map, new_markers);

        if (!bounds_list.isEmpty()) {
            if( typeof (map)!=='undefined'){
                wpestate_fit_bounds(bounds_list);
            }
        }
        map_cluster();
    }
}


function wpestate_set_filter_pins_ondemand(map, new_markers) {
    "use strict";

    for (var i = 0; i < gmarkers.length; i++) {
        gmarkers[i].setVisible(false);
        gmarkers[i].setMap(null);
    }
    gmarkers = [];
     
    if (new_markers.length > 0) {  
        if( typeof (mcluster)!=='undefined'){
           mcluster.clearMarkers();  
        }
        setMarkers(map, new_markers);
        map_cluster();

    }

    oms = new OverlappingMarkerSpiderfier(map);
    setOms(gmarkers);
    oms.addListener('spiderfy', function (markers) {
    });
    oms.addListener('unspiderfy ', function (markers) {
    });
}


function wpestate_hover_action_pin(listing_id) {
    "use strict";
    for (var i = 0; i < gmarkers.length; i++) {
        if (parseInt(gmarkers[i].idul, 10) === parseInt(listing_id, 10)) {
            gmarkers[i].setIcon(custompinhover());
        }
    }
}

function wpestate_return_hover_action_pin(listing_id) {
    "use strict";
    for (var i = 0; i < gmarkers.length; i++) {
        if (parseInt(gmarkers[i].idul, 10) === parseInt(listing_id, 10)) {
            gmarkers[i].setIcon(custompin(gmarkers[i].custompin));
        }
    }
}

String.prototype.capitalize = function () {
    return this.replace(/(?:^|\s)\S/g, function (a) {
        return a.toUpperCase();
    });
};