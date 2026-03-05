<?php
/**
	Fonctions de structure de page HTML.
*/

// ==============================================================================
// Fonctions de structure HTML
// ==============================================================================

/**
	Affiche le debut du document HTML (DOCTYPE et balise html).
*/
function affiche_debut_page_html() {
	echo '<!DOCTYPE html>'."\n".'<html>'."\n";
}

/**
	Affiche le titre et le lien CSS de la page.

	@param string $title Titre de la page
*/
function affiche_titre_page_html($title) {
	echo "\t\t".'<title>'.$title.'</title>'."\n";
	global $root_url;
	echo "\t\t".'<link rel="stylesheet" type="text/css" href="'.$root_url.'assets/css/style.css">'."\n";
}

/**
	Affiche la section head complete avec titre et ouverture du body.

	@param string title Titre de la page
*/
function affiche_head_page_html($title) {
	echo "\t".'<head>'."\n";
	affiche_titre_page_html($title);
	echo "\t".'</head>'."\n";
	echo "\t".'<body>'."\n";
}

/**
	Affiche la fermeture du body et du document HTML.
*/
function affiche_fin_page_html() {
	echo "\t".'</body>'."\n";
	echo '</html>'."\n";
}

?>
