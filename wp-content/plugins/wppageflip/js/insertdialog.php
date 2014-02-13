<?php
include ('../../../../wp-config.php');
$wpdb->pageflip = $table_prefix . 'pageflipbook';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageFlip Book</title>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript">
	function init() {
		tinyMCEPopup.resizeToInnerSize();
	}
	
	function insertPageflipBook() {
		var tagtext;	
		var idbook = document.getElementById('id_book').value;
		tagtext = "[PageFlipBook_" + idbook + "]";
	
		if(window.tinyMCE) {
			tinyMCEPopup.execCommand('mceInsertContent', false, tagtext);
			tinyMCEPopup.close();
		}
		
		return;
	}
	</script>
	<base target="_self" />
</head>
<body onLoad="tinyMCEPopup.executeOnLoad('init();');">
	<center>
	<h1>PageFlip Book</h1>
	<br />
	<br />
	<br />
	<form action="#">
		<select name="id_book" id="id_book">
<?php
	$books_data = $wpdb->get_results("SELECT DISTINCT id_book FROM `". $wpdb->pageflip ."`",ARRAY_A);
	foreach($books_data as $book) {
		if ($book['id_book'] == $id_book) $selected = 'selected ';
		else $selected = '';	
		$pfb_name = get_option('pageflipbook_'. $book['id_book'] .'_options');
		$book_name = $pfb_name['pageflip_name'];
		echo'<option '.$selected.'value='.$book['id_book'].'>'. html_encode($book_name) .'&nbsp;</option>';
  	}
	if (isset($_POST['name_newbook'])) echo'<option selected value='.$id_book.'>'.html_encode($_POST['name_newbook']).'&nbsp;</option>';
?>
		</select>
		<br />
		<br />
		<br />
		<br />
		<br />
		<div>
			<div style="float: left">
				<input type="button" id="insert" name="insert" value="<?php  _e('Insert'); ?>" onClick="insertPageflipBook();" />
			</div>
			<div style="float: right">
				<input type="button" id="cancel" name="cancel" value="<?php  _e('Cancel'); ?>" onClick="tinyMCEPopup.close();" />
			</div>
		</div>
	</form>
	</center>
</body>
</html>