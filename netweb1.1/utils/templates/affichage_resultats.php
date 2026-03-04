<?php
/**
	Fonctions d affichage HTML pour les resultats de recherche.
*/

// ==============================================================================
// Fonctions d affichage des titres
// ==============================================================================

/**
	Affiche une ligne de titres HTML pour une plage de colonnes.

	@param tab Tableau de donnees
	@param case_tab Index de la ligne dans le tableau
	@param debut Index de debut des colonnes
	@param fin Index de fin des colonnes
*/
function affiche_titres_html_precis($tab, $case_tab, $debut, $fin) {
	echo "\t\t".'<tr class="titre_table_resultats">'."\n";
	for ($idx = $debut; $idx < $fin; $idx = $idx + 1) {
		echo("\t\t\t".'<td>'.$tab[$case_tab][0][$idx].'</td>'."\n");
	}
	echo "\t\t".'</tr>'."\n";
}

/**
	Affiche tous les titres HTML d une ligne du tableau.

	@param tab Tableau de donnees
	@param case_tab Index de la ligne dans le tableau
*/
function affiche_titres_html($tab, $case_tab) {
	affiche_titres_html_precis($tab, $case_tab, 0, count($tab[$case_tab]));
}

// ==============================================================================
// Fonctions d affichage des donnees
// ==============================================================================

/**
	Affiche les donnees HTML d une ligne avec mise en forme.
	Les ports stk-254 sont rendus cliquables avec lien vers le switch.

	@param tab Tableau de donnees
	@param case_tab Index de la ligne dans le tableau
*/
function affiche_donnees_html($tab, $case_tab) {
	$nombre_de_cases_horizontales = 3;
	affiche_titres_html_precis($tab, $case_tab, 0, $nombre_de_cases_horizontales);
	echo "\t\t".'<tr class="donnees_table_resultats">'."\n";
	$cmp = 1;
	$tab_len = count($tab[$case_tab][1]);
	foreach ($tab[$case_tab][1] as $element) {
		if (strcmp(strstr($element, "0", "7"), "stk-254") == 0) {
			echo("\t\t\t".'<td><a title="'.$element.'" href="http://10.149.254.'.donne_stack($element).'" onclick="window.open(this.href); return false;">'.$element.'</td></a>'."\n");
		}
		else echo("\t\t\t".'<td>'.$element.'</td>'."\n");
		if ($cmp != 0 && $cmp % $nombre_de_cases_horizontales == 0 && $cmp <= $tab_len - $nombre_de_cases_horizontales) {
			echo "\t\t".'</tr>'."\n";
			affiche_titres_html_precis($tab, $case_tab, $cmp, $cmp + $nombre_de_cases_horizontales);
			echo "\t\t".'<tr class="donnees_table_resultats">'."\n";
		}
		$cmp = $cmp + 1;
	}
	echo "\t\t".'</tr>'."\n";
}

/**
	Affiche un tableau complet de resultats en HTML.

	@param tab Tableau de donnees a afficher
*/
function affiche_tab_donnee_html($tab) {
	if ($tab != null) {
		$tab_len = count($tab);
		echo "\t".'<table border=1 class="table_resultats">'."\n";
		for ($idx = 0; $idx < $tab_len; $idx++) {
			affiche_donnees_html($tab, $idx);
		}
		echo "\t".'</table>'."\n";
	}
	else {
		echo "Pas de resultats\n";
	}
}

?>
