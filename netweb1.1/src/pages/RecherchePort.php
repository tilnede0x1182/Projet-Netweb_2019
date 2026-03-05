<?php
/**
	Traitement du formulaire de recherche par stack et port.
	Interroge la base de donnees et affiche les resultats enrichis par SNMP.
*/

// ==============================================================================
// Importations
// ==============================================================================

include '../../utils/includes/config.php';
include '../../utils/includes/fonctions_affichage.php';
include '../../utils/includes/fonctions_snmp.php';
include '../../utils/includes/fonctions_requetes.php';
include '../../utils/snmp/simulation_snmp.php';
include '../../utils/templates/affichage_resultats.php';
include '../../utils/templates/structure_page.php';
include '../../utils/templates/formulaire.php';

// ==============================================================================
// Lancement du programme
// ==============================================================================

$valeurs = recuperer_valeurs_formulaire();
$connexion = ouvrir_connexion($adresse_hote_base_de_donnees, $utilisateur_base_de_donnees, $mot_de_passe_base_de_donnees, $nom_base_de_donnees);
$resultats = recherche_port_prepare($connexion, $nom_base_de_donnees, $valeurs['numero_stack'], $valeurs['port_debut'], $valeurs['port_fin']);
fermer_connexion($connexion);
afficher_page_resultats($titre_page, $valeurs, $resultats, $communaute_protocole_snmp);

?>
