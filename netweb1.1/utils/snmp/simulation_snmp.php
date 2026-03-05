<?php
/**
 * Fonctions SNMP pour interroger les equipements reseau.
 * 
 * NOTE PORTFOLIO : Les appels SNMP reels (snmp2_get) sont commentes car ce projet
 * est presente sans infrastructure reseau. La fonction simule_snmp() genere des
 * donnees au format SNMP reel pour demontrer le fonctionnement de l application.
 * En presence d equipements reels, il suffit de decommenter l appel snmp2_get().
 */

// ==============================================================================
// Fonction de simulation SNMP
// ==============================================================================

/**
 * Simule une reponse SNMP avec des donnees au format reel.
 * Utilisee en l absence d equipements reseau reels.
 * 
 * @param string $ip Adresse IP de l equipement
 * @param string $oid OID SNMP demande
 * @return string Reponse au format SNMP (ex: "INTEGER: 1", "Gauge32: 100000000")
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
 * Interroge un equipement via SNMP et retourne la valeur de l OID demande.
 * 
 * NOTE : L appel reel snmp2_get() est commente car ce projet est presente
 * sans infrastructure reseau. En presence d equipements reels, decommenter
 * la ligne snmp2_get() et commenter l appel simule_snmp().
 * 
 * @param string $adresse_ip_equipement Adresse IP de l equipement
 * @param string $communaute_snmp Communaute SNMP (ex: "public", "private")
 * @param string $identifiant_objet_snmp Identifiant de l objet SNMP a interroger
 * @return string Valeur retournee par l equipement ou "Pas de reponse"
 */
function renvoie_objet_snmp($adresse_ip_equipement, $communaute_snmp, $identifiant_objet_snmp) {
	// APPEL REEL SNMP (commente - pas d'équipements disponibles)
	// $reponse_equipement = @snmp2_get($adresse_ip_equipement, $communaute_snmp, $identifiant_objet_snmp, 100, 3);

	// SIMULATION pour portfolio
	$reponse_equipement = simule_snmp($adresse_ip_equipement, $identifiant_objet_snmp);

	if (!$reponse_equipement) $reponse_equipement = "Pas de reponse";
	return $reponse_equipement;
}

/**
 * Retourne le VLAN attribue a un port de switch Avaya.
 * 
 * @param string $ip_switch Adresse IP du switch
 * @param int $numero_switch Numero du switch dans le stack
 * @param int $port_physique Numero du port
 * @param string $communaute_protocole_snmp Communaute SNMP
 * @return string Numero de VLAN ou "Pas de reponse"
 */
function renvoie_VLAN($ip_switch, $numero_switch, $port_physique, $communaute_protocole_snmp) {
		if (!verifie_marque_avaya($ip_switch, $communaute_protocole_snmp)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		return nettoie_res_snmp(renvoie_objet_snmp("$ip_switch", $communaute_protocole_snmp, "1.0.8802.1.1.2.1.5.32962.1.2.1.1.1.".calcul_numero_port_protocole_snmp($numero_switch, $port_physique)));
	}

/**
 * Retourne le status d un port de switch (up/down).
 * 
 * @param string ip_switch Adresse IP du switch
 * @param int numero_switch Numero du switch dans le stack
 * @param int port_physique Numero du port
 * @param string communaute_protocole_snmp Communaute SNMP
 * @return string Status du port (1=up, 2=down) ou "Pas de reponse"
 */
function renvoie_status_port($ip_switch, $numero_switch, $port_physique, $communaute_protocole_snmp) {
		if (!verifie_marque_avaya($ip_switch, $communaute_protocole_snmp)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		return nettoie_res_snmp(renvoie_objet_snmp("$ip_switch", $communaute_protocole_snmp, "1.3.6.1.2.1.2.2.1.8.".calcul_numero_port_protocole_snmp($numero_switch, $port_physique)));
	}

/**
 * Retourne la vitesse d un port de switch en bps.
 * 
 * @param string ip_switch Adresse IP du switch
 * @param int numero_switch Numero du switch dans le stack
 * @param int port_physique Numero du port
 * @param string communaute_protocole_snmp Communaute SNMP
 * @return string Vitesse du port en bps ou "Pas de reponse"
 */
function renvoie_vitesse_port($ip_switch, $numero_switch, $port_physique, $communaute_protocole_snmp) {
		if (!verifie_marque_avaya($ip_switch, $communaute_protocole_snmp)) {
			//echo("renvoie_VLAN : Attention, le switch demandé n'est pas de marque Avaya");
			return "Pas de réponse";
		}
		$reponse_snmp_brute = renvoie_objet_snmp("$ip_switch", $communaute_protocole_snmp, "1.3.6.1.2.1.2.2.1.5.".calcul_numero_port_protocole_snmp($numero_switch, $port_physique));
		return nettoie_res_snmp($reponse_snmp_brute);
	}

/**
 * Retourne les informations de marque d un switch.
 * 
 * @param string ip_switch Adresse IP du switch
 * @param string communaute_protocole_snmp Communaute SNMP
 * @return array Tableau contenant les informations de marque
 */
function renvoie_marque_switch($ip_switch, $communaute_protocole_snmp) {
		$infos_marque = array();
		for ($index_oid = 2; $index_oid < 9; $index_oid++) {
			array_push($infos_marque, renvoie_objet_snmp("$ip_switch", $communaute_protocole_snmp, "1.0.8802.1.1.2.1.5.4795.1.2.$index_oid.0"));
		}
		return $infos_marque;
	}

// ==============================================================================
// Fonctions d enrichissement des donnees
// ==============================================================================

/**
 * Enrichit les resultats de la BDD avec les donnees SNMP des equipements.
 * Ajoute le status, la vitesse et le VLAN pour chaque port.
 * 
 * @param array $resultats_bdd Tableau de resultats de la base de donnees
 * @param string communaute_protocole_snmp Communaute SNMP
 * @return array Tableau enrichi avec les donnees SNMP
 */
function enrichie_resultats_BDD_par_SNMP($resultats_bdd, $communaute_protocole_snmp) {
		$resultats_decodes = decode_tab_mysql($resultats_bdd);
		$resultats_enrichis = array();
		$index_equipement = 0;
		foreach ($resultats_decodes as $equipement_decode) {
			$colonnes_entetes = $equipement_decode[0];
			$colonnes_valeurs = $equipement_decode[1];

			$numero_stack = donne_stack($resultats_bdd[$index_equipement]['Port']);
			$numeros_port = donne_port($resultats_bdd[$index_equipement]['Port']);
			if ($numero_stack && $numeros_port) {
				array_push($colonnes_entetes, "Status du port");
				array_push($colonnes_valeurs, renvoie_status_port("10.149.254.".$numero_stack, $numeros_port[0], $numeros_port[1], $communaute_protocole_snmp));
				array_push($colonnes_entetes, "Vitesse du port");
				array_push($colonnes_valeurs, formate_vitesse_port(renvoie_vitesse_port("10.149.254.".$numero_stack, $numeros_port[0], $numeros_port[1], $communaute_protocole_snmp)));
				array_push($colonnes_entetes, "VLAN attribue a ce port");
				array_push($colonnes_valeurs, renvoie_VLAN("10.149.254.".$numero_stack, $numeros_port[0], $numeros_port[1], $communaute_protocole_snmp));
			}
			array_push($resultats_enrichis, array($colonnes_entetes, $colonnes_valeurs));
			$index_equipement = $index_equipement + 1;
		}
		return $resultats_enrichis;
	}

// ==============================================================================
// Fonctions de verification
// ==============================================================================

/**
 * Verifie si un switch est de marque Avaya.
 * 
 * @param string ip_switch Adresse IP du switch
 * @param string communaute_protocole_snmp Communaute SNMP
 * @return bool true si le switch est Avaya, false sinon
 */
function verifie_marque_avaya($ip_switch, $communaute_protocole_snmp) {
		$infos_marque = renvoie_marque_switch($ip_switch, $communaute_protocole_snmp);
		$chaine_marque_complete = "";
		foreach ($infos_marque as $fragment_marque) {
			$chaine_marque_complete = $chaine_marque_complete . $fragment_marque;
		}
		if (str_contains($chaine_marque_complete, "Avaya")) return true;
		else return false;
	}
?>