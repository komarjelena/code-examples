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
  // /* HEADER SEARCH */
  // /* -- add in descriptive text to search box
  // function headersearch(){
  //   var searchbox = $("#id-form-text-input-element");
  //   var searchtext = "Search";
  //   var submitbutton = $('#id-of-submit-form-button');
  //
  //   searchbox.attr("value", searchtext);
  //
  //   searchbox.focus(function(){
  //     if(jQuery.trim($(this).attr("value")) == searchtext) $(this).attr("value", "");
  //   });
  //
  //   searchbox.blur(function(){
  //     if(jQuery.trim($(this).attr("value")) == "") $(this).attr("value", searchtext);
  //   });
  //
  //   submitbutton.click(function(){
  //     if (searchbox.val() == searchtext) searchbox.attr("value", "");
  //   });
  //
  // }
  // headersearch();
  // */
  // function popupShare(){
  //   $("a.ssl").click(function(){
  //     //$(this).hide();
  //     var url = $(this).attr('href');
  //     var windowWidth = $(window).width();
  //     var windowHeight = $(window).height();
  //     var windowLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
  //     var windowTop = window.screenTop != undefined ? window.screenTop : screen.top;
  //     //var screenLeft = screen.left;
  //     var width = 450;
  //     var left = windowWidth / 2 - width / 2 + windowLeft;
  //     var height = 300;
  //     var top = windowHeight / 2 - height / 2 + windowTop;
  //     window.open(url, "popupWindow", "scrollbars=no,resizable=1,height=" + height + ",width=" + width + ",top=" + top + ",left=" + left);
  //     return false;
  //   });
  // }
  // popupShare();
  //
  // function addClassLastOfType(){
  //   $('.node-qualification h3:last-of-type').addClass('last-child');
  // } addClassLastOfType();
  //
  //
  // function equalHeightOfTextField(class1, class2, noSpace){
  //   //make the function work with argument so we can re-use it
  //   // asign panel and elem variables
  //   var panel = $(class1);
  //   var elem = $(class2);
  //   // for each panel function
  //   panel.each(function(){
  //     // make a new array to put our heights into
  //     var heightArray = [];
  //     var spaceArray = [];
  //
  //     //find each elem and assign it a variable so we can apply heights later
  //     var column = $(this).find(elem);
  //
  //     // for every column we get, push it into our array
  //     column.each(function(){
  //       //get the height
  //       var columnHeight = $(this).height();
  //       //push height to array
  //       heightArray.push(columnHeight);
  //
  //       var paddingTop = parseInt($(this).css('padding-top'), 10);
  //       var paddingBottom = parseInt($(this).css('padding-bottom'), 10);
  //       //get both top and bottom padding
  //       var paddingVertical = paddingTop + paddingBottom;
  //       //push vertical padding to array
  //       spaceArray.push(paddingVertical);
  //     });
  //     //console.log(heightArray);
  //     //console.log(spaceArray);
  //     //console.log(columnHeight);
  //
  //     //eg would return [num,num,num]
  //     //from the array find the largest number
  //     var colHeight = Math.max.apply(Math, heightArray);
  //     var colPaddingVertical = Math.max.apply(Math, spaceArray);
  //     //console.log(colHeight);
  //
  //     //apply the largest height from the array to every column in the panel + spacing
  //
  //     if(noSpace == 'noSpace'){
  //       column.css('min-height', colHeight);
  //     } else {
  //       column.css('min-height', colHeight + colPaddingVertical);
  //     }
  //   });
  // }
  //
  // equalHeightOfTextField('.front .region-content-bottom', '.block-views', 'Space');
  // equalHeightOfTextField('.front .region-content-top', '.block', 'noSpace');
  // //equalHeightOfTextField('.view-course-icon-front-block', '.views-row', 'noSpace');
  //
  // function movingFilter(){
  //   $('.region-content').before('<div id="content-top"></div>');
  //   $('#content-top').append('<div class="region-content-top"></div>');
  //
  //   if(($(window).width()) < 991 ){
  //
  //     $(".region-content-top").append($("#block-block-filters-filters"));
  //   }
  //
  //   $(window).resize(function() {
  //
  //     var responsive_viewport = $(window).width();
  //
  //     //console.log(responsive_viewport);
  //     var moving_div = $("#block-block-filters-filters");
  //     if (responsive_viewport > 991) {
  //       moving_div.clone().remove();
  //       //$(moving_div).prependTo(".region-sidebar-second");
  //       $("#block-menu-block-3").after(moving_div);
  //     } else {
  //       moving_div.clone().remove();
  //       //$(moving_div).appendTo(".region-content-top");
  //       $(".region-content-top").append(moving_div);
  //     }
  //   });
  // }
  // movingFilter();




})(jQuery);


//# sourceMappingURL=../js/main.js.map
