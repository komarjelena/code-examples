(function($) {

	'use strict';



    /*-------------------------------------------------------------------------------*/
    /*
     /*-------------------------------------------------------------------------------*/

    Drupal.behaviors.Mobilemenu = {
        attach: function (context, settings) {

          $('.navbar-toggle').click(function (e) {
            e.stopImmediatePropagation();
            $(this).toggleClass('open');
            $(this).parent().parent().parent().find('#mobile-menu').toggleClass('open');
            $('body').toggleClass('mobile-overlay');
          });

        }
    };

  Drupal.behaviors.RadiobuttonPopup = {
    attach: function (context, settings) {

      $('input[type="radio"]').click(function(){
        var $radio = $(this);

        // if this was previously checked
        if ($radio.data('waschecked') == true) {
          $radio.prop('checked', false);
          $radio.data('waschecked', false);
        } else
          $radio.data('waschecked', true);

        $radio.parent().toggleClass('checked');

        // remove was checked from other radios
        $radio.siblings('input[type="radio"]').data('waschecked', false);
      });

      $('input[type="checkbox"]').click(function(){
        var $radio = $(this);

        // if this was previously checked
        if ($radio.data('waschecked') == true) {
          $radio.prop('checked', false);
          $radio.data('waschecked', false);
        } else
          $radio.data('waschecked', true);

        $radio.parent().toggleClass('checked');

        // remove was checked from other radios
        $radio.siblings('input[type="radio"]').data('waschecked', false);
      });
    }
  };


  Drupal.behaviors.ctaPopup = {
    attach: function (context) {

      // Trigger download popup on page load.
      $('a.popup-element-title', context).once( function() {
        $(this).addClass('clicked');

        $($(this)[0]).click();

        $('#popup-active-overlay').addClass('active');


        setTimeout(function(){

          if( $('#popup-active-overlay').find('.bean-download-block').length > 0) {
            $('#popup-active-overlay').addClass('remove-bg-bean');
          }

          if($('#popup-active-overlay').find('.webform-client-form').length > 0 ) {

            $('#popup-active-overlay').addClass('popup-form');
          }

          if($('#popup-active-overlay').hasClass('popup-form') ||$('#popup-active-overlay').hasClass('remove-bg-bean') ){

          }else {
            $('#popup-active-overlay .block').css('padding','30px');
          }

        }, 1550);

      });



      $('.popup-close').on('click',function () {

        $('#popup-active-overlay').removeClass('active','remove-bg-bean');
      });
    }
  };


  Drupal.behaviors.tooltip= {
    attach: function (context, settings) {
      $('.study-area .field-name-field-course-icon img').attr("data-toggle","tooltip");
      $('.field-name-course-centre-block-grid img').attr("data-toggle","tooltip");

      $('[data-toggle="tooltip"]').tooltip();
    }
  };

  Drupal.behaviors.matchHeight= {
    attach: function (context, settings) {


        $('.paragraphs-item-2-3-1-3-grid .block .field-name-field-non-bold-text').matchHeight({ property: 'min-height' });

      $('.paragraphs-item-2-3-1-3-grid .block').matchHeight({ property: 'min-height' });

        $('.paragraphs-item-2-3-1-3 .block-left .block .field-name-field-non-bold-text').matchHeight({ property: 'min-height' });


    }
  };




})(jQuery);

