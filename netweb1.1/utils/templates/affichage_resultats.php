<?php
/**
 * Fonctions d affichage HTML pour les resultats de recherche.
 */

// ==============================================================================
// Fonctions d affichage des titres
// ==============================================================================

/**
 * Affiche une ligne de titres HTML pour une plage de colonnes.
 * 
 * @param array $tableau_donnees Tableau de donnees
 * @param int $index_ligne Index de la ligne dans le tableau
 * @param int $index_debut Index de debut des colonnes
 * @param int $index_fin Index de fin des colonnes
 */
function affiche_titres_html_precis($tableau_donnees, $index_ligne, $index_debut, $index_fin) {
	echo "\t\t".'<tr class="titre_table_resultats">'."\n";
	for ($index_colonne_parcours = $index_debut; $index_colonne_parcours < $index_fin; $index_colonne_parcours = $index_colonne_parcours + 1) {
		echo("\t\t\t".'<td>'.$tableau_donnees[$index_ligne][0][$index_colonne_parcours].'</td>'."\n");
	}
	echo "\t\t".'</tr>'."\n";
}

/**
 * Affiche tous les titres HTML d une ligne du tableau.
 * 
 * @param array tableau_donnees Tableau de donnees
 * @param int index_ligne Index de la ligne dans le tableau
 */
function affiche_titres_html($tableau_donnees, $index_ligne) {
	affiche_titres_html_precis($tableau_donnees, $index_ligne, 0, count($tableau_donnees[$index_ligne]));
}

// ==============================================================================
// Fonctions d affichage des donnees
// ==============================================================================

/**
 * Affiche les donnees HTML d une ligne avec mise en forme.
 * Les ports stk-254 sont rendus cliquables avec lien vers le switch.
 * 
 * @param array tableau_donnees Tableau de donnees
 * @param int index_ligne Index de la ligne dans le tableau
 */
function affiche_donnees_html($tableau_donnees, $index_ligne) {
	$nombre_de_cases_horizontales = 3;
	affiche_titres_html_precis($tableau_donnees, $index_ligne, 0, $nombre_de_cases_horizontales);
	echo "\t\t".'<tr class="donnees_table_resultats">'."\n";
	$index_colonne = 1;
	$nombre_colonnes = count($tableau_donnees[$index_ligne][1]);
	foreach ($tableau_donnees[$index_ligne][1] as $element) {
		if (strcmp(strstr($element, "0", "7"), "stk-254") == 0) {
			echo("\t\t\t".'<td><a title="'.$element.'" href="http://10.149.254.'.donne_stack($element).'" onclick="window.open(this.href); return false;">'.$element.'</td></a>'."\n");
		}
		else echo("\t\t\t".'<td>'.$element.'</td>'."\n");
		if ($index_colonne != 0 && $index_colonne % $nombre_de_cases_horizontales == 0 && $index_colonne <= $nombre_colonnes - $nombre_de_cases_horizontales) {
			echo "\t\t".'</tr>'."\n";
			affiche_titres_html_precis($tableau_donnees, $index_ligne, $index_colonne, $index_colonne + $nombre_de_cases_horizontales);
			echo "\t\t".'<tr class="donnees_table_resultats">'."\n";
		}
		$index_colonne = $index_colonne + 1;
	}
	echo "\t\t".'</tr>'."\n";
}

/**
 * Affiche un tableau complet de resultats en HTML.
 * 
 * @param array tableau_donnees Tableau de donnees a afficher
 */
function affiche_tab_donnee_html($tableau_donnees) {
	if ($tableau_donnees != null) {
		$nombre_elements = count($tableau_donnees);
		echo "\t".'<table border=1 class="table_resultats">'."\n";
		for ($index_ligne_parcours = 0; $index_ligne_parcours < $nombre_elements; $index_ligne_parcours++) {
			affiche_donnees_html($tableau_donnees, $index_ligne_parcours);
		}
		echo "\t".'</table>'."\n";
	}
	else {
		echo "Pas de resultats\n";
	}
}

?>
