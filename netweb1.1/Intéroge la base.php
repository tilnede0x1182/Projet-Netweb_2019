<?php

function interroge_la_base ($bdd, $requette, $bdd_user, $bdd_mdp, $bdd_adresse_hote) {
	if (!$link = mysql_connect($bdd_adresse_hote, $bdd_user, $bdd_mdp)) {
	    echo 'Connexion impossible � mysql';
	    exit;
	}
	
	if (!mysql_select_db($bdd, $link)) {
	    echo 'S�lection de base de donn�es impossible';
	    exit;
	}
	
	$sql    = $requette;
	$result = mysql_query($sql, $link);
	
	if (!$result) {
	    echo "Erreur DB, impossible d'effectuer une requ�te\n";
	    echo 'Erreur MySQL : ' . mysql_error();
	    exit;
	}
	
	$res = array();
	while ($row = mysql_fetch_assoc($result)) {
		array_push($res, $row);
	}	
	mysql_free_result($result);
	return $res;
}

?>