function afficher(id){
	document.getElementById(id).style.display="block"; 
	return true;
}

function cacher(id){
	document.getElementById(id).style.display="none";
	return true;
}

function changer_etat(id1, id2, img_ferme, img_ouvert){
	var element_id1 = document.getElementById(id1);
	var element_id2 = document.getElementById(id2);
	if (element_id1.style.display == "block"){
		cacher(id1);
		element_id2.src = img_ferme;
		element_id2.style.fontWeight = 'bold';
	}
	else {
		afficher(id1);
		element_id2.src = img_ouvert;
		element_id2.style.fontWeight = 'bold';
	}
	return true;
}

function switchMenu(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != "none" ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = '';
	}
}