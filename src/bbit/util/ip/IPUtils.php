<?php

namespace bbit\util\ip;

class IPUtils {

	public static function isIPOrCIDR($cidr) {
		list($net, $mask) = explode('/', $cidr, 2);
		$net = inet_pton($net);
		$len = strlen($net) * 8;
		return $net && $mask >= 0 && $mask <= $len;
	}

	public static function isIPWithinCIDR($cidr, $ip = null) {
		$ip === null && $ip = $_SERVER['REMOTE_ADDR'];
		$ip = inet_pton($ip);
		if(!$ip) {
			return null;
		}
		list($net, $mask) = explode('/', $cidr, 2);
		$net = inet_pton($net);
		if(!$net) {
			return null;
		}
		$len = strlen($ip);
		$mask = max(0, min($len * 8, intval($mask)));
		if($mask) {
			$mask = str_repeat("\xFF", floor($mask / 8));
			($shift = $mask % 8) && $mask .= chr(255 << $shift);
			$mask .= str_repeat("\0", $len - strlen($mask));
		} else {
			$mask = str_repeat("\xFF", $len);
		}
		return ($ip & $mask) === ($net & $mask);
	}

}
