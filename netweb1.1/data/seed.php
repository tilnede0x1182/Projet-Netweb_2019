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
	['name' => 'router-core-01', 'domain' => 'network.local', 'ip' => '192.168.1.1', 'community' => 'public', 'version' => 2, 'description' => 'Routeur principal du reseau'],
	['name' => 'router-backup-01', 'domain' => 'network.local', 'ip' => '192.168.1.2', 'community' => 'public', 'version' => 2, 'description' => 'Routeur de secours'],
	['name' => 'switch-dist-01', 'domain' => 'network.local', 'ip' => '192.168.1.10', 'community' => 'public', 'version' => 2, 'description' => 'Switch de distribution niveau 1'],
	['name' => 'switch-dist-02', 'domain' => 'network.local', 'ip' => '192.168.1.11', 'community' => 'public', 'version' => 2, 'description' => 'Switch de distribution niveau 2'],
	['name' => 'switch-access-01', 'domain' => 'network.local', 'ip' => '192.168.1.20', 'community' => 'private', 'version' => 1, 'description' => 'Switch acces batiment A'],
	['name' => 'switch-access-02', 'domain' => 'network.local', 'ip' => '192.168.1.21', 'community' => 'private', 'version' => 1, 'description' => 'Switch acces batiment B'],
	['name' => 'switch-access-03', 'domain' => 'network.local', 'ip' => '192.168.1.22', 'community' => 'private', 'version' => 1, 'description' => 'Switch acces batiment C'],
	['name' => 'srv-web-01', 'domain' => 'servers.local', 'ip' => '192.168.2.10', 'community' => 'public', 'version' => 2, 'description' => 'Serveur web principal'],
	['name' => 'srv-db-01', 'domain' => 'servers.local', 'ip' => '192.168.2.20', 'community' => 'private', 'version' => 3, 'description' => 'Serveur base de donnees'],
	['name' => 'srv-mail-01', 'domain' => 'servers.local', 'ip' => '192.168.2.30', 'community' => 'public', 'version' => 2, 'description' => 'Serveur mail'],
	['name' => 'srv-file-01', 'domain' => 'servers.local', 'ip' => '192.168.2.40', 'community' => 'public', 'version' => 2, 'description' => 'Serveur de fichiers'],
	['name' => 'fw-ext-01', 'domain' => 'security.local', 'ip' => '192.168.0.1', 'community' => 'private', 'version' => 3, 'description' => 'Firewall externe'],
	['name' => 'fw-int-01', 'domain' => 'security.local', 'ip' => '192.168.0.2', 'community' => 'private', 'version' => 3, 'description' => 'Firewall interne'],
	['name' => 'printer-rdc-01', 'domain' => 'periph.local', 'ip' => '192.168.3.10', 'community' => 'public', 'version' => 1, 'description' => 'Imprimante RDC'],
	['name' => 'printer-etage1-01', 'domain' => 'periph.local', 'ip' => '192.168.3.11', 'community' => 'public', 'version' => 1, 'description' => 'Imprimante Etage 1']
];

// ==============================================================================
// Main
// ==============================================================================

/**
	Point d'entree du script.
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
	$usersTxt .= sprintf("%-20s %-20s %-15s %-15s %s\n", "NOM", "DOMAINE", "IP", "COMMUNAUTE", "VERSION");
	$usersTxt .= str_repeat("-", 90) . "\n";

	foreach ($EQUIPEMENTS as $equipement) {
		$name = mysqli_real_escape_string($connexion, $equipement['name']);
		$domain = mysqli_real_escape_string($connexion, $equipement['domain']);
		$ip = mysqli_real_escape_string($connexion, $equipement['ip']);
		$community = mysqli_real_escape_string($connexion, $equipement['community']);
		$version = (int) $equipement['version'];
		$description = mysqli_real_escape_string($connexion, $equipement['description']);

		$sql = "INSERT INTO ip_host (iph_name, iph_domain, iph_ip, iph_community, iph_version, iph_description)
				VALUES ('$name', '$domain', '$ip', '$community', $version, '$description')";
		mysqli_query($connexion, $sql);

		$usersTxt .= sprintf("%-20s %-20s %-15s %-15s %d\n", $name, $domain, $ip, $community, $version);
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
