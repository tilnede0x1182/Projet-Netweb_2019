<?php
/**
	Script de seed pour la base de donnees netweb1.1.
	Genere 15 equipements reseau pour les tests SNMP.

	Usage :
		php seed.php
*/

// ==============================================================================
// Donnees
// ==============================================================================

$EQUIPEMENTS = [
	['name' => 'srv-web-01', 'domain' => 'servers.local', 'ip' => '192.168.1.1', 'mac' => '00:1A:2B:3C:4D:01', 'gpsnum' => 'INV-SRV-001', 'type' => 'Serveur', 'dns' => 'active', 'ug' => 'IT', 'affect' => 'Production', 'desc' => 'Serveur web principal', 'location' => 'Datacenter A1', 'switchport' => 'stk-254001[01/01]'],
	['name' => 'srv-db-01', 'domain' => 'servers.local', 'ip' => '192.168.1.2', 'mac' => '00:1A:2B:3C:4D:02', 'gpsnum' => 'INV-SRV-002', 'type' => 'Serveur', 'dns' => 'active', 'ug' => 'IT', 'affect' => 'Production', 'desc' => 'Serveur base de donnees', 'location' => 'Datacenter A2', 'switchport' => 'stk-254001[01/02]'],
	['name' => 'srv-mail-01', 'domain' => 'servers.local', 'ip' => '192.168.1.3', 'mac' => '00:1A:2B:3C:4D:03', 'gpsnum' => 'INV-SRV-003', 'type' => 'Serveur', 'dns' => 'active', 'ug' => 'IT', 'affect' => 'Production', 'desc' => 'Serveur mail', 'location' => 'Datacenter A3', 'switchport' => 'stk-254001[01/03]'],
	['name' => 'router-core', 'domain' => 'network.local', 'ip' => '192.168.1.10', 'mac' => '00:1A:2B:3C:4D:10', 'gpsnum' => 'INV-RTR-001', 'type' => 'Routeur', 'dns' => 'active', 'ug' => 'Reseau', 'affect' => 'Infrastructure', 'desc' => 'Routeur principal', 'location' => 'Salle reseau', 'switchport' => 'stk-254001[02/01]'],
	['name' => 'switch-dist-01', 'domain' => 'network.local', 'ip' => '192.168.1.11', 'mac' => '00:1A:2B:3C:4D:11', 'gpsnum' => 'INV-SWT-001', 'type' => 'Switch', 'dns' => 'active', 'ug' => 'Reseau', 'affect' => 'Infrastructure', 'desc' => 'Switch distribution', 'location' => 'Salle reseau', 'switchport' => 'stk-254001[02/02]'],
	['name' => 'switch-access-01', 'domain' => 'network.local', 'ip' => '192.168.1.12', 'mac' => '00:1A:2B:3C:4D:12', 'gpsnum' => 'INV-SWT-002', 'type' => 'Switch', 'dns' => 'active', 'ug' => 'Reseau', 'affect' => 'Infrastructure', 'desc' => 'Switch acces Bat A', 'location' => 'Batiment A', 'switchport' => 'stk-254001[02/03]'],
	['name' => 'fw-ext-01', 'domain' => 'security.local', 'ip' => '192.168.1.20', 'mac' => '00:1A:2B:3C:4D:20', 'gpsnum' => 'INV-FW-001', 'type' => 'Firewall', 'dns' => 'active', 'ug' => 'Securite', 'affect' => 'Perimetre', 'desc' => 'Firewall externe', 'location' => 'DMZ', 'switchport' => 'stk-254002[01/01]'],
	['name' => 'pc-admin-01', 'domain' => 'workstations.local', 'ip' => '192.168.2.1', 'mac' => '00:1A:2B:3C:5D:01', 'gpsnum' => 'INV-PC-001', 'type' => 'PC', 'dns' => 'active', 'ug' => 'Admin', 'affect' => 'Bureau 101', 'desc' => 'PC administrateur', 'location' => 'Bureau 101', 'switchport' => 'stk-254003[01/01]'],
	['name' => 'pc-compta-01', 'domain' => 'workstations.local', 'ip' => '192.168.2.2', 'mac' => '00:1A:2B:3C:5D:02', 'gpsnum' => 'INV-PC-002', 'type' => 'PC', 'dns' => 'active', 'ug' => 'Compta', 'affect' => 'Bureau 102', 'desc' => 'PC comptabilite', 'location' => 'Bureau 102', 'switchport' => 'stk-254003[01/02]'],
	['name' => 'pc-rh-01', 'domain' => 'workstations.local', 'ip' => '192.168.2.3', 'mac' => '00:1A:2B:3C:5D:03', 'gpsnum' => 'INV-PC-003', 'type' => 'PC', 'dns' => 'active', 'ug' => 'RH', 'affect' => 'Bureau 103', 'desc' => 'PC ressources humaines', 'location' => 'Bureau 103', 'switchport' => 'stk-254003[01/03]'],
	['name' => 'printer-rdc', 'domain' => 'periph.local', 'ip' => '192.168.3.1', 'mac' => '00:1A:2B:3C:6D:01', 'gpsnum' => 'INV-PRT-001', 'type' => 'Imprimante', 'dns' => 'active', 'ug' => 'Services', 'affect' => 'RDC', 'desc' => 'Imprimante RDC', 'location' => 'Couloir RDC', 'switchport' => 'stk-254003[02/01]'],
	['name' => 'printer-etage1', 'domain' => 'periph.local', 'ip' => '192.168.3.2', 'mac' => '00:1A:2B:3C:6D:02', 'gpsnum' => 'INV-PRT-002', 'type' => 'Imprimante', 'dns' => 'active', 'ug' => 'Services', 'affect' => 'Etage 1', 'desc' => 'Imprimante Etage 1', 'location' => 'Couloir Etage 1', 'switchport' => 'stk-254003[02/02]'],
	['name' => 'ap-wifi-01', 'domain' => 'network.local', 'ip' => '192.168.4.1', 'mac' => '00:1A:2B:3C:7D:01', 'gpsnum' => 'INV-AP-001', 'type' => 'Borne WiFi', 'dns' => 'active', 'ug' => 'Reseau', 'affect' => 'Sans fil', 'desc' => 'Borne WiFi RDC', 'location' => 'Hall RDC', 'switchport' => 'stk-254002[02/01]'],
	['name' => 'ap-wifi-02', 'domain' => 'network.local', 'ip' => '192.168.4.2', 'mac' => '00:1A:2B:3C:7D:02', 'gpsnum' => 'INV-AP-002', 'type' => 'Borne WiFi', 'dns' => 'active', 'ug' => 'Reseau', 'affect' => 'Sans fil', 'desc' => 'Borne WiFi Etage 1', 'location' => 'Hall Etage 1', 'switchport' => 'stk-254002[02/02]'],
	['name' => 'nas-backup', 'domain' => 'storage.local', 'ip' => '192.168.5.1', 'mac' => '00:1A:2B:3C:8D:01', 'gpsnum' => 'INV-NAS-001', 'type' => 'NAS', 'dns' => 'active', 'ug' => 'IT', 'affect' => 'Sauvegarde', 'desc' => 'NAS de sauvegarde', 'location' => 'Datacenter B1', 'switchport' => 'stk-254001[03/01]']
];

// ==============================================================================
// Main
// ==============================================================================

/**
	Point d entree du script.
	Cree les tables et insere les equipements.
*/
function main() {
	$debut = microtime(true);
	echo "=== Seed netweb1.1 ===\n\n";

	$connexion = mysqli_connect('localhost', 'tilnede0x1182', 'tilnede0x1182', 'netweb1.1');
	if (!$connexion) {
		die("Erreur de connexion: " . mysqli_connect_error() . "\n");
	}

	mysqli_set_charset($connexion, 'utf8mb4');

	// Execution du script de creation des tables
	$sqlFile = __DIR__ . '/create_tables.sql';
	$sql = file_get_contents($sqlFile);
	mysqli_multi_query($connexion, $sql);
	while (mysqli_next_result($connexion)) {;}
	echo "Tables creees.\n";

	// Insertion des equipements
	global $EQUIPEMENTS;
	$usersTxt = "=== EQUIPEMENTS RESEAU ===\n\n";
	$usersTxt .= sprintf("%-18s %-15s %-20s %s\n", "NOM", "IP", "MAC", "INVENTAIRE");
	$usersTxt .= str_repeat("-", 75) . "\n";

	foreach ($EQUIPEMENTS as $equipement) {
		$name = mysqli_real_escape_string($connexion, $equipement['name']);
		$domain = mysqli_real_escape_string($connexion, $equipement['domain']);
		$ip = mysqli_real_escape_string($connexion, $equipement['ip']);
		$mac = mysqli_real_escape_string($connexion, $equipement['mac']);
		$gpsnum = mysqli_real_escape_string($connexion, $equipement['gpsnum']);
		$type = mysqli_real_escape_string($connexion, $equipement['type']);
		$dns = mysqli_real_escape_string($connexion, $equipement['dns']);
		$ug = mysqli_real_escape_string($connexion, $equipement['ug']);
		$affect = mysqli_real_escape_string($connexion, $equipement['affect']);
		$desc = mysqli_real_escape_string($connexion, $equipement['desc']);
		$location = mysqli_real_escape_string($connexion, $equipement['location']);
		$switchport = mysqli_real_escape_string($connexion, $equipement['switchport']);

		// Insertion dans ip_host
		$sql = "INSERT INTO ip_host (iph_name, iph_domain, iph_ether, iph_gpsnum, iph_type, iph_dnsstate, iph_ug, iph_affect, iph_desc, iph_location, iph_switchport)
				VALUES ('$name', '$domain', '$mac', '$gpsnum', '$type', '$dns', '$ug', '$affect', '$desc', '$location', '$switchport')";
		mysqli_query($connexion, $sql);
		$client_id = mysqli_insert_id($connexion);

		// Insertion dans ip_address
		$sql = "INSERT INTO ip_address (ipa_addr, ipa_client, ipa_vlanid, ipa_status, ipa_dhcp)
				VALUES ('$ip', $client_id, 100, 'allocated', 'true')";
		mysqli_query($connexion, $sql);

		$usersTxt .= sprintf("%-18s %-15s %-20s %s\n", $name, $ip, $mac, $gpsnum);
	}

	echo "Equipements inseres: " . count($EQUIPEMENTS) . "\n";

	// Ecriture du fichier users.txt
	$cheminUsers = __DIR__ . '/users.txt';
	file_put_contents($cheminUsers, $usersTxt);
	echo "\nFichier users.txt cree: $cheminUsers\n";

	mysqli_close($connexion);

	$fin = microtime(true);
	$duree = round($fin - $debut, 2);
	echo "\n=== Seed termine en {$duree}s ===\n";
}

// ==============================================================================
// Lancement du programme
// ==============================================================================

main();
