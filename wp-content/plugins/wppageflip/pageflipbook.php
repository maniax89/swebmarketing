<?php
/*
Plugin Name: A Page Flip Book
Plugin Script: pageflipbook.php
Plugin URI: http://www.pageflipbook.com
Description: Create and manage your jQuery or Flash Page Flip : add pages, reorganize them and start browsing your book. Visit <a href='http://www.pageflipbook.com' target='_blank'>our website</a> !<br />
Version: 3.0
Author: Agence Web 360
Author URI: http://www.agence-web-360.com

Copyright 2008-2013  Agence Web 360  (email : support@agence-web-360.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ABSPATH=='') define('ABSPATH', substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'/wp-includes')));

define ('SITE_URL', get_option('siteurl') );
define ('PAGEFLIPBOOK_UPLOAD_DIR', ABSPATH . '/wp-content/plugins/wppageflip/');
define ('PAGEFLIPBOOK_UPLOAD_URL', SITE_URL . '/wp-content/plugins/wppageflip/');
define ('PAGEFLIPBOOK_ICONES_DIR', '../wp-content/plugins/wppageflip/images/');
define ('PAGEFLIPBOOK_PLUGIN_URL', SITE_URL . '/wp-content/plugins/wppageflip/');

require_once (ABSPATH.'wp-includes/pluggable.php');
include (PAGEFLIPBOOK_UPLOAD_DIR. 'functions.php');
include (PAGEFLIPBOOK_UPLOAD_DIR. 'quicktags.php');
if ((isset($_POST['pageflipbook_language'])) AND ($_POST['pageflipbook_language']!='')) {
	$pageflipbook_language = $_POST['pageflipbook_language'];
    $languages_directory = ABSPATH.'/wp-content/plugins/wppageflip/languages';
    $language_files = listdir($languages_directory);
	if (is_in_array($_POST['pageflipbook_language'], $language_files, TRUE)){
		update_option('pageflipbook_language', $pageflipbook_language);
	}
}
else
	$pageflipbook_language = get_option('pageflipbook_language');
if ($pageflipbook_language == '') $pageflipbook_language = 'EN_en.php';
include (PAGEFLIPBOOK_UPLOAD_DIR. 'languages/'. $pageflipbook_language);

$plugin_dir = ABSPATH.'wp-content/plugins/wppageflip/';

global $wpdb;
$wpdb->pageflip = $table_prefix . 'pageflipbook';

if (!mysql_table_exists($wpdb->pageflip)){
	pageflipbook_db_install($wpdb->pageflip);
}

function pageflipbook_db_install($table) {
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// Structure
	$sql_create_pageflip_table = "CREATE TABLE `". $table ."` (
	ID int(10) unsigned NOT NULL auto_increment,
	adresse varchar(255) NOT NULL default '',
	lien varchar(255) NOT NULL default '',
	detachable tinyint(1) NOT NULL default '0',
	is_double tinyint(1) NOT NULL default '0',
	is_preloaded tinyint(1) NOT NULL default '0',
	categorie varchar(50) NOT NULL default '',
	position int(50) NOT NULL default '0',
	PRIMARY KEY  (ID))";
	dbDelta($sql_create_pageflip_table);

	// Données
	$sql_insert_pageflip_data = "INSERT INTO `". $table."` (`ID`, `adresse`, `lien`, `detachable`, `is_double`, `is_preloaded`, `categorie`, `position`) VALUES
	(12, '1371423468.jpg', 'http://', 0, 0, 0, '', 15),
	(10, '1371423450-g.jpg', 'http://', 0, 0, 0, '', 9),
	(11, '1371423450-d.jpg', 'http://', 0, 0, 0, '', 10),
	(7, '1371423359-d.jpg', 'http://', 0, 0, 0, '', 6),
	(6, '1371423359-g.jpg', 'http://', 0, 0, 0, '', 5),
	(5, '1371423323-d.jpg', 'http://', 0, 0, 0, '', 4),
	(1, '1371423263.jpg', 'http://', 0, 0, 0, '', 0),
	(8, '1371423433-g.jpg', 'http://', 0, 0, 0, '', 13),
	(9, '1371423433-d.jpg', 'http://', 0, 0, 0, '', 14),
	(2, '1371423288-g.jpg', 'http://', 0, 0, 0, '', 1),
	(3, '1371423288-d.jpg', 'http://', 0, 0, 0, '', 2),
	(4, '1371423323-g.jpg', 'http://', 0, 0, 0, '', 3),
	(13, '1371423482.jpg', 'http://', 0, 0, 0, '', 16),
	(14, '1371423505-g.jpg', 'http://', 0, 0, 0, '', 7),
	(15, '1371423505-d.jpg', 'http://', 0, 0, 0, '', 8),
	(16, '1371423537-g.jpg', 'http://', 0, 0, 0, '', 11),
	(17, '1371423537-d.jpg', 'http://', 0, 0, 0, '', 12),
	(18, '1371423583.jpg', 'http://', 0, 0, 0, '', 17);";
	$wpdb->query($sql_insert_pageflip_data);
}

/* UPGRADE FROM OLD VERSIONS **************************************************************************************************/
/*
$temp_dir = '../wp-content/plugins/wppageflip/temp/';
if (is_dir($temp_dir)) {
	delTree($temp_dir);
}
*/

function pageflipbook_init() {
	$pageflipbook_options = array(
		'pageflip_background' => '#FFFFFF',
		'pageflip_hauteur' => '520',
		'pageflip_largeur' => '650',
		'pageflip_couvendur' => '1',
		'pageflip_preloads' => '3',
		'pageflip_redimensionner' => '1',
		'pageflip_doublepages' => '1',
		'pageflip_page_hauteur' => '400',
		'pageflip_page_largeur' => '300',
		'pageflip_transparence' => '0',
		'pageflip_bordure' => '2',
		'pageflip_version' => 'L_2.0',
		'pageflip_back' => 'huz6.jpg',
		'pageflip_mode' =>  'jquery',
		'jquery_menu_color'  => '#FFFFFF'
	);
	add_option('pageflipbook_options', $pageflipbook_options);
	add_option('pageflipbook_language', 'EN_en.php');
	if ( !is_writable( PAGEFLIPBOOK_UPLOAD_DIR. 'pages/' ) ) @chmod( PAGEFLIPBOOK_UPLOAD_DIR. 'pages/', 0775 );
	if ( !is_writable( PAGEFLIPBOOK_UPLOAD_DIR. 'thumbs/' ) ) @chmod( PAGEFLIPBOOK_UPLOAD_DIR. 'thumbs/', 0775 );
	if ( !is_writable( PAGEFLIPBOOK_UPLOAD_DIR. 'xml/' ) ) @chmod( PAGEFLIPBOOK_UPLOAD_DIR. 'xml/', 0775 );
}

function pageflipbook_admin_header() {
	echo'
<!-- PageFlip Book -->
<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/functions.js"></script>
<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/jscolor.js"></script>
<!-- PageFlip Book //-->
	';
}
	
function pageflipbook_header() {
echo'
<!-- PageFlip Book Lite -->
<script type="text/javascript" src="'. get_option('siteurl'). '/wp-content/plugins/wppageflip/js/swfobject.js"></script>
<!-- PageFlip Book Lite //-->
';
}
	
function pageflipbook_filter($content) {
	if(stristr($content,'[PageFlipBook_Lite]')) {
		$pageflipbook_code = affiche_livre();
		$html = explode('[PageFlipBook_Lite]', $content);
		$content = $html[0]. $pageflipbook_code . $html[1];
	}
	return $content;
}

function pageflipbook_plugin_more_links($links, $file) {
    if ( $file == 'wppageflip/pageflipbook.php' ) {
		$links[] = '<a href="http://www.pageflipbook.com/forums/page-flip-support/wordpress-pageflip-book-lite/" target="_blank">Support</a>';
		$links[] = '<a href="http://wordpress.org/support/view/plugin-reviews/wppageflip#postform" target="_blank">Rate this plugin</a>';
    }
    return $links;
}

function pageflipbook_menu() {
	$plugin_path = "wppageflip/";
	add_menu_page('Page Flip Book', 'Page Flip Book', 'activate_plugins', $plugin_path.'pageflipbook.php', 'pageflipbook_configuration', '../wp-content/plugins/wppageflip/images/book_open.png');
	add_submenu_page( $plugin_path.'pageflipbook.php','Images','Images','activate_plugins',$plugin_path.'display_page.php');
	add_submenu_page( $plugin_path.'pageflipbook.php','Support','Support','activate_plugins',$plugin_path.'upgrade.php');
}

function pageflipbook_submit_configuration(){
	if ((isset($_POST['pageflipbook_modif_config']) AND ($_POST['pageflipbook_modif_config'] == 'modif'))){
		foreach ($_POST AS $key => $val ){
			$pageflipbook_options[$key] = $_POST[$key];
		}
		return update_option( 'pageflipbook_options' , $pageflipbook_options );
	}
}

function pageflipbook_configuration() {
	global $wpdb, $wp_filesystem;
	if ( pageflipbook_submit_configuration() == TRUE ){
		generation_pageflipbook_xml();
		echo '<div class="updated"><p align="center">'. TXT_WPPF_NEWPARAMSSAVED .'</p></div>';
	}
	if ((isset($_GET['action'])) AND ($_GET['action']=='pageflipbook_turnjs_install')) {
		WP_filesystem();
		$turnjsarchive = 'https://github.com/blasten/turn.js/archive/master.zip';
		$upload_dir = wp_upload_dir();
		copy($turnjsarchive,  $upload_dir['basedir'].'/master.zip');
		$return = unzip_file( $upload_dir['basedir'].'/master.zip', $upload_dir['basedir'].'/' );
		if ( is_wp_error($return) )
			echo '<div class="updated"><p align="center">'. $return->get_error_message() .'</p></div>';
		copy($upload_dir['basedir'].'/turn.js-master/turn.min.js',  '../wp-content/plugins/wppageflip/js/turn.min.js');
		unlink( $upload_dir['basedir'].'/master.zip' );
		delTree($upload_dir['basedir'].'/turn.js-master/');
		echo '<div class="updated"><p align="center">'. TXT_WPPF_NEWPARAMSSAVED .'</p></div>';
	}
	$pageflipbook_options = get_option('pageflipbook_options');
	echo'
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div><h2>'. TXT_WPPF_CONFIGURATION .'</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="postbox-container-1" class="postbox-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox ">
					<h3 class="hndle">&nbsp;</h3>
					<div class="inside">
						<div id="minor-publishing-actions">
							<div class="misc-pub-section">	
								<p style="text-align:left;">
									<img src="../wp-content/plugins/wppageflip/images/cog_error.png">&nbsp;'. TXT_WPPF_DOUBLETEARWARNING .'
								</p>
							</div>
								<p style="text-align:left;">
<form method="post" name="pageflip_options" action="">
			<strong>'. TXT_WPPF_LANGUAGE .' :</strong>
			<select name="pageflipbook_language" onchange="submit();">';
        if(get_option('pageflipbook_language') != '') $pageflipbook_language = get_option('pageflipbook_language');
		if ( ABSPATH=='') define('ABSPATH', substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'/wp-includes')));
        $languages_directory = ABSPATH.'/wp-content/plugins/wppageflip/languages';
        $language_files = listdir($languages_directory);
        foreach($language_files as $language_file){
          switch($language_file){
            case "FR_fr.php";
            $language = "Fran&ccedil;ais";
            break;

            case "EN_en.php";
            $language = "English";
            break;
            
            case "DE_de.php";
            $language = "German";
            break;

            case "IT_it.php";
            $language = "Italian";
            break;
            
            case "PT_pt.php";
            $language = "Portuguese";
            break;
                      
            case "SP_sp.php";
            $language = "Spanish";
            break;

            case "ID_id.php";
            $language = "Indonesian";
            break;

            case "DA_da.php";
            $language = "Danish";
            break;

            case "NL_nl.php";
            $language = "Dutch";
            break;
			
            case "NO_no.php";
            $language = "Norwegian";
            break;
			
            case "SV_sv.php";
            $language = "Swedish";
            break;			
			
            default:
            continue 2;
            break;
          }
          if($pageflipbook_language == $language_file){
            echo '
				<option selected="true" value="'. $language_file .'"> '. $language .'</option>';
          }
          else{
            echo '
			    <option value="'. $language_file .'">'. $language .'</option>';            
           }
        }
	echo'
			</select>
	  </form>
								</p>
						</div>	
					</div>
				</div>
			</div>
		</div>
		<div class="postbox-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox ">
					<h3 class="hndle"><img src="../wp-content/plugins/wppageflip/images/book_open.png"> '. TXT_WPPF_MAINANIM .'</h3>
					<div class="inside">
						<p>

						
	  <form method="post" name="pageflip_options" action="">
	  <input type="hidden" name="pageflipbook_modif_config" value="modif">
	  <h2><input value="flash" name="pageflip_mode" ';
	  
	if ($pageflipbook_options['pageflip_mode']=='') $pageflipbook_options['pageflip_mode']='jquery';
	  
	if ($pageflipbook_options['pageflip_mode']=='flash') echo'checked="true" ';
	echo'type="radio" onclick="afficher(\'specifs_flash\');cacher(\'specifs_jquery\');"> Flash&nbsp;&nbsp;&nbsp;&nbsp;<input value="jquery" name="pageflip_mode" ';
	if ($pageflipbook_options['pageflip_mode']=='jquery') echo'checked="true" ';
	echo'type="radio" onclick="cacher(\'specifs_flash\');afficher(\'specifs_jquery\');"> jQuery </h2>
	  <table>
		  <tr>
			<td>'. TXT_WPPF_WIDTH .' (pixels) :</td>
			<td><input name="pageflip_largeur" value="'. $pageflipbook_options['pageflip_largeur'] .'" type="text">&nbsp;px</td>
	      </tr>
		  <tr>
			<td>'. TXT_WPPF_HEIGHT .' (pixels) :</td>
			<td><input name="pageflip_hauteur" value="'. $pageflipbook_options['pageflip_hauteur'] .'" type="text">&nbsp;px</td>
	      </tr>
	  	  <tr>
			<td colspan="2"><input value="0" name="pageflip_back" '; if ($pageflipbook_options['pageflip_back']=='0') echo'checked="true" '; echo'type="radio"> '. TXT_WPPF_BGTRANSP .'</td>
		  </tr>
	  	  <tr>
			<td><input value="1" name="pageflip_back" '; if ($pageflipbook_options['pageflip_back']=='1') echo'checked="true" '; echo'type="radio"> '. TXT_WPPF_BGCOLOR .'</td>
			<td>
				<table>
					<tr>
						<td><input class="color" name="pageflip_background" value="'. $pageflipbook_options['pageflip_background'] .'" type="text"></td>
					</tr>
				</table>
			</td>
		  </tr>
		  <tr>
			<td colspan="2">'. TXT_WPPF_OR_BGIMAGE .' :</td>
		  </tr>
		  <tr>
			<td colspan="2">
				<table>
					<tr>
						<td>
							<ul>';
$bgDirectory = ABSPATH.'/wp-content/plugins/wppageflip/images/backgrounds';
$bgFiles = listdir($bgDirectory);
foreach($bgFiles as $bgFile){
	echo '
	<li style="float:left; background-color:#E0E0E0; margin-right:5px;">
		<table><tr>
			<td><input value="'. $bgFile .'" name="pageflip_back" '; if ($pageflipbook_options['pageflip_back']==$bgFile) echo'checked="true" '; echo'type="radio"></td>
			<td><img src="../wp-content/plugins/wppageflip/images/backgrounds/'. $bgFile .'" width="100" height="75"></td>
		</tr></table>
	</li>
	';
}
echo '
							</ul>
						</td>
					</tr>
				</table>
			</td>
		  </tr>
	  </table>
	  <br />
	  <div id="specifs_flash"';
	  if ($pageflipbook_options['pageflip_mode']!='flash') echo' style="display:none;"';
	  echo '>
	  <table>
		  <tr>
			<td colspan="3">&nbsp;</td>
	      </tr>	  
	  	  <tr>
			<td>'. TXT_WPPF_TRANSPARENCY .' :</td>
			<td colspan="2">
				<input value="1" name="pageflip_transparence" ';
	if ($pageflipbook_options['pageflip_transparence']==1) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_YES .'
				<input value="0" name="pageflip_transparence" ';
	if ($pageflipbook_options['pageflip_transparence']==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
			</td>
	      </tr>
		  <tr>
			<td>'. TXT_WPPF_NUMPRELOADS .' :</td>
			<td colspan="2"><input name="pageflip_preloads" value="'.$pageflipbook_options['pageflip_preloads'].'" type="text"></td>
	      </tr>
	  	  <tr>
			<td>'. TXT_WPPF_HARDCOVER .' :</td>
			<td>
				<input value="1" name="pageflip_couvendur" ';
	if ($pageflipbook_options['pageflip_couvendur']==1) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_YES .'
				<input value="0" name="pageflip_couvendur" ';
	if ($pageflipbook_options['pageflip_couvendur']==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
			</td>
	      </tr>
	  	  <tr>
			<td>'. TXT_WPPF_BUTTONSCOLOR .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
	      </tr>
		  <tr>
			<td>'. TXT_WPPF_JUMPTOTARGET .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
	      </tr>
	  	  <tr>
			<td>'. TXT_WPPF_CATSON .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
	      </tr>
	  </table>
	  </div>
	  <div id="specifs_jquery"';
	  	  if ($pageflipbook_options['pageflip_mode']!='jquery') echo' style="display:none;"';
	  echo '>';

	  if (!file_exists('../wp-content/plugins/wppageflip/js/turn.min.js')) {
	  echo '
	  <div id="warning">
		'. TXT_WPPF_TURNJS_NEEDED .'<br />
		<center><a href="?page=wppageflip/pageflipbook.php&action=pageflipbook_turnjs_install">CONTINUE &raquo;</a></center><br />
		<br />
	  </div>';
	  }
	  echo '
	  <table>
		  <tr>
			<td>'. TXT_WPPF_JQUERY_MENU_COLOR .' :</td>
			<td><input class="color" name="jquery_menu_color" value="'. $pageflipbook_options['jquery_menu_color'] .'" type="text"></td>
	      </tr>
		  <tr>
			<td>'. TXT_WPPF_PLAYSOUND .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
		  </tr>
		  <tr>
			<td>'. TXT_WPPF_STARTAUTOPLAY .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
		  </tr>
		  <tr>
			<td>'. TXT_WPPF_AUTOPLAYDELAY .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
	      </tr>
	  </table>
	  </div>
						</p>
					</div>
				</div>
			</div>
		</div>
	
		<div class="postbox-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox ">
					<h3 class="hndle"><img src="../wp-content/plugins/wppageflip/images/image.png"> '. TXT_WPPF_PAGESANIM .'</h3>
					<div class="inside">
						<p>
	  <table>
		  <tr>
			<td>'. TXT_WPPF_WIDTH .' (pixels) :</td>
			<td><input name="pageflip_page_largeur" value="'. $pageflipbook_options['pageflip_page_largeur'] .'" type="text">px</td>
	      </tr>
		  <tr>
			<td>'. TXT_WPPF_HEIGHT .' (pixels) :</td>
			<td><input name="pageflip_page_hauteur" value="'. $pageflipbook_options['pageflip_page_hauteur'] .'" type="text">px</td>
	      </tr>
	  	  <tr>
			<td>'. TXT_WPPF_AUTORESIZE .' :</td>
			<td>
				<input value="1" name="pageflip_redimensionner" ';
	if ($pageflipbook_options['pageflip_redimensionner']==1) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_YES .'
				<input value="0" name="pageflip_redimensionner" ';
	if ($pageflipbook_options['pageflip_redimensionner']==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
			</td>
	      </tr>
	  	  <tr>
			<td>'. TXT_WPPF_AUTOBORDER .' :</td>
			<td><input name="pageflip_bordure" value="'. $pageflipbook_options['pageflip_bordure'] .'" type="text"> px</td>
	      </tr>
		  <tr>
			<td>'. TXT_WPPF_AUTOBORDERCOLOR .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
	      </tr>
	  	  <tr>
			<td>'. TXT_WPPF_DEFAULTDOUBLEPAGES .' :</td>
			<td>
				<input value="1" name="pageflip_doublepages" ';
	if ($pageflipbook_options['pageflip_doublepages']==1) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_YES .'
				<input value="0" name="pageflip_doublepages" ';
	if ($pageflipbook_options['pageflip_doublepages']==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
			</td>
	      </tr>
		  <tr>
			<td>'. TXT_WPPF_IMAGESLIBRARY .' :</td>
			<td>'. TXT_WPPF_PLEASE_UPGRADE .'</td>
		  </tr>
	  </table>
	  <br /><br />
	  <input type="submit" name="save_conf" value="'. TXT_WPPF_SAVE .' &raquo;"  class="button-primary submit" id="save_conf">
      </form>
						</p>
					</div>
				</div>
			</div>
		</div>
	
		<div class="postbox-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox ">
					<h3 class="hndle"><a onclick="switchMenu(\'tech_spec\');"><img src="../wp-content/plugins/wppageflip/images/bullet_wrench.png"> TECH SPEC</a></h3>
					<div class="inside">
						<p>
	  <div id="tech_spec" style="display:none;">';
	
	if (function_exists('memory_get_usage')) $memUse = round(memory_get_usage()/1024/1024, 2) . ' MByte';
	else $memUse = 'N/A';
	if(ini_get('safe_mode')) $safeMode = 'On';
	else $safeMode = '<b>Off</b>';
	if( ini_get( 'memory_limit' ) ) $memLimit = ini_get('memory_limit');
	else $memLimit = 'N/A';	
	if(ini_get('upload_max_filesize')) $upMax = ini_get('upload_max_filesize');
	else $upMax = 'N/A';
	if(ini_get('max_execution_time')) $maxTime = ini_get('max_execution_time') . ' secs';
	else $maxTime = 'N/A';
	if (function_exists('gd_info')) {
		$gdInfo = gd_info();
		$gdVersion = $gdInfo['GD Version'];
	}
	else
		$gdVersion = 'N/A';
	if( ini_get('allow_url_fopen')) $allowUrlFopen = 'On';
	else $allowUrlFopen = '<b>Off</b>';
	
echo '
		<table>
			<tr>
				<td>OS :</td><td>' . php_uname() . '</td>
			</tr>
			<tr>
				<td>Server software :</td><td>' . $_SERVER["SERVER_SOFTWARE"] . '</td>
			</tr>
			<tr>
				<td>SQL :</td><td>' . $wpdb->get_var( "SELECT VERSION() AS version" ) . '</td>
			</tr>
			<tr>
				<td>PHP :</td><td>' . PHP_VERSION . '</td>
			</tr>
			<tr>
				<td>Memory Get Usage :</td><td>' . $memUse . '</td>
			</tr>
			<tr>
				<td>Safe Mode :</td><td>' . $safeMode . '</td>
			</tr>
			<tr>
				<td>Memory Limit :</td><td>' . $memLimit . '</td>
			</tr>
			<tr>
				<td>Upload Max Filesize :</td><td>' . $upMax . '</td>
			</tr>
			<tr>
				<td>Max execution time :</td><td>' . $maxTime . '</td>
			</tr>
			<tr>
				<td>GD Version :</td><td>' . $gdVersion . '</td>
			</tr>
			<tr>
				<td>Allow URL Fopen :</td><td>' . $allowUrlFopen . '</td>
			</tr>			
		</table>
	  </div>
						</p>
					</div>
				</div>
			</div>
		</div>

	</div>
	<br class="clear">	
	</div>
</div>
	';
}

add_filter('plugin_action_links', 'pageflipbook_plugin_more_links', 10, 2);
add_filter('the_content', 'pageflipbook_filter');

add_action('plugins_loaded', 'pageflipbook_init');
add_action('wp_head', 'pageflipbook_header');
add_action('admin_head', 'pageflipbook_admin_header');
if ( is_admin() ) {
	add_action('admin_menu', 'pageflipbook_menu');
}
?>