<?php
/**
	Fonctions utilitaires pour l affichage et le traitement des donnees.
*/

// ==============================================================================
// Fonctions d affichage
// ==============================================================================

/**
	Affiche les elements d un tableau sur des lignes separees.

	@param array $tableau_elements Tableau a afficher
*/
function aff_tab($tableau_elements) {
	foreach ($tableau_elements as $element) {
		echo($element."\n");
	}
}

/**
	Affiche les elements d un tableau a deux dimensions.

	@param array $tableau_2_dimensions Tableau 2D a afficher
*/
function aff_tab_2_dimensions($tableau_2_dimensions) {
	foreach ($tableau_2_dimensions as $ligne) {
		foreach ($ligne as $element) {
			echo($element."\n");
		}
	}
}

/**
	Affiche un tableau de resultats MySQL avec cles et valeurs.

	@param array $resultats_mysql Tableau associatif de resultats MySQL
*/
function aff_tab_res_mysql($resultats_mysql) {
	foreach ($resultats_mysql as $ligne) {
		foreach ($ligne as $cle => $valeur) {
			echo($cle." : ".$valeur."\n");
		}
	}
}

/**
	Affiche un booleen sous forme de 1 ou 0.

	@param bool $resultat_verification Valeur booleenne a afficher
*/
function aff_bool($resultat_verification) {
	if ($resultat_verification) echo ("1\n");
	else echo ("0\n");
}

// ==============================================================================
// Fonctions de traitement SNMP
// ==============================================================================

/**
	Nettoie une reponse SNMP pour extraire uniquement la valeur numerique.

	@param string $reponse_snmp Reponse SNMP brute (ex: "INTEGER: 100")
	@return string Valeur numerique extraite ou "Pas de reponse"
*/
function nettoie_res_snmp($reponse_snmp) {
	if (strcmp($reponse_snmp, "Pas de reponse")==0) return $reponse_snmp;
	$longueur_reponse = strlen($reponse_snmp);
	$index_caractere_reponse = 0;
	if (str_contains($reponse_snmp, ":")) {
		while ($index_caractere_reponse < $longueur_reponse && strcmp(substr($reponse_snmp, $index_caractere_reponse, 1), ":")!=0) {
			$index_caractere_reponse = $index_caractere_reponse + 1;
		}
		$index_caractere_reponse = $index_caractere_reponse + 1;
	}
	$valeur_extraite = "";
	while ($index_caractere_reponse < $longueur_reponse) {
		if (is_numeric(substr($reponse_snmp, $index_caractere_reponse, 1))) {
			$valeur_extraite = $valeur_extraite . substr($reponse_snmp, $index_caractere_reponse, 1);
		}
		$index_caractere_reponse = $index_caractere_reponse + 1;
	}
	return $valeur_extraite;
}

// ==============================================================================
// Fonctions de formatage
// ==============================================================================

/**
	Complete un nombre avec des zeros a gauche.

	@param int $nombre Nombre a formater
	@param int $longueur_totale Longueur totale souhaitee
	@return string Nombre formate avec zeros
*/
function remplit_nombre_zeros($nombre, $longueur_totale) {
	$nombre_formate = "" . $nombre;
	$longueur_actuelle = strlen($nombre_formate);

	while ($longueur_actuelle < $longueur_totale) {
		$nombre_formate = "0" . $nombre_formate;
		$longueur_actuelle = strlen($nombre_formate);
	}
	return $nombre_formate;
}

/**
	Formate une vitesse de port en unites lisibles.

	@param string $vitesse_bits_par_seconde Vitesse en bps
	@return string Vitesse formatee (ex: "100 mo")
*/
function formate_vitesse_port($vitesse_bits_par_seconde) {
	$vitesse_formatee = $vitesse_bits_par_seconde;
	if (strlen($vitesse_bits_par_seconde) > 0) {
		if (strcmp($vitesse_bits_par_seconde, "10000000") == 0) $vitesse_formatee = "10 mo";
		if (strcmp($vitesse_bits_par_seconde, "100000000") == 0) $vitesse_formatee = "100 mo";
		if (strcmp($vitesse_bits_par_seconde, "1000000000") == 0) $vitesse_formatee = "1 go";
	}
	return $vitesse_formatee;
}

// ==============================================================================
// Polyfill
// ==============================================================================

/**
	Polyfill pour str_contains (PHP < 8.0).

	@param string $chaine Chaine dans laquelle chercher
	@param string $sous_chaine Sous-chaine a trouver
	@return bool true si trouve, false sinon
*/
if (!function_exists('str_contains')) {
	function str_contains($chaine, $sous_chaine) {
		$longueur_sous_chaine = strlen($sous_chaine);
		$longueur_chaine = strlen($chaine);

		for ($index_debut_comparaison = 0; $index_debut_comparaison <= $longueur_chaine - $longueur_sous_chaine; $index_debut_comparaison++) {
			if (strcmp(substr($chaine, $index_debut_comparaison, $longueur_sous_chaine), $sous_chaine) == 0) return true;
		}
		return false;
	}
}
?>
