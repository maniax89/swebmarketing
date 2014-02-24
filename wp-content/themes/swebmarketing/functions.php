<?php

   remove_filter( 'the_content', 'wpautop' );
   remove_filter( 'the_excerpt', 'wpautop' );

   wp_enqueue_style('book_block_css', get_stylesheet_directory_uri() . "/css/bookblock.css");
   wp_enqueue_style('book_block_css', get_stylesheet_directory_uri() . "/css/default.css");
   
   wp_enqueue_script('jquery');
   wp_enqueue_script('iscroll', get_stylesheet_directory_uri() . "/js/iscroll-lite.js");
   wp_enqueue_script('modernizr', get_stylesheet_directory_uri() . "/js/modernizr.custom.js", array('jquery'));
   //wp_enqueue_script('jquerypp', get_stylesheet_directory_uri() . "/js/jquerypp.custom.js", array('modernizr','jquery'));
   wp_enqueue_script('book_block', get_stylesheet_directory_uri() . "/js/bookblock.js", array('jquerypp','modernizr','jquery'));
   wp_enqueue_script('jq_book_block', get_stylesheet_directory_uri() . "/js/jquery.bookblock.js", array('jquerypp','modernizr','jquery'));
   
   
   add_action('wp_footer' , 'add_nav_scroll_fixing');
   function add_nav_scroll_fixing() {
       ?>
       <script type="text/javascript">
           jQuery(document).ready(function () {
            
            

            //var myScroll = new IScroll('#main-content');
            

               ! function ($) {
                   //prevents js conflicts
                   "use strict";
                   $(window).scroll(function () {
                     var vPos = $(window).scrollTop();
                     var navH = $(".navbar-wrapper.span9").offset().top;
                     var navSize = navH - vPos;
                     
                      if (navSize <= 0) {
                        $('.navbar.notresp').addClass('fixed');
                        $(".tc-header .brand a.site-logo").addClass('fixed');
                      }
                      else{
                        $('.navbar.notresp').removeClass('fixed');
                        $(".tc-header .brand a.site-logo").removeClass('fixed');
                      }
                    });
                   
                   $("a.round-div").attr("href","#").click(function(){
                     return false;
                   })
                   
                  $("#menu-main-menu a").on("click", function (){
                     var elementID = $(this).attr("href");
                     console.log(elementID);
                     var element = $(elementID);
                     console.log(element);
                     $("html, body").animate({
                        scrollTop: element.offset().top }, "slow");
                     return false;
                  });
                  
                  $(".site-logo, .back-to-top").on("click", function () {
                     $("html, body").animate({
                        scrollTop: 0 }, "slow");
                     return false;
                  });
                   
               }(window.jQuery)
           });
       </script>
       <?php
   }
   
   add_action('wp_footer' , 'add_booklet');
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