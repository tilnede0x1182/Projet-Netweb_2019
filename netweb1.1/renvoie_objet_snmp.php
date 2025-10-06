<?php

	function renvoie_objet_snmp ($ip, $communaute, $OID) {
		$res = @snmp2_get($ip, $communaute, $OID, 100, 3);
		if (!$res) $res = "Pas de réponse";

		return $res;
	}

	function renvoie_VLAN ($ip_switch, $num_switch, $port, $communaute_cst){
		if (!verifie_marque_avaya($ip_switch, $communaute_cst)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		return nettoie_res_snmp(renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.0.8802.1.1.2.1.5.32962.1.2.1.1.1.".calcul_numero_port_snmp($num_switch, $port)));
	}

	function renvoie_status_port ($ip_switch, $num_switch, $port, $communaute_cst) {
		if (!verifie_marque_avaya($ip_switch, $communaute_cst)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		return nettoie_res_snmp(renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.3.6.1.2.1.2.2.1.8.".calcul_numero_port_snmp($num_switch, $port)));
	}

	function renvoie_vitesse_port ($ip_switch, $num_switch, $port, $communaute_cst) {
		if (!verifie_marque_avaya($ip_switch, $communaute_cst)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		$tmp = renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.3.6.1.2.1.2.2.1.5.".calcul_numero_port_snmp($num_switch, $port));
		return nettoie_res_snmp($tmp);
	}

	function renvoie_marque_switch ($ip_switch, $communaute_cst) {
		$res = array();
		for ($i = 2; $i < 9; $i++) {
			array_push($res, renvoie_objet_snmp("$ip_switch", $communaute_cst, "1.0.8802.1.1.2.1.5.4795.1.2.$i.0"));
		}
		return $res;
	}

	// ######### Ajoute les réulstats SNMP aux résultats donnés par la BDD ###########################################

	function enrichie_resultats_BDD_par_SNMP ($tab_0, $communaute_cst) {
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

	//########## Donne marque du Switch ##############################################################################

	function verifie_marque_avaya ($ip_switch, $communaute_cst) {
		$tmp = renvoie_marque_switch ($ip_switch, $communaute_cst);
		$str_0 = "";
		foreach ($tmp as $i) {
			$str_0 = $str_0.$i;
		}
		if (str_contains($str_0, "Avaya")) return true;
		else return false;
	}
?>