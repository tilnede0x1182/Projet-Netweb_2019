<?php

include 'Intéroge la base.php';
include 'renvoie_objet_snmp.php';
include 'Fonctions_utilitaires.php';
include 'fonctions_utilitaires_appliquees.php';
include 'Affiche_html.php';
include 'Affiche_page_html.php';
include 'Demande_utilisateur.php';
include 'Tests.php';

include 'VARIABLES_CONSTANTES.php';

function tests ($communaute_cst) {
	//test_snmp($communaute_cst);
	$tmp = test_bdd();
	enrichie_resultats_BDD_par_SNMP($tmp, $communaute_cst);
}

function main($titre_page) {
	affiche_debut_page_html();
	affiche_head_page_html($titre_page);
	affiche_formulaire("", "", "", "");
	affiche_fin_page_html();
}

main($titre_page);
//tests($communaute_cst);

?>