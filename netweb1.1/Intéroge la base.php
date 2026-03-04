<?php
/**
	Fonction de connexion et interrogation de la base de donnees MySQL.
*/

// ==============================================================================
// Connexion et requetes MySQL
// ==============================================================================

/**
	Execute une requete SQL sur la base de donnees.

	@param bdd Nom de la base de donnees
	@param requette Requete SQL a executer
	@param bdd_user Utilisateur MySQL
	@param bdd_mdp Mot de passe MySQL
	@param bdd_adresse_hote Adresse du serveur MySQL
	@return array Tableau associatif des resultats
*/
function interroge_la_base($bdd, $requette, $bdd_user, $bdd_mdp, $bdd_adresse_hote) {
	if (!$link = mysql_connect($bdd_adresse_hote, $bdd_user, $bdd_mdp)) {
		echo 'Connexion impossible a mysql';
		exit;
	}

	if (!mysql_select_db($bdd, $link)) {
		echo 'Selection de base de donnees impossible';
		exit;
	}

	$sql = $requette;
	$result = mysql_query($sql, $link);

	if (!$result) {
		echo "Erreur DB, impossible d effectuer une requete\n";
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
