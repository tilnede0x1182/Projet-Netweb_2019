<?php
/**
	Fonction de connexion et interrogation de la base de donnees MySQL.
	Utilise mysqli (PHP 7+).
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
	$link = mysqli_connect($bdd_adresse_hote, $bdd_user, $bdd_mdp, $bdd);
	if (!$link) {
		echo 'Connexion impossible a mysql: ' . mysqli_connect_error();
		exit;
	}

	$result = mysqli_query($link, $requette);

	if (!$result) {
		echo "Erreur DB, impossible d effectuer une requete\n";
		echo 'Erreur MySQL : ' . mysqli_error($link);
		exit;
	}

	$res = array();
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($res, $row);
	}
	mysqli_free_result($result);
	mysqli_close($link);
	return $res;
}

?>
