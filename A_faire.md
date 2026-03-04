# À faire

## 1. Compréhension rapide
- `netweb1.1` regroupe les scripts PHP : `Action.php` et `Action2.php` traitent respectivement une recherche par nom/IP/MAC et une recherche par stack/ports.
- `Demande_utilisateur.php` construit le formulaire, `Affiche_page_html.php` la structure HTML (head/body), et `Affiche_html.php` formate les résultats en tableau.
- Les helpers (`Fonctions_utilitaires.php`, `fonctions_utilitaires_appliquees.php`) nettoient les réponses SNMP, remplissent les zéros et transforment les vitesses en texte.
- `VARIABLES_CONSTANTES.php` centralise les paramètres (nom de BDD, communauté SNMP, titre de page).
- `renvoie_objet_snmp.php` interroge les commutateurs pour enrichir les résultats SQL (état du port, VLAN, etc.).
- `Intéroge la base.php` établit la connexion MySQL, exécute la requête et renvoie un tableau associatif que `affiche_tab_donnee_html` présente.

## 2. Bugs à corriger
- `Fonctions_utilitaires.php::formate_vitesse_port` compare les chaînes avec `strcmp($vitesse, "10000000")` mais utilise le résultat comme booléen. `strcmp` renvoie 0 quand les chaînes sont égales, donc les vitesses «10 Mo»/«100 Mo» ne sont jamais converties, tandis que toute autre valeur est remplacée par «10 mo». Il faut tester `if (strcmp(...) == 0)`.
- `Action.php` et `Action2.php` construisent la requête SQL via concaténation. Un identifiant contenant des guillemets casse la requête. Migrer vers PDO ou au moins `mysql_real_escape_string` évitera les requêtes invalides.
- Lorsque SNMP ne répond pas, `enrichie_resultats_BDD_par_SNMP` mixe des chaînes «Pas de réponse» avec le tableau. `affiche_tab_donnee_html` ne gère pas ces erreurs et affiche des colonnes décalées. Ajouter un état «injoignable» explicite résout le décalage.

## 3. DRY en priorité
- Les deux actions (`Action.php`/`Action2.php`) dupliquent 90 % du flux (lecture POST, `affiche_debut_page_html`, enrichissement SNMP). Mutualiser une fonction `handle_request($builder)` limiterait la copie.
- `Fonctions_utilitaires.php` et `fonctions_utilitaires_appliquees.php` fournissent tous deux des wrappers `aff_tab*`, `aff_bool`. Les regrouper dans un seul fichier de debug clarifierait l’API utilitaire.

## 4. Sécurité
- Les identifiants MySQL et la communauté SNMP sont stockés en clair dans `VARIABLES_CONSTANTES.php`. Prévoir un fichier `.env` hors dépôt (ou des variables d’environnement) évite de diffuser des secrets.
- Les requêtes SQL sont construites à partir de l’entrée utilisateur sans paramètre préparé. Même si les champs sont `htmlspecialchars`, les quotes restent injectables. Utiliser PDO avec `?` est indispensable.
- `renvoie_objet_snmp.php` accepte n’importe quelle IP renvoyée par la BDD sans vérification ; si un enregistrement malicieux existe, on interroge un hôte arbitraire via la communauté fournie. Filtrer les IP sur un sous-réseau connu réduit ce risque.
