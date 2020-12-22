<?php
function get_ip_address() {
	if (IP_FORWARDING_HEADER != "") {
		$normalizedName = "HTTP_" . strtoupper(str_replace("-", "_", IP_FORWARDING_HEADER));
		if (isset($_SERVER[$normalizedName])) {
			return $_SERVER[$normalizedName];
		}
	}

	return $_SERVER["REMOTE_ADDR"];
}