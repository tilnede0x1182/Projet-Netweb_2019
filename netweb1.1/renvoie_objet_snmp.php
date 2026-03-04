<?php
/**
	Fonctions SNMP pour interroger les equipements reseau.

	NOTE PORTFOLIO : Les appels SNMP reels (snmp2_get) sont commentes car ce projet
	est presente sans infrastructure reseau. La fonction simule_snmp() genere des
	donnees au format SNMP reel pour demontrer le fonctionnement de l application.
	En presence d equipements reels, il suffit de decommenter l appel snmp2_get().
*/

// ==============================================================================
// Fonction de simulation SNMP
// ==============================================================================

/**
	Simule une reponse SNMP avec des donnees au format reel.
	Utilisee en l absence d equipements reseau reels.

	@param ip Adresse IP de l equipement
	@param oid OID SNMP demande
	@return string Reponse au format SNMP (ex: "INTEGER: 1", "Gauge32: 100000000")
*/
function simule_snmp($ip, $oid) {
	// OID status port (1.3.6.1.2.1.2.2.1.8.x) -> INTEGER: 1 (up) ou 2 (down)
	if (str_contains($oid, "1.3.6.1.2.1.2.2.1.8")) {
		$status = rand(1, 10) > 2 ? 1 : 2;
		return "INTEGER: " . $status;
	}

	// OID vitesse port (1.3.6.1.2.1.2.2.1.5.x) -> Gauge32: vitesse en bps
	if (str_contains($oid, "1.3.6.1.2.1.2.2.1.5")) {
		$vitesses = [10000000, 100000000, 1000000000];
		return "Gauge32: " . $vitesses[array_rand($vitesses)];
	}

	// OID VLAN (1.0.8802.1.1.2.1.5.32962.x) -> INTEGER: numero VLAN
	if (str_contains($oid, "1.0.8802.1.1.2.1.5.32962")) {
		$vlan = rand(10, 200);
		return "INTEGER: " . $vlan;
	}

	// OID marque switch (1.0.8802.1.1.2.1.5.4795.x) -> STRING: marque
	if (str_contains($oid, "1.0.8802.1.1.2.1.5.4795")) {
		return "STRING: Avaya";
	}

	// OID inconnu -> reponse generique
	return "STRING: Valeur simulee pour " . $ip;
}

// ==============================================================================
// Fonctions SNMP principales
// ==============================================================================

/**
	Interroge un equipement via SNMP et retourne la valeur de l OID demande.

	NOTE : L appel reel snmp2_get() est commente car ce projet est presente
	sans infrastructure reseau. En presence d equipements reels, decommenter
	la ligne snmp2_get() et commenter l appel simule_snmp().

	@param ip Adresse IP de l equipement
	@param communaute Communaute SNMP (ex: "public", "private")
	@param OID Identifiant de l objet SNMP a interroger
	@return string Valeur retournee par l equipement ou "Pas de reponse"
*/
function renvoie_objet_snmp($ip, $communaute, $OID) {
	// APPEL REEL SNMP (commente - pas d equipements disponibles)
	// $res = @snmp2_get($ip, $communaute, $OID, 100, 3);

	// SIMULATION pour portfolio
	$res = simule_snmp($ip, $OID);

	if (!$res) $res = "Pas de reponse";
	return $res;
}

/**
	Retourne le VLAN attribue a un port de switch Avaya.

	@param ip_switch Adresse IP du switch
	@param num_switch Numero du switch dans le stack
	@param port Numero du port
	@param communaute_cst Communaute SNMP
	@return string Numero de VLAN ou "Pas de reponse"
*/
function renvoie_VLAN($ip_switch, $num_switch, $port, $communaute_cst) {
		if (!verifie_marque_avaya($ip_switch, $communaute_cst)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		return nettoie_res_snmp(renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.0.8802.1.1.2.1.5.32962.1.2.1.1.1.".calcul_numero_port_snmp($num_switch, $port)));
	}

/**
	Retourne le status d un port de switch (up/down).

	@param ip_switch Adresse IP du switch
	@param num_switch Numero du switch dans le stack
	@param port Numero du port
	@param communaute_cst Communaute SNMP
	@return string Status du port (1=up, 2=down) ou "Pas de reponse"
*/
function renvoie_status_port($ip_switch, $num_switch, $port, $communaute_cst) {
		if (!verifie_marque_avaya($ip_switch, $communaute_cst)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		return nettoie_res_snmp(renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.3.6.1.2.1.2.2.1.8.".calcul_numero_port_snmp($num_switch, $port)));
	}

/**
	Retourne la vitesse d un port de switch en bps.

	@param ip_switch Adresse IP du switch
	@param num_switch Numero du switch dans le stack
	@param port Numero du port
	@param communaute_cst Communaute SNMP
	@return string Vitesse du port en bps ou "Pas de reponse"
*/
function renvoie_vitesse_port($ip_switch, $num_switch, $port, $communaute_cst) {
		if (!verifie_marque_avaya($ip_switch, $communaute_cst)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		$tmp = renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.3.6.1.2.1.2.2.1.5.".calcul_numero_port_snmp($num_switch, $port));
		return nettoie_res_snmp($tmp);
	}

/**
	Retourne les informations de marque d un switch.

	@param ip_switch Adresse IP du switch
	@param communaute_cst Communaute SNMP
	@return array Tableau contenant les informations de marque
*/
function renvoie_marque_switch($ip_switch, $communaute_cst) {
		$res = array();
		for ($i = 2; $i < 9; $i++) {
			array_push($res, renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.0.8802.1.1.2.1.5.4795.1.2.$i.0"));
		}
		return $res;
	}

// ==============================================================================
// Fonctions d enrichissement des donnees
// ==============================================================================

/**
	Enrichit les resultats de la BDD avec les donnees SNMP des equipements.
	Ajoute le status, la vitesse et le VLAN pour chaque port.

	@param tab_0 Tableau de resultats de la base de donnees
	@param communaute_cst Communaute SNMP
	@return array Tableau enrichi avec les donnees SNMP
*/
function enrichie_resultats_BDD_par_SNMP($tab_0, $communaute_cst) {
		$tab = decode_tab_mysql($tab_0);
		$res = array();
		$cmp = 0;
		foreach ($tab as $i) {
			$tmp1 = $i[0];
			$tmp2 = $i[1];

			$stk = donne_stack($tab_0[$cmp]['Port']);
			$port = donne_port($tab_0[$cmp]['Port']);
			if ($stk && $port) {
				array_push($tmp1, "Status du port");
				array_push($tmp2, renvoie_status_port("10.149.254.".$stk, $port[0], $port[1], $communaute_cst));
				array_push($tmp1, "Vitesse du port");
				array_push($tmp2, formate_vitesse_port(renvoie_vitesse_port("10.149.254.".$stk, $port[0], $port[1], $communaute_cst)));
				array_push($tmp1, "VLAN attribué à ce port");
				array_push($tmp2,  renvoie_VLAN("10.149.254.".$stk, $port[0], $port[1], $communaute_cst));
			}
			array_push($res, array($tmp1, $tmp2));
			$cmp = $cmp+1;
		}
		return $res;
	}

// ==============================================================================
// Fonctions de verification
// ==============================================================================

/**
	Verifie si un switch est de marque Avaya.

	@param ip_switch Adresse IP du switch
	@param communaute_cst Communaute SNMP
	@return bool true si le switch est Avaya, false sinon
*/
function verifie_marque_avaya($ip_switch, $communaute_cst) {
		$tmp = renvoie_marque_switch ($ip_switch, $communaute_cst);
		$str_0 = "";
		foreach ($tmp as $i) {
			$str_0 = $str_0.$i;
		}
		if (str_contains($str_0, "Avaya")) return true;
		else return false;
	}
?>