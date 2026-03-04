<?php
/**
	Fonctions utilitaires appliquees au contexte reseau SNMP.
*/

// ==============================================================================
// Fonctions de calcul port SNMP
// ==============================================================================

/**
	Calcule le numero de port SNMP en fonction du switch et du port physique.
	Les switchs empiles ont des offsets de 64 par switch.

	@param num_switch Numero du switch dans le stack (1-4)
	@param port Numero du port physique (0-50)
	@return int Numero de port SNMP calcule
*/
function calcul_numero_port_snmp($num_switch, $port) {
	if ($port < 0 || $port > 50) {
		print("calcul_numero_port_snmp : Erreur, port<0 ou port>50");
		return $port;
	}
	if ($num_switch < 1 || $num_switch > 4) {
		print("calcul_numero_port_snmp : Erreur, num_switch<1 ou num_switch>4");
		return $port;
	}
	if ($num_switch == 1) {
		return $port;
	}
	if ($num_switch == 2) {
		return ($port + 64);
	}
	if ($num_switch == 3) {
		return ($port + 128);
	}
	if ($num_switch == 4) {
		return ($port + 192);
	}
}

// ==============================================================================
// Fonctions de parsing du format stack
// ==============================================================================

/**
	Verifie si le format du port respecte le standard stk-254*.

	@param port_stk Chaine du port au format stack
	@return bool true si format valide, false sinon
*/
function verife_stk_standart($port_stk) {
	if (strcmp(substr($port_stk, 0, 7), "stk-254") != 0) return false;
	return true;
}

/**
	Extrait le numero de stack depuis une chaine au format stk-254XXX.

	@param port_stk Chaine du port au format stack
	@return int Numero du stack ou -1 si format invalide
*/
function donne_stack($port_stk) {
	if (!verife_stk_standart($port_stk)) {
		return -1;
	}
	$tmp = substr($port_stk, 7, 3);
	$res = intval($tmp);
	return $res;
}

/**
	Extrait les numeros de switch et port depuis une chaine au format stack.

	@param port_stk Chaine du port au format stk-254XXX-YY/ZZ
	@return array Tableau [num_switch, num_port] ou tableau vide si invalide
*/
function donne_port($port_stk) {
	if (!verife_stk_standart($port_stk)) {
		return array();
	}
	$res = array();
	array_push($res, intval(substr($port_stk, 11, 2)));
	array_push($res, intval(substr($port_stk, 14, 2)));
	return $res;
}

// ==============================================================================
// Fonctions de decodage MySQL
// ==============================================================================

/**
	Decode un tableau de resultats MySQL en format exploitable.
	Separe les cles et valeurs en deux tableaux distincts.

	@param tab Tableau de resultats MySQL
	@return array Tableau decode avec structure [[cles], [valeurs]]
*/
function decode_tab_mysql($tab) {
	$res = array();
	$tmp1 = array();
	$tmp2 = array();
	foreach ($tab as $ligne) {
		$tmp = array();
		foreach ($ligne as $cle => $valeur) {
			array_push($tmp1, $cle);
			array_push($tmp2, $valeur);
		}
		array_push($tmp, $tmp1);
		array_push($tmp, $tmp2);
		array_push($res, $tmp);
	}
	return $res;
}

?>
