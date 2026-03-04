<?php
/**
	Fonctions utilitaires pour l affichage et le traitement des donnees.
*/

// ==============================================================================
// Fonctions d affichage
// ==============================================================================

/**
	Affiche les elements d un tableau sur des lignes separees.

	@param tab Tableau a afficher
*/
function aff_tab($tab) {
	foreach ($tab as $element) {
		echo($element."\n");
	}
}

/**
	Affiche les elements d un tableau a deux dimensions.

	@param tab Tableau 2D a afficher
*/
function aff_tab_2_dimensions($tab) {
	foreach ($tab as $ligne) {
		foreach ($ligne as $element) {
			echo($element."\n");
		}
	}
}

/**
	Affiche un tableau de resultats MySQL avec cles et valeurs.

	@param tab Tableau associatif de resultats MySQL
*/
function aff_tab_res_mysql($tab) {
	foreach ($tab as $ligne) {
		foreach ($ligne as $cle => $valeur) {
			echo($cle." : ".$valeur."\n");
		}
	}
}

/**
	Affiche un booleen sous forme de 1 ou 0.

	@param tmp Valeur booleenne a afficher
*/
function aff_bool($tmp) {
	if ($tmp) echo ("1\n");
	else echo ("0\n");
}

// ==============================================================================
// Fonctions de traitement SNMP
// ==============================================================================

/**
	Nettoie une reponse SNMP pour extraire uniquement la valeur numerique.

	@param tmp Reponse SNMP brute (ex: "INTEGER: 100")
	@return string Valeur numerique extraite ou "Pas de reponse"
*/
function nettoie_res_snmp($tmp) {
	if (strcmp($tmp, "Pas de reponse")==0) return $tmp;
	$tmp_len = strlen($tmp);
	$idx = 0;
	if (str_contains($tmp, ":")) {
		while ($idx < $tmp_len && strcmp(substr($tmp, $idx, 1), ":")!=0) {
			$idx = $idx+1;
		}
		$idx = $idx+1;
	}
	$res = "";
	while ($idx < $tmp_len) {
		if (is_numeric(substr($tmp, $idx, 1))) {
			$res = $res.substr($tmp, $idx, 1);
		}
		$idx = $idx+1;
	}
	return $res;
}

// ==============================================================================
// Fonctions de formatage
// ==============================================================================

/**
	Complete un nombre avec des zeros a gauche.

	@param nombre Nombre a formater
	@param nbr_zeros Longueur totale souhaitee
	@return string Nombre formate avec zeros
*/
function remplit_nombre_zeros($nombre, $nbr_zeros) {
	$longueur_cible = $nbr_zeros;
	$tmp = "".$nombre;
	$tmplen = strlen($tmp);

	while ($tmplen < $longueur_cible) {
		$tmp = "0".$tmp;
		$tmplen = strlen($tmp);
	}
	return $tmp;
}

/**
	Formate une vitesse de port en unites lisibles.

	@param vitesse Vitesse en bps
	@return string Vitesse formatee (ex: "100 mo")
*/
function formate_vitesse_port($vitesse) {
	$res = $vitesse;
	if (strlen($vitesse)>0) {
		if (strcmp($vitesse, "10000000")==0) $res = "10 mo";
		if (strcmp($vitesse, "100000000")==0) $res = "100 mo";
		if (strcmp($vitesse, "1000000000")==0) $res = "1 go";
	}
	return $res;
}

// ==============================================================================
// Polyfill
// ==============================================================================

/**
	Polyfill pour str_contains (PHP < 8.0).

	@param chaine Chaine dans laquelle chercher
	@param chaine_a_chercher Sous-chaine a trouver
	@return bool true si trouve, false sinon
*/
if (!function_exists('str_contains')) {
	function str_contains($chaine, $chaine_a_chercher) {
		$len_recherche = strlen($chaine_a_chercher);
		$len_chaine = strlen($chaine);

		for ($idx = 0; $idx <= $len_chaine - $len_recherche; $idx++) {
			if (strcmp(substr($chaine, $idx, $len_recherche), $chaine_a_chercher)==0) return true;
		}
		return false;
	}
}
?>
