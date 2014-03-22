<?php

   remove_filter( 'the_content', 'wpautop' );
   remove_filter( 'the_excerpt', 'wpautop' );

   wp_enqueue_style('book_block_css', get_stylesheet_directory_uri() . "/css/bookblock.css");
   wp_enqueue_style('book_block_css', get_stylesheet_directory_uri() . "/css/default.css");
   wp_enqueue_style('magra-font', 'http://fonts.googleapis.com/css?family=Magra:400,700');
   
   wp_enqueue_script('jquery');
   wp_enqueue_script('raf', get_stylesheet_directory_uri() . "/js/Raf.js");
   wp_enqueue_script('animate', get_stylesheet_directory_uri() . "/js/Animate.js", array('raf'));
   wp_enqueue_script('scroller', get_stylesheet_directory_uri() . "/js/Scroller.js", array('raf','animate'));
   wp_enqueue_script('easyscroller', get_stylesheet_directory_uri() . "/js/EasyScroller.js", array('raf','animate','scroller'));
   wp_enqueue_script('modernizr', get_stylesheet_directory_uri() . "/js/modernizr.custom.js", array('jquery'));
   //wp_enqueue_script('jquerypp', get_stylesheet_directory_uri() . "/js/jquerypp.custom.js", array('modernizr','jquery'));
   wp_enqueue_script('book_block', get_stylesheet_directory_uri() . "/js/bookblock.js", array('jquerypp','modernizr','jquery'));
   wp_enqueue_script('jq_book_block', get_stylesheet_directory_uri() . "/js/jquery.bookblock.js", array('jquerypp','modernizr','jquery'));
   
   
   add_action('wp_footer' , 'add_nav_scroll_fixing');
   add_action('wp_footer' , 'add_google_analytics');
   add_filter( 'grunion_contact_form_success_message', 'change_grunion_success_message' );
   
   function add_nav_scroll_fixing() {
       ?>
       <script type="text/javascript">
            
                  
           jQuery(document).ready(function () {
            
               ! function ($) {
                  //prevents js conflicts
                  "use strict";
                  
                  //initialize logo as visible
                  $(".tc-header .brand a.site-logo").addClass("visible");
                  
                  //var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );
                  //if (iOS) {                     
                  //   var element = document.querySelector(".entry-content");
                  //   var easyScroll = new EasyScroller(element,{scrollingY:true,scrollingX:false});
                  //}

                  $(window).on('scroll', updatePosition);
                  //$(window).on('gesturechange', updatePosition);
                  //$(window).on('touchmove', updatePosition);
                   
                   $("a.round-div").attr("href","#").click(function(){
                     return false;
                   });
                   
                  $("#menu-main-menu a, #menu-main-menu-1 a").on("click", function (e){
                     e.preventDefault();
                     var elementID = $(this).attr("href");
                     var element = $(elementID);
                     console.log(element.offset().top);
                     $("html, body").animate({
                        scrollTop: (element.offset().top - 75) }, "slow");
                     return false;
                  });
                  
                  $(".site-logo, .back-to-top").on("click", function () {
                     $("html, body").animate({
                        scrollTop: -55 }, "slow");
                     return false;
                  });
                                    
               
            }(window.jQuery)
         });
           
         function updatePosition(){
                  var vPos = jQuery(window).scrollTop();
                  var windowH = jQuery(window).height();
                  var navH = jQuery(".navbar-wrapper.span9").offset().top;
                  var navSize = navH - vPos;
                  
                  if (navSize <= 0) {
                     jQuery('.navbar.notresp').addClass('fixed');
                     jQuery(".tc-header .brand a.site-logo").addClass('fixed').removeClass("visible");
                  }
                  else{
                     jQuery('.navbar.notresp').removeClass('fixed');
                     jQuery(".tc-header .brand a.site-logo").removeClass('fixed').addClass("notransition");
                     setTimeout(function(){
                        jQuery(".tc-header .brand a.site-logo").removeClass('notransition').addClass('visible');
                     }, 700);
                  }
                   
                  //make images come into screen
                  jQuery(".slide-in, .scale-animate").each(function(){
                     var itemTop = jQuery(this).offset().top;
                     if (itemTop <= (vPos + windowH - 150)) {
                        if (!jQuery(this).hasClass("visible")) {
                           jQuery(this).addClass("visible");
                        }
                     }
                  });
            }
       </script>
       <?php
   }
   
   function add_google_analytics(){
      ?>
         <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
          
            ga('create', 'UA-48342159-1', 'swebmarketing.com');
            ga('send', 'pageview');
          
          </script>
      <?php
   }
   
   function change_grunion_success_message( $msg ) {
      global $contact_form_message;
      $customSuccess = "Your message has been received.\rGet ready to Sweb.";
      return '<div class="contact-form"><div><textarea>' . $customSuccess . '</textarea></div></div>';
   }
   
   //add_action('wp_footer' , 'add_booklet');
   function add_booklet() {
   ?>
         <script type="text/javascript">
//            jQuery(document).ready(function(){
//               ! function ($) {
//                   //prevents js conflicts
//                  
//               var Page = (function() {
//                       
//                       var config = {
//                                       $bookBlock : $( '#bb-bookblock' ),
//                                       $navNext : $( '#bb-nav-next' ),
//                                       $navPrev : $( '#bb-nav-prev' ),
//                                       $navFirst : $( '#bb-nav-first' ),
//                                       $navLast : $( '#bb-nav-last' )
//                               },
//                               init = function() {
//                                       config.$bookBlock.bookblock( {
//                                               speed : 800,
//                                               shadowSides : 0.8,
//                                               shadowFlip : 0.7
//                                       } );
//                                       initEvents();
//                               },
//                               initEvents = function() {
//                                       
//                                       var $slides = config.$bookBlock.children();
//
//                                       // add navigation events
//                                       config.$navNext.on( 'click touchstart', function() {
//                                               config.$bookBlock.bookblock( 'next' );
//                                               return false;
//                                       } );
//
//                                       config.$navPrev.on( 'click touchstart', function() {
//                                               config.$bookBlock.bookblock( 'prev' );
//                                               return false;
//                                       } );
//
//                                       config.$navFirst.on( 'click touchstart', function() {
//                                               config.$bookBlock.bookblock( 'first' );
//                                               return false;
//                                       } );
//
//                                       config.$navLast.on( 'click touchstart', function() {
//                                               config.$bookBlock.bookblock( 'last' );
//                                               return false;
//                                       } );
//                                       
//                                       // add swipe events
//                                       $slides.on( {
//                                               'swipeleft' : function( event ) {
//                                                       config.$bookBlock.bookblock( 'next' );
//                                                       return false;
//                                               },
//                                               'swiperight' : function( event ) {
//                                                       config.$bookBlock.bookblock( 'prev' );
//                                                       return false;
//                                               }
//                                       } );
//
//                                       // add keyboard events
//                                       $( document ).keydown( function(e) {
//                                               var keyCode = e.keyCode || e.which,
//                                                       arrow = {
//                                                               left : 37,
//                                                               up : 38,
//                                                               right : 39,
//                                                               down : 40
//                                                       };
//
//                                               switch (keyCode) {
//                                                       case arrow.left:
//                                                               config.$bookBlock.bookblock( 'prev' );
//                                                               break;
//                                                       case arrow.right:
//                                                               config.$bookBlock.bookblock( 'next' );
//                                                               break;
//                                               }
//                                       } );
//                               };
//
//                               return { init : init };
//                              
//
//			})();
//               
//               Page.init();
//		
//               }(window.jQuery)
//            });
            
         </script>
   <?php
   }
?>