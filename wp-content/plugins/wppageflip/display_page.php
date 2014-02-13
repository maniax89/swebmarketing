<?php

if ( ABSPATH=='') define('ABSPATH', substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'/wp-includes')));
$pageflipbook_options = get_option('pageflipbook_options');
generation_pageflipbook_xml();
$message = '';

echo '
<!-- PageFlip Book -->
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/AC_RunActiveContent.js"></script>
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/core.js"></script>
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/events.js"></script>
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/css.js"></script>
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/coordinates.js"></script>
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/drag.js"></script>
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/dragsort.js"></script>
	<script language="JavaScript" type="text/javascript" src="'. PAGEFLIPBOOK_PLUGIN_URL .'js/cookies.js"></script>
	<script language="JavaScript" type="text/javascript"><!--
	var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()
	window.onload = function() {
		junkdrawer.restoreListOrder("numeric")
		dragsort.makeListSortable(document.getElementById("numeric"),
				saveOrder)
	}

	function verticalOnly(item) {
		item.toolManDragGroup.verticalOnly()
	}

	function speak(id, what) {
		var element = document.getElementById(id);
		element.innerHTML = \'Clicked \' + what;
	}
	function saveOrder(item) {
		var group = item.toolManDragGroup
		var list = group.element.parentNode
		var id = list.getAttribute("id")
		if (id == null) return
		group.register(\'dragend\', function() {
			ToolMan.cookies().set("list-" + id, 
					junkdrawer.serializeList(list), 365)
		})
	}

	function foo(listID) {
    var list = document.getElementById(listID);
    var items = list.getElementsByTagName("li");
    var itemsString = "";
	var hiddeninput = document.getElementById("nouvelOrdre"); // the input field storing the order
    for (var i = 0; i < items.length; i++) {
        if (itemsString.length > 0) itemsString += ":";
        itemsString += items[i].getAttribute("itemID");
    }
	hiddeninput.value = itemsString;
	}
	//-->
	</script>
<!-- PageFlip Book //-->
';

$plugin_url = get_option('siteurl').'/wp-content/plugins/wppageflip';

if ((isset($_POST['action']))&&($_POST['action']=='reordonner')){
	$tabnouvelordre = explode (':',$_POST['nouvelOrdre']);
	for ($i=0; $i<(count($tabnouvelordre)) ; $i++){
		$order_secured = count($tabnouvelordre) + $i;
		$pageflip_reorder_reset = 'UPDATE '. $wpdb->pageflip .' SET position = "'. $order_secured .'" WHERE ID="'. $tabnouvelordre[$i] .'"';
		$wpdb->query($pageflip_reorder_reset);
	}
	for ($i=0; $i<(count($tabnouvelordre)) ; $i++){
		$pageflip_reorder_request = 'UPDATE '. $wpdb->pageflip .' SET position="'. $i .'" WHERE ID="'. $tabnouvelordre[$i] .'"';
		$wpdb->query($pageflip_reorder_request);
	}	
	$message = TXT_WPPF_PAGEREORDERED;
}

if ((isset($_POST['action']))&&($_POST['action']=='supprimer')){
	supprime_image($_GET['flip_page']);
	$message = TXT_WPPF_PAGEDELETED;
}

if ((isset($_POST['action'])) && ($_POST['action']=='upload')){
  $format = strtolower(extension($_FILES['page_file']['name']));
  if (!(($format != '')  &&  (($format == 'jpg') || ($format == 'jpeg')))) {
    $message = TXT_WPPF_PAGEFORMATBEFORE .' '. $format .' '. TXT_WPPF_PAGEFORMATAFTER;
  }
  else {
	$pages_dir = '../wp-content/plugins/wppageflip/pages/';
	$thumbs_dir = '../wp-content/plugins/wppageflip/thumbs/';
	$rand_name = time() .'.'. $format;
	if ($pageflipbook_options['pageflip_redimensionner']==1)  {
		upload($_FILES['page_file'],$pages_dir);
		img2thumb(strtolower($_FILES['page_file']['name']) , ($pageflipbook_options['pageflip_page_largeur']*($_POST['is_double'] + 1)) , $pageflipbook_options['pageflip_page_hauteur'] , $_FILES['page_file']['type'] , $pages_dir , '../pages/'. $rand_name);
		unlink(ABSPATH .'wp-content/plugins/wppageflip/pages/'. strtolower($_FILES['page_file']['name']));
	}
	else {
		upload($_FILES['page_file'],$pages_dir);
		imgcopy(strtolower($_FILES['page_file']['name']),$pages_dir,$rand_name,$_FILES['page_file']['type']);
		unlink(ABSPATH .'wp-content/plugins/wppageflip/pages/'. strtolower($_FILES['page_file']['name']));
	}
	if (($_POST['pageflip_bordure']==1)&&($format!='swf')) bordure_image($pages_dir,$rand_name,$pageflipbook_options['pageflip_bordure']);
	$max_id = $wpdb->get_var("SELECT MAX(ID) FROM ". $wpdb->pageflip);
	$max_id++;
	if ($_POST['is_double']==0) {
		$secured_detachable = 0;
		img2thumb($rand_name , 75 , 100 , $_FILES['page_file']['type'] , $pages_dir , '../thumbs/'. $rand_name);
		$pageflip_insert = "INSERT INTO ". $wpdb->pageflip. " VALUES ('". $max_id ."','". $rand_name ."','http://','". $secured_detachable . "','0','". $_POST['preloaded'] ."','','". $max_id ."')";
		$wpdb->query($pageflip_insert);
		$page_data = $wpdb->get_row("SELECT * FROM `". $wpdb->pageflip ."` WHERE `adresse`='". $rand_name ."'");
	}
	else {
		$secured_detachable = $_POST['detachable'];
		image_double($pages_dir,$rand_name);
		$nom_img_gauche = str_replace ( '.' , '-g.', $rand_name );
		img2thumb($nom_img_gauche , 75 , 100 , $_FILES['page_file']['type'] , $pages_dir , '../thumbs/'. $nom_img_gauche);
		$pageflip_insert = "INSERT INTO ". $wpdb->pageflip. " VALUES ('". $max_id ."','". $nom_img_gauche ."','http://','". $secured_detachable . "','0','". $_POST['preloaded'] ."','','". $max_id ."')";
		$wpdb->query($pageflip_insert);
		$page_data = $wpdb->get_row("SELECT * FROM `". $wpdb->pageflip ."` WHERE `adresse`='". $nom_img_gauche ."'");
		$max_id++;
		$nom_img_droite = str_replace ( '.' , '-d.', $rand_name );
		img2thumb($nom_img_droite , 75 , 100 , $_FILES['page_file']['type'] , $pages_dir , '../thumbs/'. $nom_img_droite);
		$pageflip_insert = "INSERT INTO ". $wpdb->pageflip. " VALUES ('". $max_id ."','". $nom_img_droite ."','http://','". $secured_detachable . "','0','". $_POST['preloaded'] ."','','". $max_id ."')";
		$wpdb->query($pageflip_insert);
		$page_data = $wpdb->get_row("SELECT * FROM `". $wpdb->pageflip ."` WHERE `adresse`='". $nom_img_droite ."'");
	}
	// $site_url = get_option('siteurl');
	generation_pageflipbook_xml();
    $message = TXT_WPPF_PAGEADDED;
  }
}

if ($message!='') echo '<div class="updated"><p align="center">'. $message .'</p></div>';

// BOOK INDEX
if ((!isset($_GET['action']))&&(!isset($_POST['flip_page']))){
	$xml_url = PAGEFLIPBOOK_UPLOAD_URL . 'xml/pageflipbook.xml';
	echo'
	<style>
	ul.boxy {
		list-style-type: none;
		padding: 0px;
		margin: 0px;
		width: 100%;
		font-size: 13px;
		font-family: Arial, sans-serif;
	}
	ul.boxy li {
		cursor:move;
		background-color:#eeeeee;
		font-size:10px;
		margin:5px 5px 5px 5px;
		border: dashed 1px;
		padding:2px 1px 1px 2px;
		width:155px;
		float:left;
	}
	.thumb{
		width: 155px;
		height: 100px;
		overflow: hidden;
		position: relative;
		text-align: center;
		float:left;
	}
	.thumb img{
		max-height: 100px;
		max-width: 155px;
	}
	.icon{
		color : #fff;
		position: absolute;
		bottom: -60px;
		height: 60px;
		background: #000;
		background: rgba(0,0,0,0.7);
		width: 155px;
		text-align: center;
		line-height: 30px;
	}
	</style>
    <div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div><h2>'. TXT_WPPF_CHOOSEABOOK .'</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
<br />
<div style="text-align:center">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-5681677026817873";
/* PageFlip Plugin 728&#42;90 */
google_ad_slot = "5410605457";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<br />
	  <h2>'.TXT_WPPF_DRAGDROPREORDER.'</h2>
	  <p>
	  <ul id="numeric" class="boxy">
';
	$num_page = 1;
	$pages_data = $wpdb->get_results("SELECT * FROM `". $wpdb->pageflip ."` ORDER BY `position` ASC",ARRAY_A);
	foreach((array)$pages_data as $page) {
		if ($page['is_double']) {
			$texte_page = TXT_WPPF_MULTIPAGES;
			$page_suivante = ' - '. ($num_page+1);
		}
		else {
			$texte_page = TXT_WPPF_SINGLEPAGE;
			$page_suivante = '';
		}
		$image_url = $plugin_url .'/pages/'. $page['adresse'];
		$thumb_url = $plugin_url .'/thumbs/'. $page['adresse'];
		$apercu_url = $thumb_url;
		echo'
		  <li itemID="'. $page['ID'] .'">
			<center>
				'. $texte_page .' '. $num_page . $page_suivante .'<br/>
				<img src="'. $apercu_url .'" height="100" border="0"><br/>
				<table width="100%">
					<tr>
						<td><a href="?page=wppageflip/display_page.php&action=modifier&flip_page='. $page['ID'] .'&num_page='. $num_page .'" style="text-decoration:none"><img src="'. PAGEFLIPBOOK_ICONES_DIR .'magnifier.png" ></a></td>
						<td style="text-align:right;">
							<form method="post" action="./admin.php?page=wppageflip/display_page.php&flip_page='. $page['ID'] .'">
				 			<input type="hidden" name="action" value="supprimer">
				 			<input type="hidden" name="page_file" value="'. $page['adresse'] .'">
				 			<input type="image" src="'. PAGEFLIPBOOK_ICONES_DIR .'cross.gif" onclick="return showNotice.warn();">
				 			</form>
						</td>
					</tr>
				</table>
			</center>
		  </li>
';
		$num_page ++;
		if ($page['is_double']) $num_page ++;
	}
	echo'
	</ul>
	</p>
	<div style="clear: both;"></div>
	<p class="submit">
	<table width="100%"><tr>
	  <td width="50%" align="left">
		<form method="post" action="./admin.php?page=wppageflip/display_page.php" onsubmit="return foo(\'numeric\');" >
			<input type="hidden" name="nouvelOrdre" id="nouvelOrdre">
			<input type="hidden" value="reordonner" name="action">
			<input type="submit" value="'. TXT_WPPF_SAVE_ORDER .' &raquo;"  class="button submit" id="submitbutton">
		</form>
	  </td>
	</tr></table>
	</p>
	<div id="chooseabook">
	  <center>
	  <br />
	  '. TXT_WPPF_TEXTTOADD .' : <strong>[PageFlipBook_Lite]</strong><br />
	  <br />
	  <table>
		  <tr>
			<td>'.TXT_XML_LINK.' :</td>
			<td><a href="'. $xml_url . '" target="_blank">'. $xml_url . '</a></td>
	      </tr>
	  </table>
	  </center>
	</div>
		</div>
		<div id="postbox-container-1" class="postbox-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox ">
					<h3 class="hndle">'. TXT_WPPF_ADDAPAGE .'</h3>
					<div class="inside">
						<div id="minor-publishing-actions">
	  <p style="text-align:left;">
	    <img src="../wp-content/plugins/wppageflip/images/cog_error.png">&nbsp;'. TXT_WPPF_ACCEPTEDFORMATS .'.
	  </p>
	  <hr />
      <form method="post" action="./admin.php?page=wppageflip/display_page.php" enctype="multipart/form-data">
	  <input type="hidden" name="action" value="upload">
	  <ul>
	  <li><label>
	   '. TXT_WPPF_ADDAPAGE .' : </label>
	  <input id="page_file" name="page_file" value="" type="file">
	  </li>
	  <li><label>
	  '. TXT_WPPF_DOUBLEPAGE .' : </label>
	  <input value="1" name="is_double" ';
	if ($pageflipbook_options['pageflip_doublepages']==1) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_YES .'
	  <input value="0" name="is_double" ';
	if ($pageflipbook_options['pageflip_doublepages']==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
	  </li>
	  <li><label>
	  '. TXT_WPPF_TEARABLE .' : </label>
	  <input id="" value="1" name="detachable" type="radio"> '. TXT_WPPF_YES .'
	  <input value="0" name="detachable" type="radio" checked="true"> '. TXT_WPPF_NO .'
	  </li>
	  <li><label>
	  '. TXT_WPPF_PRELOADED .' : </label>
	  <input value="1" name="preloaded" type="radio"> '. TXT_WPPF_YES .'
	  <input value="0" name="preloaded" type="radio" checked="true"> '. TXT_WPPF_NO .'
	  </li>
				';
	if ($pageflipbook_options['pageflip_bordure']!=0)
	echo'
	  <li><label>
	  '. TXT_WPPF_BORDER .' : </label>
	  <input value="1" name="pageflip_bordure" checked="true" type="radio"> '. TXT_WPPF_YES .'
	  <input value="0" name="pageflip_bordure" type="radio"> '. TXT_WPPF_NO .'
	  </li>
';
	 echo'
	 </ul>
						<div id="major-publishing-actions">
							<div id="publishing-action">
								<input id="publish" class="button button-primary button-large" type="submit" value="'. TXT_WPPF_ADD .' &raquo;" accesskey="p" name="upload">
							</div>
						</div>
		<br />

						</div>	
					</form>
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

// ZOOM PAGE MODIFICATION
if ( (isset($_POST['action'])) AND ($_POST['action']=='modifier')){
    $modification = false;
	$message = TXT_WPPF_UPDATEERROR;
	$pageflip_modif = 'UPDATE '. $wpdb->pageflip .' SET';
	$page_data = $wpdb->get_row("SELECT * FROM `". $wpdb->pageflip ."` WHERE ID=". $_POST['flip_page'] );
	if ((isset($_FILES['page_file']))&&(strtolower(extension($_FILES['page_file']['name']))!='')){
		$format = strtolower(extension($_FILES['page_file']['name']));
  		if (!(($format != '')  &&  (($format == 'jpg') || ($format == 'jpeg')))) {
    		$message = TXT_WPPF_PAGEFORMATBEFORE .' '. $format .' '. TXT_WPPF_PAGEFORMATAFTER;
  		}
		else{
			unlink(PAGEFLIPBOOK_UPLOAD_DIR . 'pages/'. $page_data->adresse);
			unlink(PAGEFLIPBOOK_UPLOAD_DIR . 'thumbs/'. $page_data->adresse);
			$page_file = $_FILES['page_file'];
			$temp_dir = PAGEFLIPBOOK_UPLOAD_DIR . 'temp/';
			$pages_dir = PAGEFLIPBOOK_UPLOAD_DIR . 'pages/';
			$thumbs_dir = PAGEFLIPBOOK_UPLOAD_DIR . 'thumbs/';
			upload ($page_file,$temp_dir);
			$rand_name = random(7);
			$thumb_width = 75 * ($_POST['is_double'] + 1);
			$format = strtolower(extension($_FILES['page_file']['name']));
			$pageflip_redimensionner = $pageflipbook_options['pageflip_redimensionner'];
			$rand_name = $rand_name .'.'. $format;
			if ($pageflip_redimensionner==1) img2thumb(strtolower($_FILES['page_file']['name']) , ($pageflipbook_options['pageflip_page_largeur']*($_POST['is_double'] + 1)) , $pageflipbook_options['pageflip_page_hauteur'] , $_FILES['page_file']['type'] , $temp_dir , '../pages/'. $rand_name);
			img2thumb(strtolower($_FILES['page_file']['name']) , $thumb_width , 100 , $_FILES['page_file']['type'] , $temp_dir , '../thumbs/'. $rand_name);
			if ($_POST['pageflip_bordure']==1) bordure_image($thumbs_dir,$rand_name,1);
		  	if ($pageflip_redimensionner==0) rename($temp_dir . $_FILES['page_file']['name'] , $pages_dir . $rand_name);
			if ($pageflip_redimensionner==1) unlink(ABSPATH .'wp-content/plugins/wppageflip/temp/'. strtolower($_FILES['page_file']['name']));
			if (($_POST['pageflip_bordure']==1)&&($format!='swf')) bordure_image($pages_dir,$rand_name,$pageflipbook_options['pageflip_bordure']);
			$pageflip_modif .= ' adresse = \''. $rand_name .'\',';
			$modification = true;
		}
	}
	if ((isset($_POST['is_double']))&&($page_data->is_double!=$_POST['is_double'])){
    $pageflip_modif .= ' is_double = '. $_POST['is_double'] .',';
	$modification=true;
	}
	if ((isset($_POST['detachable']))&&($page_data->detachable!=$_POST['detachable'])){
    	if ($_POST['is_double']==0){
			$pageflip_modif .= ' detachable = '. $_POST['detachable'] .',';
			$modification = true;
		}
	}
	if ((isset($_POST['preloaded']))&&($page_data->is_preloaded!=$_POST['preloaded'])){
    $pageflip_modif .= ' is_preloaded = '. $_POST['preloaded'] .',';
	$modification = true;
	}
	if (substr($pageflip_modif,-1)==',') $pageflip_modif=substr($pageflip_modif,0,(strlen($pageflip_modif)-1));
	$pageflip_modif .= ' WHERE ID='. $_POST['flip_page'];
	if ($modification == true){
	  $wpdb->query($pageflip_modif);
	  echo '<div class="updated"><p align="center">'. TXT_WPPF_NEWPARAMSSAVED .'</p></div>';
	}
	else {
	  echo '<div class="error"><p align="center">'. $message .'</p></div>';
	}
}

if ( (isset($_REQUEST['action'])) AND ($_REQUEST['action']=='modifier') ) {
	if (isset($_GET['flip_page'])) $flip_page = $_GET['flip_page'];
	else $flip_page = $_POST['flip_page'];
	if (isset($_GET['num_page'])) $num_page = $_GET['num_page'];
	else $num_page = $_POST['num_page'];
	$page_data = $wpdb->get_row("SELECT * FROM `". $wpdb->pageflip ."` WHERE ID=". $flip_page );
	$img_width = $pageflipbook_options['pageflip_page_largeur'] * ($page_data->is_double + 1);
	$div_width = $pageflipbook_options['pageflip_page_largeur'] *2;
	$url_page = PAGEFLIPBOOK_UPLOAD_URL . 'pages/'. $page_data->adresse;
	list($width_img, $height_img) = getimagesize(PAGEFLIPBOOK_UPLOAD_DIR . 'pages/'. $page_data->adresse);
	if ($page_data->is_double==1) {
			$texte_page = TXT_WPPF_MULTIPAGES;
			$page_suivante = ' - '. ($num_page+1);
		}
		else {
			$texte_page = TXT_WPPF_SINGLEPAGE;
			$page_suivante = '';
		}
	echo'
    <div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div><h2>'. TXT_WPPF_MODIFAPAGE .'</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
<br />
<div style="text-align:center">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-5681677026817873";
/* PageFlip Plugin 728&#42;90 */
google_ad_slot = "5410605457";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<br />
		<div class="postbox-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox ">
					<h3 class="hndle">'. $texte_page .' '. $num_page . $page_suivante .'</h3>
					<div class="inside">
						<p>

		<div>
	  	    <div style="float:left;">
				<img src="'. $url_page .'" width="'. $img_width .'" height="'. $pageflipbook_options['pageflip_page_hauteur'] .'">
			</div>
			<div style="float:left; padding-left:50px;">
			'. $page_data->adresse .' <br/>
			<br/><br/>
			'. TXT_WPPF_WIDTH .' : '. $width_img. 'px<br/>
			<br/>
			'. TXT_WPPF_HEIGHT .' : '. $height_img. 'px<br/>
			<br/>
			<br/>
			<br/>
			<br/>
		    <form method="post" action="./admin.php?page=wppageflip/display_page.php&flip_page='. $page_data->ID .'">
				 <input type="hidden" name="action" value="supprimer">
				 <input type="hidden" name="page_file" value="'. $page_data->adresse .'">
				 '. TXT_WPPF_DELETE .' : <input type="image" src="'. PAGEFLIPBOOK_ICONES_DIR .'cross.gif" onclick="return showNotice.warn();">
			</form>
			</div>
			<div style="clear:both"></div>
		</div>
						</p>
						
						
			     <form method="post" action="./admin.php?page=wppageflip/display_page.php">
				 <input value="&laquo; '. TXT_WPPF_RETURN .'" type="submit">
				 </form>
						

						
					</div>
				</div>
			</div>
		</div>
	  
	  
	  
	  
		</div>
		<div id="postbox-container-1" class="postbox-container">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox ">
					<h3 class="hndle">'. TXT_WPPF_CHANGEIMAGE .'</h3>
					<div class="inside">
						<div id="minor-publishing-actions">

		    <form method="post" action="./admin.php?page=wppageflip/display_page.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="modifier">
			<input type="hidden" name="num_page" value="'. $num_page .'">
			<input type="hidden" name="flip_page" value="'. $page_data->ID .'">
	  <ul>
	  <li><label>
	  '. TXT_WPPF_CHANGEIMAGE .' : </label>
	  <input id="page_file" name="page_file" value="" type="file">
	  </li>
	  <li><label>
	  '. TXT_WPPF_DOUBLEPAGE .' : </label>
	  <input value="1" name="is_double" ';
	if ($page_data->is_double==1) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_YES .'
				<input value="0" name="is_double" ';
	if ($page_data->is_double==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
	  </li>
	  <li><label>
	  '. TXT_WPPF_TEARABLE .' : </label>
				  <input value="1" name="detachable" ';
	 if ($page_data->detachable==1) echo'checked="true" ';
	 echo 'type="radio"> '. TXT_WPPF_YES .'
				  <input value="0" name="detachable" ';
	if ($page_data->detachable==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
	  </li>
	  <li><label>
	  '. TXT_WPPF_PRELOADED .' : </label>
<input value="1" name="preloaded" ';
	 if ($page_data->is_preloaded==1) echo'checked="true" ';
	 echo 'type="radio"> '. TXT_WPPF_YES .'
				  <input value="0" name="preloaded" ';
	if ($page_data->is_preloaded==0) echo'checked="true" ';
	echo'type="radio"> '. TXT_WPPF_NO .'
	  </li>
				';
	if ($pageflipbook_options['pageflip_bordure']!=0)
	echo'
		  <li><label>
	  '. TXT_WPPF_BORDER .' : </label>
				  <input value="1" name="pageflip_bordure" checked="true" type="radio"> '. TXT_WPPF_YES .'
				  <input value="0" name="pageflip_bordure" type="radio"> '. TXT_WPPF_NO .'
		  </li>
';
	 echo'
	 	  </ul>
						<div id="major-publishing-actions">
							<div id="publishing-action">
								<input id="publish" class="button button-primary button-large" type="submit" value="'. TXT_WPPF_MODIFY .' &raquo;" accesskey="p" name="save_conf">
							</div>
						</div>
		<br />
						</div>	
					</form>
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
?>