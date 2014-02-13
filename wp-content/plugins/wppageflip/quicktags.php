<?php
/* TINY MCE ************************************************************************************************************************/
function addPageFlipBook_edButton() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
   if ( get_user_option('rich_editing') == 'true') {
     add_filter('mce_external_plugins', 'add_pageflipbook_tinymce_plugin');
     add_filter('mce_buttons', 'register_pageflipbook_button');
   }
}

function register_pageflipbook_button($buttons) {
   array_push($buttons, "|", "pageflipbook");
   return $buttons;
}

function add_pageflipbook_tinymce_plugin($plugin_array) {
   $plugin_array['pageflipbook'] = get_option('siteurl').'/wp-content/plugins/wppageflip/js/editor_plugin.js';
   return $plugin_array;
}

function my_refresh_mce($ver) {
  $ver += 3;
  return $ver;
}
add_filter( 'tiny_mce_version', 'my_refresh_mce');
add_action('init', 'addPageFlipBook_edButton');
?>