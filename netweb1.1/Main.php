<?php
/**
	Point d entree principal de l application Netweb.
	Charge tous les modules et affiche la page d accueil.
*/

// ==============================================================================
// Importations
// ==============================================================================

include 'Intéroge la base.php';
include 'renvoie_objet_snmp.php';
include 'Fonctions_utilitaires.php';
include 'fonctions_utilitaires_appliquees.php';
include 'Affiche_html.php';
include 'Affiche_page_html.php';
include 'Demande_utilisateur.php';
include 'Tests.php';
include 'VARIABLES_CONSTANTES.php';

// ==============================================================================
// Fonctions principales
// ==============================================================================

/**
	Execute les tests de l application.

	@param communaute_cst Communaute SNMP pour les tests
*/
function tests($communaute_cst) {
	$tmp = test_bdd();
	enrichie_resultats_BDD_par_SNMP($tmp, $communaute_cst);
}

/**
	Affiche la page principale avec le formulaire de recherche.

	@param titre_page Titre de la page HTML
*/
function main($titre_page) {
	affiche_debut_page_html();
	affiche_head_page_html($titre_page);
	affiche_formulaire("", "", "", "");
	affiche_fin_page_html();
}

// ==============================================================================
// Lancement du programme
// ==============================================================================

main($titre_page);

?>
