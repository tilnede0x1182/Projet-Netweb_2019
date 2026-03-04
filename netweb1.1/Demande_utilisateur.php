<?php
/**
	Fonctions d affichage du formulaire de recherche utilisateur.
*/

// ==============================================================================
// Formulaire de recherche
// ==============================================================================

/**
	Affiche le formulaire de recherche avec deux modes :
	- Recherche par IP, nom, inventaire ou MAC
	- Recherche par stack et port

	@param entree_par_defaut Valeur par defaut du champ de recherche principal
	@param stk_par_defaut Valeur par defaut du numero de stack
	@param port_par_default_1 Valeur par defaut du premier numero de port
	@param port_par_default_2 Valeur par defaut du second numero de port
*/
function affiche_formulaire($entree_par_defaut, $stk_par_defaut, $port_par_default_1, $port_par_default_2) {
	echo "\t\t".'<form action="Action.php" method="post">'."\n";
	echo "\t\t\t".'<p>IP, nom, nom d\'inventaire ou adresse MAC : <input value="'.$entree_par_defaut.'" type="text" name="entree_user"/>'."\n";
	echo "\t\t\t".'<input type="submit" value="Rechercher"/></p>'."\n";
	echo "\t\t".'</form>'."\n";
	echo "<p>OU</p>";
	echo "\t\t".'<form action="Action2.php" method="post">'."\n";
	echo "\t\t\t".'<p>N du stack <input value="'.$stk_par_defaut.'" type="text" name="stk" maxlength="3" size="3"/>'."\n";
	echo "\t\t\t".'port : <input value="'.$port_par_default_1.'" type="text" name="port_1" maxlength="2" size="2"/>'."\n";
	echo "\t\t\t".' / <input value="'.$port_par_default_2.'" type="text" name="port_2" maxlength="2" size="2"/>'."\n";
	echo "\t\t\t".'<input type="submit" value="Rechercher"/></p>'."\n";
	echo "\t\t".'</form>'."\n";
}

?>
