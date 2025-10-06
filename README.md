# Netweb1.1 – Consultation d’équipements réseau

Netweb1.1 est une application PHP front-end qui interroge une base MySQL et enrichit les résultats par SNMP pour afficher, dans une page web, les informations et l’état de ports de commutateurs. L’ensemble fonctionne sans framework : la logique SQL, SNMP et HTML est gérée par des scripts PHP modulaires.

## Technologies utilisées

- **PHP** (scripts `Action.php`, `Action2.php`, etc.)
- **MySQL** via l’extension `mysql_*`
- **SNMP** via `snmp2_get()`
- **HTML/CSS** pour présentation (fichier `Page.css`)
- **JavaScript** optionnel (pour PHPInfo et tests)
- Aucun framework

## Fonctionnalités

- Formulaire de recherche
  - Par nom, IP, inventaire ou MAC (`Action.php`)
  - Par numéro de stack et intervalle de ports (`Action2.php`)
- Requête SQL dynamique selon l’entrée utilisateur
- Enrichissement SNMP
  - Statut de port (up/down)
  - Vitesse du port (10 Mo/100 Mo)
  - VLAN assigné
- Affichage des résultats en tableau HTML
- Génération dynamique de l’en-tête et du pied de page HTML
- Tests unitaires basiques (`Tests.php`) et script de tests SNMP
- Configuration centralisée dans `VARIABLES_CONSTANTES.php`
