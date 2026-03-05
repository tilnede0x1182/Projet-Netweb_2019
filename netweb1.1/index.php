<?php
/**
 * Point d entree principal de l application Netweb.
 * Charge tous les modules et affiche la page d accueil.
 */

// ==============================================================================
// Importations
// ==============================================================================

include 'utils/includes/connexion_db.php';
include 'utils/snmp/simulation_snmp.php';
include 'utils/includes/fonctions_affichage.php';
include 'utils/includes/fonctions_snmp.php';
include 'utils/templates/affichage_resultats.php';
include 'utils/templates/structure_page.php';
include 'utils/templates/formulaire.php';
// include 'Tests.php'; // Fichier non migré
include 'utils/includes/config.php';

// ==============================================================================
// Fonctions principales
// ==============================================================================

/**
 * Execute les tests de l application.
 * 
 * @param string $communaute_protocole_snmp Communaute SNMP pour les tests
 */
function tests($communaute_protocole_snmp) {
	$resultats_test = test_bdd();
	enrichie_resultats_BDD_par_SNMP($resultats_test, $communaute_protocole_snmp);
}

/**
 * Affiche la page principale avec le formulaire de recherche.
 * 
 * @param string $titre_page Titre de la page HTML
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
