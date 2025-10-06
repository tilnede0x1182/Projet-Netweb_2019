<?php

function affiche_debut_page_html () {
	echo '<!DOCTYPE html>'."\n".'<html>'."\n";
}

function affiche_titre_page_html ($title) {
	echo "\t\t".'<title>'.$title.'</title>'."\n";
	echo "\t\t".'<link rel="stylesheet" type="text/css" href="Page.css">'."\n";
}

function affiche_head_page_html ($title) {
	echo "\t".'<head>'."\n";
	affiche_titre_page_html ($title);
	echo "\t".'</head>'."\n";
	echo "\t".'<body>'."\n";
}

function affiche_fin_page_html () {
	echo "\t".'</body>'."\n";
	echo '</html>'."\n";
}

?>