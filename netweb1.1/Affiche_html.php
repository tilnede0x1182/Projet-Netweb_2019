<?php

function affiche_titres_html_precis ($tab, $case_tab, $debut, $fin) {
	echo "\t\t".'<tr class="titre_table_resultats">'."\n";
	for ($i=$debut; $i<$fin; $i=$i+1) {
		echo("\t\t\t".'<td>'.$tab[$case_tab][0][$i].'</td>'."\n");
	}
	echo "\t\t".'</tr>'."\n";
}

function affiche_titres_html ($tab, $case_tab) {
	affiche_titres_html_precis ($tab, $case_tab, 0, count($tab[$case_tab]));
}

function affiche_donnees_html ($tab, $case_tab) {
	$nombre_de_cases_horizontales = 3;
	affiche_titres_html_precis($tab, $case_tab, 0, $nombre_de_cases_horizontales);
	echo "\t\t".'<tr class="donnees_table_resultats">'."\n";
	$cmp = 1;
	$tab_len = count($tab[$case_tab][1]);
	foreach ($tab[$case_tab][1] as $i) {
		if (strcmp(strstr($i, "0", "7"), "stk-254")==0) {
			echo("\t\t\t".'<td><a title="'.$i.'" href="http://10.149.254.'.donne_stack($i).'" onclick="window.open(this.href); return false;">'.$i.'</td></a>'."\n");
		}
		else echo("\t\t\t".'<td>'.$i.'</td>'."\n");
		if ($cmp!=0 && $cmp%$nombre_de_cases_horizontales==0 && $cmp<=$tab_len-$nombre_de_cases_horizontales) {
			echo "\t\t".'</tr>'."\n";
			affiche_titres_html_precis($tab, $case_tab, $cmp, $cmp+$nombre_de_cases_horizontales);
			echo "\t\t".'<tr class="donnees_table_resultats">'."\n";
		}
		$cmp = $cmp+1;
	}
	echo "\t\t".'</tr>'."\n";
}

function affiche_tab_donnee_html ($tab) {
	if ($tab!=null) {
		$tab_len = count($tab);
		echo "\t".'<table border=1 class="table_resultats">'."\n";
		for ($i = 0; $i < $tab_len; $i++) {
			affiche_donnees_html ($tab, $i);
		}
		echo "\t".'</table>'."\n";
	}
	else {
		echo "Pas de résultats\n";
	}
}

?>