<?php
/**
 * Fonctions d affichage du formulaire de recherche utilisateur.
 */

// ==============================================================================
// Formulaire de recherche
// ==============================================================================

/**
 * Affiche le formulaire de recherche avec deux modes :
 * - Recherche par IP, nom, inventaire ou MAC
 * - Recherche par stack et port
 * 
 * @param string $entree_par_defaut Valeur par defaut du champ de recherche principal
 * @param string $stack_par_defaut Valeur par defaut du numero de stack
 * @param string $port_debut_par_defaut Valeur par defaut du premier numero de port
 * @param string $port_fin_par_defaut Valeur par defaut du second numero de port
 */
function affiche_formulaire($entree_par_defaut, $stack_par_defaut, $port_debut_par_defaut, $port_fin_par_defaut) {
	global $root_url;
	echo "\t\t".'<form action="'.$root_url.'src/pages/RechercheEquipement.php" method="post">'."\n";
	echo "\t\t\t".'<p>IP, nom, nom d\'inventaire ou adresse MAC : <input value="'.$entree_par_defaut.'" type="text" name="entree_user"/>'."\n";
	echo "\t\t\t".'<input type="submit" value="Rechercher"/></p>'."\n";
	echo "\t\t".'</form>'."\n";
	echo "<p>OU</p>";
	echo "\t\t".'<form action="'.$root_url.'src/pages/RecherchePort.php" method="post">'."\n";
	echo "\t\t\t".'<p>N du stack <input value="'.$stack_par_defaut.'" type="text" name="numero_stack" maxlength="3" size="3"/>'."\n";
	echo "\t\t\t".'port : <input value="'.$port_debut_par_defaut.'" type="text" name="port_debut" maxlength="2" size="2"/>'."\n";
	echo "\t\t\t".' / <input value="'.$port_fin_par_defaut.'" type="text" name="port_fin" maxlength="2" size="2"/>'."\n";
	echo "\t\t\t".'<input type="submit" value="Rechercher"/></p>'."\n";
	echo "\t\t".'</form>'."\n";
}

?>
