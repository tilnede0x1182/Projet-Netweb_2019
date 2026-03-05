<?php
/**
 * Fonctions utilitaires appliquees au contexte reseau SNMP.
 */

// ==============================================================================
// Fonctions de calcul port SNMP
// ==============================================================================

/**
 * Calcule le numero de port SNMP en fonction du switch et du port physique.
 * Les switchs empiles ont des offsets de 64 par switch.
 * 
 * @param int $numero_switch Numero du switch dans le stack (1-4)
 * @param int $port_physique Numero du port physique (0-50)
 * @return int Numero de port SNMP calcule
 */
function calcul_numero_port_protocole_snmp($numero_switch, $port_physique) {
	if ($port_physique < 0 || $port_physique > 50) {
		print("calcul_numero_port_protocole_snmp : Erreur, port_physique<0 ou port_physique>50");
		return $port_physique;
	}
	if ($numero_switch < 1 || $numero_switch > 4) {
		print("calcul_numero_port_protocole_snmp : Erreur, numero_switch<1 ou numero_switch>4");
		return $port_physique;
	}
	if ($numero_switch == 1) {
		return $port_physique;
	}
	if ($numero_switch == 2) {
		return ($port_physique + 64);
	}
	if ($numero_switch == 3) {
		return ($port_physique + 128);
	}
	if ($numero_switch == 4) {
		return ($port_physique + 192);
	}
}

// ==============================================================================
// Fonctions de parsing du format stack
// ==============================================================================

/**
 * Verifie si le format du port respecte le standard stk-254*.
 * 
 * @param string $port_format_stack Chaine du port au format stack
 * @return bool true si format valide, false sinon
 */
function verife_format_stack_standard($port_format_stack) {
	if (strcmp(substr($port_format_stack, 0, 7), "stk-254") != 0) return false;
	return true;
}

/**
 * Extrait le numero de stack depuis une chaine au format stk-254XXX.
 * 
 * @param string port_format_stack Chaine du port au format stack
 * @return int Numero du stack ou -1 si format invalide
 */
function donne_stack($port_format_stack) {
	if (!verife_format_stack_standard($port_format_stack)) {
		return -1;
	}
	$numero_stack_extrait = substr($port_format_stack, 7, 3);
	$numero_stack_entier = intval($numero_stack_extrait);
	return $numero_stack_entier;
}

/**
 * Extrait les numeros de switch et port depuis une chaine au format stack.
 * 
 * @param string port_format_stack Chaine du port au format stk-254XXX-YY/ZZ
 * @return array Tableau [num_switch, num_port] ou tableau vide si invalide
 */
function donne_port($port_format_stack) {
	if (!verife_format_stack_standard($port_format_stack)) {
		return array();
	}
	$resultats_port = array();
	array_push($resultats_port, intval(substr($port_format_stack, 11, 2)));
	array_push($resultats_port, intval(substr($port_format_stack, 14, 2)));
	return $resultats_port;
}

// ==============================================================================
// Fonctions de decodage MySQL
// ==============================================================================

/**
 * Decode un tableau de resultats MySQL en format exploitable.
 * Separe les cles et valeurs en deux tableaux distincts.
 * 
 * @param array $tableau_mysql Tableau de resultats MySQL
 * @return array Tableau decode avec structure [[cles], [valeurs]]
 */
function decode_tab_mysql($tableau_mysql) {
	$resultats_decodes = array();
	$cles_colonne = array();
	$valeurs_colonne = array();
	foreach ($tableau_mysql as $ligne) {
		$ligne_decodee = array();
		foreach ($ligne as $cle => $valeur) {
			array_push($cles_colonne, $cle);
			array_push($valeurs_colonne, $valeur);
		}
		array_push($ligne_decodee, $cles_colonne);
		array_push($ligne_decodee, $valeurs_colonne);
		array_push($resultats_decodes, $ligne_decodee);
	}
	return $resultats_decodes;
}

?>
