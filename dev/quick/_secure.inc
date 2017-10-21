<?php
define('ADMIN_USERNAME','magento'); 	// Admin Username
define('ADMIN_PASSWORD','magento');  	// Admin Password
////////// END OF DEFAULT CONFIG AREA /////////////////////////////////////////////////////////////

///////////////// Password protect ////////////////////////////////////////////////////////////////
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
           $_SERVER['PHP_AUTH_USER'] != ADMIN_USERNAME ||$_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD) {
  Header("WWW-Authenticate: Basic realm=\"Memcache Login\"");
  Header("HTTP/1.0 401 Unauthorized");

  echo <<<EOB
				<html><body>
				<h1>Rejected!</h1>
				<big>Wrong Username or Password!</big>
				</body></html>
EOB;
  exit;
}