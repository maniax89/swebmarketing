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
                     var totalH = $(".navbar-wrapper.span9").offset().top;
                     var finalSize = totalH - vPos;
                     
                     console.log(finalSize);
                      if (finalSize <= 0) {
                        $('.navbar.notresp').addClass('fixed');
                      }
                      else{
                        $('.navbar.notresp').removeClass('fixed');
                      }
                    });
                   
               }(window.jQuery)
           });
       </script>
       <?php
   }
?>