<?php
/**
 * Fonctions de requetes SQL avec requetes preparees.
 * Factorise le code commun entre RechercheEquipement et RecherchePort.
 */

// ==============================================================================
// Requetes preparees
// ==============================================================================

/**
 * Execute une requete preparee pour rechercher un equipement par IP/nom/MAC/inventaire.
 * 
 * @param mysqli $connexion Connexion MySQL active
 * @param string $nom_base_de_donnees Nom de la base de donnees
 * @param string $entree_user Critere de recherche utilisateur
 * @return array Resultats de la requete
 */
function recherche_equipement_prepare($connexion, $nom_base_de_donnees, $entree_user) {
	$entree = trim($entree_user);
	if (empty($entree)) {
		return array();
	}

	$requete = "SELECT iph_name as Nom, ipa_addr as IP, iph_ether as MAC,
		iph_gpsnum as Nom_inventaire, iph_type as Type, iph_dnsstate as DNS,
		iph_ug as UG, iph_affect as Affectation, iph_desc as Description,
		iph_location as Emplacement, iph_switchport as Port,
		iph_lastupdated as Derniere_connection
		FROM `$nom_base_de_donnees`.ip_host, `$nom_base_de_donnees`.ip_address
		WHERE ipa_client=iph_client
		AND (iph_name=? OR iph_gpsnum=? OR iph_ether=? OR ipa_addr=?)";

	$requete_preparee = mysqli_prepare($connexion, $requete);
	if (!$requete_preparee) {
		echo "Erreur preparation requete: " . mysqli_error($connexion);
		return array();
	}

	mysqli_stmt_bind_param($requete_preparee, "ssss", $entree, $entree, $entree, $entree);
	mysqli_stmt_execute($requete_preparee);
	$result = mysqli_stmt_get_result($requete_preparee);

	$resultats = array();
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($resultats, $row);
	}

	mysqli_stmt_close($requete_preparee);
	return $resultats;
}

/**
 * Execute une requete preparee pour rechercher un equipement par stack/port.
 * 
 * @param connexion mysqli Connexion MySQL active
 * @param nom_base_de_donnees string Nom de la base de donnees
 * @param string $numero_stack Numero de stack
 * @param string $port_debut Premier numero de port
 * @param string $port_fin Second numero de port
 * @return array Resultats de la requete
 */
function recherche_port_prepare($connexion, $nom_base_de_donnees, $numero_stack, $port_debut, $port_fin) {
	$numero_stack = trim($numero_stack);
	$port_debut = trim($port_debut);
	$port_fin = trim($port_fin);

	if (empty($numero_stack) || empty($port_debut) || empty($port_fin)) {
		return array();
	}

	$format_port_bdd = "stk-254" . remplit_nombre_zeros($numero_stack, 3) . "[" . remplit_nombre_zeros($port_debut, 2) . "/" . remplit_nombre_zeros($port_fin, 2) . "]";

	$requete = "SELECT iph_name as Nom, ipa_addr as IP, iph_ether as MAC,
		iph_gpsnum as Nom_inventaire, iph_type as Type, iph_dnsstate as DNS,
		iph_ug as UG, iph_affect as Affectation, iph_desc as Description,
		iph_location as Emplacement, iph_switchport as Port,
		iph_lastupdated as Derniere_connection
		FROM `$nom_base_de_donnees`.ip_host, `$nom_base_de_donnees`.ip_address
		WHERE ipa_client=iph_client AND iph_switchport=?";

	$requete_preparee = mysqli_prepare($connexion, $requete);
	if (!$requete_preparee) {
		echo "Erreur preparation requete: " . mysqli_error($connexion);
		return array();
	}

	mysqli_stmt_bind_param($requete_preparee, "s", $format_port_bdd);
	mysqli_stmt_execute($requete_preparee);
	$result = mysqli_stmt_get_result($requete_preparee);

	$resultats = array();
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($resultats, $row);
	}

	mysqli_stmt_close($requete_preparee);
	return $resultats;
}

// ==============================================================================
// Connexion
// ==============================================================================

/**
 * Ouvre une connexion MySQL.
 * 
 * @param string $bdd_adresse_hote Adresse du serveur MySQL
 * @param string $bdd_user Utilisateur MySQL
 * @param string $bdd_mdp Mot de passe MySQL
 * @param string $bdd_base Nom de la base de donnees
 * @return mysqli Connexion MySQL
 */
function ouvrir_connexion($bdd_adresse_hote, $bdd_user, $bdd_mdp, $bdd_base) {
	$connexion = mysqli_connect($bdd_adresse_hote, $bdd_user, $bdd_mdp, $bdd_base);
	if (!$connexion) {
		echo 'Connexion impossible a mysql: ' . mysqli_connect_error();
		exit;
	}
	return $connexion;
}

/**
 * Ferme une connexion MySQL.
 * 
 * @param connexion mysqli Connexion MySQL a fermer
 */
function fermer_connexion($connexion) {
	mysqli_close($connexion);
}

// ==============================================================================
// Traitement commun
// ==============================================================================

/**
 * Recupere les valeurs POST du formulaire avec nettoyage.
 * 
 * @return array Valeurs nettoyees (entree_user, numero_stack, port_debut, port_fin)
 */
function recuperer_valeurs_formulaire() {
	$entree_user = isset($_POST['entree_user']) ? trim(htmlspecialchars($_POST['entree_user'])) : "";
	$numero_stack = isset($_POST['numero_stack']) ? trim(htmlspecialchars($_POST['numero_stack'])) : "";
	$port_debut = isset($_POST['port_debut']) ? trim(htmlspecialchars($_POST['port_debut'])) : "";
	$port_fin = isset($_POST['port_fin']) ? trim(htmlspecialchars($_POST['port_fin'])) : "";

	return array(
		'entree_user' => $entree_user,
		'numero_stack' => $numero_stack,
		'port_debut' => $port_debut,
		'port_fin' => $port_fin
	);
}

/**
 * Affiche la page complete avec formulaire et resultats.
 * 
 * @param string $titre_page Titre de la page HTML
 * @param array $valeurs_formulaire Valeurs du formulaire
 * @param array $resultats_bdd Resultats de la requete BDD
 * @param string $communaute_protocole_snmp Communaute SNMP
 */
function afficher_page_resultats($titre_page, $valeurs_formulaire, $resultats_bdd, $communaute_protocole_snmp) {
	affiche_debut_page_html();
	affiche_head_page_html($titre_page);
	affiche_formulaire(
		$valeurs_formulaire['entree_user'],
		$valeurs_formulaire['numero_stack'],
		$valeurs_formulaire['port_debut'],
		$valeurs_formulaire['port_fin']
	);
	affiche_tab_donnee_html(enrichie_resultats_BDD_par_SNMP($resultats_bdd, $communaute_protocole_snmp));
	affiche_fin_page_html();
}

?>
