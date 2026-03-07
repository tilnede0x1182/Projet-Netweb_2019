# Netweb1.1 – Consultation d'équipements réseau

Netweb 1.1 est une application web de consultation d’équipements réseau via SNMP et d’une base de données d’informations sur les IP d’un réseau privé d’organisation.
Le projet a été réalisé lors d’un stage pour enrichir une application existante (Netweb) en ajoutant la capacité d’obtenir des informations sur les switches, en plus des informations disponibles en base de données sur les équipements réseau.

Le travail a consisté à recréer la partie consultation de la base de données puis à l’enrichir avec des données issues des switches.

Par la suite,j'ai refactoré le porejt pour ajouter des données fictives permettant de tester l’application.

L’application interroge une base MySQL contenant des informations sur les équipements réseau (switches) et enrichit les résultats avec des données SNMP (statut de port, vitesse, VLAN).

Un refactoring supplémentaire a permis de moderniser la structure du code (architecture MVC), de migrer vers mysqli (PHP 7+) et d’ajouter une simulation SNMP afin que l’application puisse fonctionner sans infrastructure réseau réelle.

## Fonctionnalités

- **Recherche par identifiant** : Recherche d'équipements par adresse IP, nom, numéro d'inventaire ou adresse MAC.
- **Recherche par port** : Recherche d'équipements connectés à un stack et port de switch spécifique.
- **Enrichissement SNMP** : Affichage du statut de port (up/down), de la vitesse (10/100/1000 Mbps) et du VLAN assigné.
- **Simulation SNMP** : Pour test sans infrastructure réelle : génération de données SNMP réalistes.
- **Affichage des résultats** : Tableau HTML avec toutes les informations de l'équipement.
- **Configuration centralisée** : Paramètres BDD et SNMP dans un fichier de configuration unique.

## Technologies

- PHP 8.1 (mysqli)
- MySQL 8.0
- HTML5 / CSS3
- Apache

## Installation

### WAMP (Windows)

1. Placer le projet dans `C:\wamp64\www\netweb\`
2. Démarrer WAMP et accéder à phpMyAdmin
3. Créer la base de données `netweb1.1`
4. Lancer le seed :
   ```
   php netweb1.1/data/seed.php
   ```
5. Accéder au site : http://localhost/netweb/

### XAMPP (Windows / macOS)

1. Placer le projet dans `C:\xampp\htdocs\netweb\` (Windows) ou `/Applications/XAMPP/htdocs/netweb/` (macOS)
2. Démarrer Apache et MySQL depuis le panneau XAMPP
3. Créer la base de données `netweb1.1` via phpMyAdmin
4. Lancer le seed :
   ```
   php netweb1.1/data/seed.php
   ```
5. Accéder au site : http://localhost/netweb/

### LAMP (Linux)

1. Placer le projet dans `/var/www/html/netweb/`
2. Créer la base de données :
   ```
   sudo mysql -e "CREATE DATABASE \`netweb1.1\`; GRANT ALL PRIVILEGES ON \`netweb1.1\`.* TO 'utilisateur'@'localhost'; FLUSH PRIVILEGES;"
   ```
3. Lancer le seed :
   ```
   php netweb1.1/data/seed.php
   ```
4. Accéder au site : http://localhost/netweb/

## Identifiants de test

Les équipements générés par le seed sont disponibles dans `netweb1.1/data/données_de_test.txt` tester.
