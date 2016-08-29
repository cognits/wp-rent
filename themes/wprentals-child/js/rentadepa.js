'use strict';

( function ($) {
  window.rentaDepa = {};
  
  window.rentaDepa.utils = {};

  rentaDepa.utils.bodyClass = (function (){

    var setUserOS = function() {
      var OSName = "";
      if (navigator.appVersion.indexOf("Win") != -1) OSName = "windows";
      if (navigator.appVersion.indexOf("Mac") != -1) OSName = "mac";
      if (navigator.appVersion.indexOf("X11") != -1) OSName = "unix";
      if (navigator.appVersion.indexOf("Linux") != -1) OSName = "linux";

      $('body').addClass(OSName);
    };

    var setUserAgent = function() {
      if (navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)) {
          $('body').addClass('mobile');
      } else {
          $('body').addClass('desktop');
          if (navigator.userAgent.match(/MSIE 9.0/)) {
              $('body').addClass('ie9');
          }
      }
    };

    var getUserAgent = function() {
      return $('body').hasClass('mobile') ? "mobile" : "desktop";
    };
    return {
      setUserOS: setUserOS,
      setUserAgent: setUserAgent,
      getUserAgent: getUserAgent
    }
  }());

  rentaDepa.utils.modifyFootAdress = (function($) {

    var addSpanFootAddress = function(){
      var $innerIconAddress = $('.widget_contact_addr i');
      var $pAddress = $('.widget_contact_addr');
      $pAddress.find('i').remove();

      var textAddress = $pAddress.text();

      $pAddress.text('').html("<span>"+ textAddress+"</span>");
      $pAddress.prepend($innerIconAddress);
    }
    return {
      addSpanFootAddress: addSpanFootAddress
    }
  }($));

}(jQuery));


rentaDepa.utils.modifyFootAdress.addSpanFootAddress();