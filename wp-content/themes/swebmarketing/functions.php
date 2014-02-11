<?php
   add_action('wp_footer' , 'add_nav_scroll_fixing');
   function add_nav_scroll_fixing() {
       ?>
       <script type="text/javascript">
           jQuery(document).ready(function () {
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
                   
               }(window.jQuery)
           });
       </script>
       <?php
   }
?>