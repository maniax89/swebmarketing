<?php
if (!function_exists('mysql_table_exists')){
	function mysql_table_exists($table) {
		return mysql_num_rows(mysql_query('SHOW TABLES LIKE \''.$table.'\''));
	}
}

if (!function_exists('mysql_column_exists')){
	function mysql_column_exists ($table,$column) {
		$result = mysql_query('select * from '.$table);
		if (!$result) return FALSE;
		$i = 0;
		$return = FALSE;
		while ($i < mysql_num_fields($result)) {
			$meta = mysql_fetch_field($result, $i);
			if ($meta->name == $column)
				$return = TRUE;
			$i++;
		}
		return $return;
	}
}

if (!function_exists('upload')){
	function upload($file,$dir) {
		$tmp_file = $file['tmp_name'];
	    if( !is_uploaded_file($tmp_file) ){
	        exit("Le fichier est introuvable");
	    }
	    $name_file = strtolower($file['name']);	
	    if( !move_uploaded_file($tmp_file, $dir . $name_file) ){
	        exit("Impossible de copier le fichier dans $dir");
	    }
	}
}

if (!function_exists('extension')){
	function extension ($nom_fichier){
		$tab_nom_fichier = explode ('.',$nom_fichier);
		return $tab_nom_fichier[1];
	}
}	

if (!function_exists('random')){	
	function random($nb_car) {
		$string = "";
		$chaine = "abcdefghijklmnpqrstuvwxy0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*1000000);
		for($i=0; $i<$nb_car; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	} 
}	

if (!function_exists('remote_file_exists')){
	function remote_file_exists ($url){
		ini_set('allow_url_fopen', '1');
		if (@fclose(@fopen($url, 'r'))) {
		    return true;
		} else {
	    	return false;
		}
	}
}

if (!function_exists('listdir')){
	function listdir($dirname){
		$dir = @opendir($dirname);
	    $num = 0;
	    while(($file = @readdir($dir)) !== false){
	       if(($file != "..") && ($file != ".") && !stristr($file, "~") && !( strpos($file, ".") === 0 )){
	         $dirlist[$num] = $file;
	         $num++;
	         }
	       }
	    return $dirlist; 
	}
}

if (!function_exists('is_in_array')){
	function is_in_array($val, $tab, $i=FALSE){
		$return = FALSE;
		if ($i == FALSE){
			foreach ($tab AS $elem)
				if ($elem == $val) $return = TRUE;
		}
		else {
			foreach ($tab AS $indice => $elem)
				if ($elem == $val) $return = TRUE;	
		}	
		return $return;
	}
}

if (!function_exists('img2thumb')){
	function img2thumb($img,$width,$height,$type,$dossier,$nom_thumb) {
		if($type == "image/jpeg") {
			$img_in = imagecreatefromjpeg($dossier.$img);
		}
		if($type == "image/pjpeg") {
			$img_in = imagecreatefromjpeg($dossier.$img);
		}
		$img_out = imagecreatetruecolor($width, $height);
		imagecopyresampled($img_out, $img_in, 0, 0, 0, 0, $width, $height, imagesx($img_in), imagesy($img_in));
		imagejpeg($img_out,$dossier.$nom_thumb);
		imagedestroy($img_out);
		return true;
	}
}

if (!function_exists('bordure_image')){
	function bordure_image($dossier,$img,$epaisseur){
		$img_in = imagecreatefromjpeg($dossier.$img);
		list($width_max, $height_max, $type_img, $attr_img) = getimagesize($dossier.$img);
		$img_out = imagecreatetruecolor($width_max, $height_max);
		$couleur = imagecolorallocate ($img_out,191,191,191);
		imagecopy($img_out, $img_in, 0, 0, 0, 0, $width_max, $height_max);
		for($i=0; $i<$epaisseur; $i++) {
			imagerectangle($img_out, $i, $i, $width_max-($i+1), $height_max-($i+1), $couleur); 
		}
		imagejpeg($img_out,$dossier.$img);
		imagedestroy($img_out);
		return true;
	}
}

if (!function_exists('image_double')){
	function image_double($dossier,$img){
		$img_in = imagecreatefromjpeg($dossier.$img);
		list($width_max, $height_max, $type_img, $attr_img) = getimagesize($dossier.$img);
		$nom_img_gauche = str_replace ( '.' , '-g.', $img );
		$img_gauche = imagecreatetruecolor($width_max/2, $height_max);
		imagecopy($img_gauche, $img_in, 0, 0, 0, 0, $width_max, $height_max);
		imagejpeg($img_gauche,$dossier.$nom_img_gauche);
		imagedestroy($img_gauche);
		$nom_img_droite = str_replace ( '.' , '-d.', $img );
		$img_droite = imagecreatetruecolor($width_max/2, $height_max);
		imagecopy($img_droite, $img_in, 0, 0, $width_max/2, 0, $width_max, $height_max);
		imagejpeg($img_droite,$dossier.$nom_img_droite);
		imagedestroy($img_droite);
		unlink($dossier.$img);
		return true;
	}
}

if (!function_exists('imgcopy')){
	function imgcopy($source,$dossier,$cible, $type) {
		if($type == "image/jpeg") {
			$img_in = imagecreatefromjpeg($dossier.$source);
		}
		if($type == "image/pjpeg") {
			$img_in = imagecreatefromjpeg($dossier.$source);
		}
		list($width, $height, $t, $a) = getimagesize($dossier.$source);
		$img_out = imagecreatetruecolor($width, $height);
		imagecopy($img_out, $img_in, 0, 0, 0, 0, $width, $height);
		imagejpeg($img_out,$dossier.$cible);
		imagedestroy($img_out);
		return true;
	}
}

if (!function_exists('delTree')){
	function delTree($dir) {
		$files = glob( $dir . '*', GLOB_MARK );
		foreach( $files as $file ){
			if( substr( $file, -1 ) == '/' )
				delTree( $file );
			else
				unlink( $file );
		}
		rmdir( $dir );
	}
}	

/* SPECIFIC *************************************************************************************************************************/
if (!function_exists('generation_pageflipbook_xml')){
	function generation_pageflipbook_xml() {
		global $wpdb;
		$pageflipbook_options = get_option('pageflipbook_options');
		$xml = PAGEFLIPBOOK_UPLOAD_DIR . 'xml/pageflipbook.xml';
		$num_page=1;
		if ($pageflipbook_options['pageflip_couvendur']==1) $pageflip_hcover = 'hcover="true" '; else $pageflip_hcover = '';
		if ($pageflipbook_options['pageflip_transparence']==1) $pageflip_transparency = 'transparency="true" '; else $pageflip_transparency = '';
		$pageflip_xml = '<content width="'. $pageflipbook_options['pageflip_page_largeur'] .'" height="'. $pageflipbook_options['pageflip_page_hauteur'] .'" '. $pageflip_hcover .$pageflip_transparency .'>';
		$pages_data = $wpdb->get_results("SELECT * FROM `".$wpdb->pageflip."` ORDER BY `position` ASC",ARRAY_A);
		foreach((array)$pages_data as $page) {
			$page_is_preloaded = '';
			$page_is_detachable = '';
			$page_is_double = '';
			if (($page['is_preloaded']==1)||($num_page <= $pageflipbook_options['pageflip_preloads'])) $page_is_preloaded = ' preLoad="true" ';
			else $page_is_preloaded = ' preLoad="false" ';
			if ($page['is_preloaded']==1) $page_is_preloaded = ' preLoad="true" ';
			if ($page['detachable']==1) $page_is_detachable = ' canTear="true" ';
			if ($page['is_double']==1) $page_is_double  = ' isSpread="true" ';
			$page_xml ='
<page src="'. PAGEFLIPBOOK_PLUGIN_URL .'pages/'. $page['adresse'] .'"'. $page_is_preloaded . $page_is_detachable . $page_is_double .'/>';
			if ($page['is_double']==1) $page_xml .= $page_xml;
			$pageflip_xml .= $page_xml;
			$num_page ++;
		}
		$pageflip_xml .= '</content>';
		if (remote_file_exists($xml)) unlink($xml);
		$fp = fopen($xml, 'a+');
		fputs ($fp,$pageflip_xml);
		fclose ($fp);
	}
}

if (!function_exists('supprime_image')){
	function supprime_image ($id){
		global $wpdb;
		if ( ABSPATH=='') define('ABSPATH', substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'/wp-includes')));
		$page_adresse = $wpdb->get_var('SELECT adresse FROM '. $wpdb->pageflip .' WHERE ID='. $id);
		@unlink(ABSPATH .'wp-content/plugins/wppageflip/pages/'. $page_adresse);
		@unlink(ABSPATH .'wp-content/plugins/wppageflip/thumbs/'. $page_adresse);
		$pageflip_supp = 'DELETE FROM '. $wpdb->pageflip .' WHERE ID='. $id .' LIMIT 1';
		$wpdb->query($pageflip_supp);
	}
}

if (!function_exists('affiche_livre')){
	function affiche_livre() {
		global $wpdb;
		$plugin_path = get_option('siteurl') ."/wp-content/plugins/wppageflip/";
		require_once 'mobile_detect.php';
		$pageflipbook_options = get_option('pageflipbook_options');
		$detect = new Mobile_Detect;
		if ( ($detect->isMobile()) || ($detect->isiOS()) ) $pageflipbook_options['pageflip_mode'] = "jquery";
		
		if ($pageflipbook_options['pageflip_mode'] == 'flash') {
			$flash_script = $plugin_path . 'swf/book.swf';
			$xml_url = PAGEFLIPBOOK_UPLOAD_URL . 'xml/pageflipbook.xml';
			if ($pageflipbook_options['pageflip_back']=='0'){
				$image_background = '';
				$couleur_background = '';
				$wmode = 'so.addParam("wmode", "transparent");';
			}		
			elseif ($pageflipbook_options['pageflip_back']=='1'){
				$image_background = '';
				$couleur_background = ', "'. $pageflipbook_options['pageflip_background'] .'"';
				$wmode = '';
			}
			else {
				$image_background = "background-image:url('". $plugin_path ."images/backgrounds/". $pageflipbook_options['pageflip_back'] ."');";
				$couleur_background = '';
				$wmode = 'so.addParam("wmode", "transparent");';
			}	
			$html='
			<div id="pageflipbookContent" style="width:'.$pageflipbook_options['pageflip_largeur'].'px; height: '.$pageflipbook_options['pageflip_hauteur'].'px; '. $image_background .'">
				<h4>Page Flip - A Page Flip Book</h4>
				<p>'. TXT_WPPF_MADEWITH .' <a href="http://www.pageflipbook.com" alt="Plugin WordPress PageFlipBook">WP-PageFlipBook</a>.</p>
				<p>'. TXT_WPPF_MADEWITH .' <a href="http://www.agence-web-360.com" alt="Cr&eacute;ation de sites">Agence Web</a>.</p>
				<p>'. TXT_WPPF_MADEWITH .' <a href="http://www.codeweb.fr/" alt="Graphisme et WebDesign">CodeWeb</a>.</p>
				<p>'. TXT_WPPF_TOVIEWTHEBOOK .' <a href="http://adobe.com/go/getflashplayer">Flash player</a>.</p>
			</div>
			<script type="text/javascript">
				var so = new SWFObject("' . $flash_script . '", "book", "'.$pageflipbook_options['pageflip_largeur'].'", "'.$pageflipbook_options['pageflip_hauteur'].'", "8"'. $couleur_background .');
				so.addVariable("xmlFile","' . $xml_url . '");
				'. $wmode .'
				so.write("pageflipbookContent");
			</script>
			';
		}
		else {
			wp_deregister_script('jquery');
			$book=0;
			$largeur_icone = 36;
			$nbIconesControle = 2;
			$largeur_controles = ($largeur_icone +(5*2)) * $nbIconesControle;
			$html = '
<style type="text/css">
#pageflipbookArea_'. $book .'{
';
if ($pageflipbook_options['pageflip_back']!='0'){
	if ($pageflipbook_options['pageflip_back']=='1'){
		$html .= 'background-color: #'. $pageflipbook_options['pageflip_background'] .';';
	}
	else {
		$html .=  'background-image: url('. $plugin_path .'images/backgrounds/'. $pageflipbook_options['pageflip_back'] .');';
	}
}
if (!$detect->isiPhone()) $html .= 'padding: 50px;';
$html .= '
}
#pageflipbookContent_'. $book .'{
';
if ($detect->isiPhone()) 
	$html .= '	
	width:'. $pageflipbook_options['pageflip_page_largeur'] .'px; ';
else 
	$html .= '
	width:'. $pageflipbook_options['pageflip_page_largeur']*2 .'px; ';
$html .= '
	height:'.$pageflipbook_options['pageflip_page_hauteur'].'px;
	margin-right:auto;
	margin-left:auto;
}
#pageflipbookContent_'. $book .'.turn-page{
	background-color:#ccc;
	background-size:100%;
}
#pageflipbookControls_'. $book .'{
	margin-left: auto;
	margin-right: auto;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	background-color:#'.$pageflipbook_options['jquery_menu_color'].';
	width: '. $largeur_controles .'px;
}
.prev_'. $book .'{
	margin:5px;	width:36px;	height:36px; cursor:pointer; float:left;
	background: url("'.PAGEFLIPBOOK_PLUGIN_URL.'images/controls/round-previous.png") no-repeat;
}
.next_'. $book .'{
	margin:5px;	width:36px;	height:36px; cursor:pointer; float:left;
	background: url("'.PAGEFLIPBOOK_PLUGIN_URL.'images/controls/round-next.png") no-repeat;
}
.prev_'. $book .'-grey{
	margin:5px;	width:36px;	height:36px; float:left; display:none;
	background: url("'.PAGEFLIPBOOK_PLUGIN_URL.'images/controls/round-previous-grey.png") no-repeat;
}
.next_'. $book .'-grey{
	margin:5px;	width:36px;	height:36px; float:left; display:none;
	background: url("'.PAGEFLIPBOOK_PLUGIN_URL.'images/controls/round-next-grey.png") no-repeat;
}
</style>
		<div id="pageflipbookArea_'. $book .'">
			<div id="pageflipbookContent_'. $book .'">';
			$pages_data = $wpdb->get_results("SELECT * FROM `".$wpdb->pageflip."` ORDER BY `position` ASC",ARRAY_A);
			foreach((array)$pages_data as $page) {
$html .= '
				<div style="background-image:url('. PAGEFLIPBOOK_PLUGIN_URL .'pages/'. $page['adresse'] .'); background-size: 100%;"></div>
';
			}
			$html .= '
			</div>
			<br />
			<div id="pageflipbookControls_'. $book .'">
				<div class="prev_'. $book .'"></div><div class="prev_'. $book .'-grey"></div>
				<div class="next_'. $book .'"></div><div class="next_'. $book .'-grey"></div>
				<div style="clear:both"></div>
			</div>		
		</div>
		<div style="font-size:0.5em;"><a href="http://www.pageflipbook.com" target="_blank" title="Plugin WordPress Page Flip Book">PLUGIN A PAGEFLIPBOOK</a></div>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/turn.min.js"></script>
			<script type="text/javascript">
				var pageflip_'. $book .' = {
					start: function () {
						$(\'#pageflipbookContent_'. $book .'\').turn({
							display: \'double\',
							acceleration: true,
							elevation:50,
							when: {
								first: function(e, page) {
									$(\'.prev_'. $book .'\').hide();
									$(\'.prev_'. $book .'-grey\').show();
								},
								turned: function(e, page) {
									if (page > 1) {
										$(\'.prev_'. $book .'-grey\').hide();
										$(\'.prev_'. $book .'\').show();
									}
									var lastPage = $(\'#pageflipbookContent_'. $book .'\').turn(\'pages\') - 1;
									if ( page < lastPage ) {
										$(\'.next_'. $book .'-grey\').hide();
										$(\'.next_'. $book .'\').show();
									}
								},
								last: function(e, page) {
									$(\'.next_'. $book .'\').hide();
									$(\'.next_'. $book .'-grey\').show();
								}
							}
						});
					},
				
					controls: function() {
						$(\'.prev_'. $book .'\').click(function() {
							$(\'#pageflipbookContent_'. $book .'\').turn(\'previous\');
						});
						$(\'.next_'. $book .'\').click(function() {
							$(\'#pageflipbookContent_'. $book .'\').turn(\'next\');
						});
					},
				}
				function mobileAdapt() {
					if ($(\'#pageflipbookArea_'. $book .'\').width() < '. $pageflipbook_options['pageflip_page_largeur'].' ) {
						var new_width = $(\'#pageflipbookArea_'. $book .'\').width();
						var new_height = '.$pageflipbook_options['pageflip_page_hauteur'].' * new_width / '. $pageflipbook_options['pageflip_page_largeur'].' ;
						$(\'#pageflipbookContent_'. $book .'\').css({ height: new_height, width: new_width });
						$(\'#pageflipbookContent_'. $book .'\').turn(\'display\', \'single\');
					}
				}
				$(window).bind(\'keydown\', function(e){
					if (e.keyCode==37) { // ->
						$(\'#pageflipbookContent_'. $book .'\').turn(\'previous\');
					}
					else if (e.keyCode==39) { // <-
						$(\'#pageflipbookContent_'. $book .'\').turn(\'next\');
					}
				});
				$(window).ready(function() {
					pageflip_'. $book .'.start();
					pageflip_'. $book .'.controls();
';
if ($detect->isiPhone())
	$html .= '	
					$(\'#pageflipbookContent_'. $book .'\').turn(\'display\', \'single\');
					mobileAdapt();
	';
$html .= '
				});
			</script>
			';
		}
		return $html;
	}
}
?>