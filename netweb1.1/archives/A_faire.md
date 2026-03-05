# À faire

## Ce qui a été fait (2026-03-05)

- Réorganisation en architecture MVC (src/pages, utils/includes, utils/templates)
- Migration mysql_* → mysqli (connexion_db.php)
- Simulation SNMP (simulation_snmp.php génère des données fictives)
- Noms de fichiers explicites (Action.php → RechercheEquipement.php, etc.)
- Seed et create_tables.sql pour données de test
- README mis à jour avec instructions d'installation

## Ce qui n'est plus pertinent

- Bug strcmp vitesse : SNMP simulé, les valeurs sont générées correctement
- Bug SNMP "Pas de réponse" : Simulation retourne toujours une valeur
- Sécurité communauté SNMP : Pas de vraies requêtes SNMP
- Filtrage IP SNMP : Simulation, pas d'interrogation réseau

## Ce qui reste à faire (optionnel)

- Injection SQL : Les requêtes utilisent la concaténation, pas de requêtes préparées (priorité haute si production)
- DRY Action/Action2 : 90% de code dupliqué entre RechercheEquipement.php et RecherchePort.php
- Regrouper fichiers utils : fonctions_affichage.php et fonctions_snmp.php pourraient être fusionnés
- Identifiants MySQL en clair : config.php contient user/password en dur (acceptable pour portfolio)
