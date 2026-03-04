<?php
/**
	Traitement du formulaire de recherche par stack et port.
	Interroge la base de donnees et affiche les resultats enrichis par SNMP.
*/

// ==============================================================================
// Importations
// ==============================================================================

include 'Interroge la base.php';
include 'renvoie_objet_snmp.php';
include 'Fonctions_utilitaires.php';
include 'fonctions_utilitaires_appliquees.php';
include 'Affiche_html.php';
include 'Affiche_page_html.php';
include 'Demande_utilisateur.php';
include 'VARIABLES_CONSTANTES.php';

// ==============================================================================
// Fonctions principales
// ==============================================================================

/**
	Interroge la base de donnees avec les criteres stack et port.

	@param bdd_base Nom de la base de donnees
	@param user Utilisateur MySQL
	@param mdp Mot de passe MySQL
	@param host Adresse du serveur MySQL
	@return array Resultats de la requete
*/
function base_de_donnees($bdd_base, $user, $mdp, $host) {
	$bdd = $bdd_base;
	$stk = trim(htmlspecialchars($_POST['stk']), " \t\n\r\0\x0B");
	$port_1 = trim(htmlspecialchars($_POST['port_1']), " \t\n\r\0\x0B");
	$port_2 = trim(htmlspecialchars($_POST['port_2']), " \t\n\r\0\x0B");
	$port_bdd = "stk-254".remplit_nombre_zeros($stk, 3)."[".remplit_nombre_zeros($port_1, 2)."/".remplit_nombre_zeros($port_2, 2)."]";
	if ($port_bdd != null) {
		$requette = 'select iph_name as Nom, ipa_addr as IP, iph_ether as MAC, iph_gpsnum as Nom_inventaire, iph_type as Type, iph_dnsstate as DNS, iph_ug as UG, iph_affect as Affectation, iph_desc as Description, iph_location as Emplacement, iph_switchport as Port, iph_lastupdated as Derniere_connection from '.$bdd.".ip_host, ".$bdd.'.ip_address where ipa_client=iph_client and iph_switchport="'.$port_bdd.'";';
		$res = interroge_la_base($bdd, $requette, $user, $mdp, $host);
		return $res;
	}
	else return array();
}

/**
	Affiche les resultats enrichis par les donnees SNMP.

	@param res_requette Resultats de la requete BDD
	@param communaute_cst Communaute SNMP
*/
function affiche_reponse($res_requette, $communaute_cst) {
	affiche_tab_donnee_html(enrichie_resultats_BDD_par_SNMP($res_requette, $communaute_cst));
}

/**
	Traite la soumission du formulaire et affiche la page de resultats.

	@param titre_page Titre de la page HTML
	@param bdd_base Nom de la base de donnees
	@param user Utilisateur MySQL
	@param mdp Mot de passe MySQL
	@param host Adresse du serveur MySQL
	@param communaute_cst Communaute SNMP
*/
function reponse_au_formulaire($titre_page, $bdd_base, $user, $mdp, $host, $communaute_cst) {
	$entree_par_default = isset($_POST['entree_user']) ? trim(htmlspecialchars($_POST['entree_user']), " \t\n\r\0\x0B") : "";
	$stk_par_default = isset($_POST['stk']) ? trim(htmlspecialchars($_POST['stk']), " \t\n\r\0\x0B") : "";
	$port_par_default_1 = isset($_POST['port_1']) ? trim(htmlspecialchars($_POST['port_1']), " \t\n\r\0\x0B") : "";
	$port_par_default_2 = isset($_POST['port_2']) ? trim(htmlspecialchars($_POST['port_2']), " \t\n\r\0\x0B") : "";
	affiche_debut_page_html();
	affiche_head_page_html($titre_page);
	affiche_formulaire($entree_par_default, $stk_par_default, $port_par_default_1, $port_par_default_2);
	$res_requette = base_de_donnees($bdd_base, $user, $mdp, $host);
	affiche_reponse($res_requette, $communaute_cst);
	affiche_fin_page_html();
}

// ==============================================================================
// Lancement du programme
// ==============================================================================

reponse_au_formulaire($titre_page, $bdd_base, $bdd_user, $bdd_mdp, $bdd_adresse_hote, $communaute_cst);

?>
