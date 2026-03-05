<?php
/**
 * Variables et constantes de configuration de l application.
 */

// ==============================================================================
// Chargement du .env
// ==============================================================================

/**
 *	Charge les variables d'environnement depuis un fichier .env
 *
 *	@param string $path Chemin vers le fichier .env
 */
function load_env_file($path) {
	if (!file_exists($path)) {
		return;
	}
	$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		$line = trim($line);
		if ($line === '' || $line[0] === '#') {
			continue;
		}
		$pos = strpos($line, '=');
		if ($pos === false) {
			continue;
		}
		$key = trim(substr($line, 0, $pos));
		$value = trim(substr($line, $pos + 1));
		$_ENV[$key] = $value;
		putenv("$key=$value");
	}
}

// Charger le .env depuis la racine du projet (2 niveaux au-dessus de netweb1.1)
load_env_file(dirname(dirname(dirname(__DIR__))) . '/.env');

// ==============================================================================
// Donnees
// ==============================================================================

// Chemin racine URL (pour les liens CSS/images dans le HTML)
$root_url = '/netweb/';

// Titre de la page
$titre_page = "Informations sur les equipements informatiques";

// Connexion a la base de donnees (depuis .env)
$adresse_hote_base_de_donnees = $_ENV['DB_HOST'] ?? 'localhost';
$utilisateur_base_de_donnees = $_ENV['DB_USER'] ?? '';
$mot_de_passe_base_de_donnees = $_ENV['DB_PASSWORD'] ?? '';
$nom_base_de_donnees = $_ENV['DB_NAME'] ?? '';

// Configuration SNMP
$communaute_protocole_snmp = "public";

?>
