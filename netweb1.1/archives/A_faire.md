# À faire

## Ce qui a été fait (2026-03-05)

- Réorganisation en architecture MVC (src/pages, utils/includes, utils/templates)
- Migration mysql_* → mysqli (connexion_db.php)
- Simulation SNMP (simulation_snmp.php génère des données fictives)
- Noms de fichiers explicites (Action.php → RechercheEquipement.php, etc.)
- Seed et create_tables.sql pour données de test
- README mis à jour avec instructions d'installation
- Requêtes préparées (mysqli_prepare) pour éviter injection SQL
- Factorisation code commun dans fonctions_requetes.php (DRY)
- Renommage variables explicites ($stmt → $requete_preparee, $bdd → $nom_base_de_donnees, etc.)
- Documentation Javadoc avec @param type $nom sur toutes les fonctions
- Favicon ajouté

## Ce qui reste à faire (optionnel)

- Identifiants MySQL en clair : config.php contient user/password en dur → utiliser .env
