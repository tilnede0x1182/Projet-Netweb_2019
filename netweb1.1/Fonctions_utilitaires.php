<?php

	function aff_tab($tab) {
		foreach ($tab as $i) {
			echo($i."\n");
		}
	}

	function aff_tab_2_dimensions($tab) {
		foreach ($tab as $i) {
			foreach ($i as $j) {
				echo($j."\n");
			}
		}
	}

	function aff_tab_res_mysql($tab) {
		foreach ($tab as $i) {
			foreach ($i as $k => $j) {
				echo($k." : ".$j."\n");
			}
		}
	}

	function aff_bool ($tmp) {
		if ($tmp) echo ("1\n");
		else echo ("0\n");
	}

	function nettoie_res_snmp ($tmp) {
		if (strcmp($tmp, "Pas de réponse")==0) return $tmp;
		$tmp_len = strlen($tmp);
		$i = 0;
		if (str_contains($tmp, ":")) {
			while ($i < $tmp_len && strcmp(substr($tmp, $i, 1), ":")!=0) {
				$i = $i+1;
			}
			$i = $i+1;
		}
		$res = "";
		while ($i < $tmp_len) {
			if (is_numeric(substr($tmp, $i, 1))) {
				$res = $res.substr($tmp, $i, 1);
			}
			$i = $i+1;
		}
		return $res;
	}

	function remplit_nombre_zeros ($nombre, $nbr_zeros) {
		$a = $nbr_zeros; $tmp = "".$nombre;

		$tmplen = strlen($tmp);

		while ($tmplen<$a) {
			$tmp = "0".$tmp;
			$tmplen = strlen($tmp);
		}
		$res = $tmp;
		return $res;
	}

	function formate_vitesse_port($vitesse) {
		$res = $vitesse;
		if (strlen($vitesse)>0) {
			if (strcmp($vitesse, "10000000")) $res = "10 mo";
			if (strcmp($vitesse, "100000000")) $res = "100 mo";
		}
		return $res;
	}

	function str_contains ($chaine, $chaine_a_chercher) {
		$a = $chaine_a_chercher; $b =$chaine;
		$a_str = strlen($a);
		$b_str = strlen($b);

		for ($i = 0; $i <= $b_str-$a_str; $i++) {
			if (strcmp(substr($b, $i, $a_str), $a)==0) return true;
		}
		return false;
	}
?>