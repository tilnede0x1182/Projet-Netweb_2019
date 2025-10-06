<?php

	function calcul_numero_port_snmp ($num_switch, $port) {
		if ($port<0 || $port>50) {
			print("calcul_numero_port_snmp : Erreur, port<0 ou port>50");
			return $port;
		}
		if ($num_switch<1 || $num_switch>4) {
			print("calcul_numero_port_snmp : Erreur, num_switch<1 ou num_switch>4");
			return $port;
		}
		if ($num_switch==1) {
			return $port;
		}
		if ($num_switch==2) {
			return ($port+64);
		}
		if ($num_switch==3) {
			return ($port+128);
		}
		if ($num_switch==4) {
			return ($port+192);
		}
	}

	function verife_stk_standart ($port_stk) {
		if (strcmp(substr($port_stk, 0, 7), "stk-254")!=0) return false;
		return true;
	}

	function donne_stack($port_stk) {
		if (!verife_stk_standart($port_stk)) {
			//echo('donne_stack : Erreur, le stack interrogé n'."'".'est pas noté stk-254*'."\n");
			return -1;
		}
		$tmp = substr($port_stk, 7, 3);
		$res = intval($tmp);
		return $res;
	}

	function donne_port($port_stk) {
		if (!verife_stk_standart($port_stk)) {
			//echo('donne_stack : Erreur, le stack interrogé n'."'".'est pas noté stk-254*'."\n");
			return array();
		}
		$res = array();
		array_push($res, intval(substr($port_stk, 11, 2)));
		array_push($res, intval(substr($port_stk, 14, 2)));
		return $res;
	}

	function decode_tab_mysql ($tab) {
		$res = array();
		$tmp1 = array();
		$tmp2 = array();
		foreach ($tab as $i) {
			$tmp = array();
			foreach ($i as $k => $j) {
				array_push($tmp1, $k);
				array_push($tmp2, $j);
			}
			array_push($tmp, $tmp1);
			array_push($tmp, $tmp2);
			array_push($res, $tmp);
		}
		return $res;
	}

?>