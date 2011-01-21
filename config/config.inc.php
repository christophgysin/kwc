<?php
/**
 * $Id: config.inc.php 4 2008-12-28 07:41:19Z donald $:
 * Kannel Web Configuration 
 * Keeping it simple.
 *
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. 
 *  
 * This software is donationware. Meaning that if you find it useful or want to 
 * contribute to the development of this software, you can make a donation.
 *
 * Donations can be in the form of contributions to the code (patches), money
 * or hardware. Donator's will be recognized on the sponsors page or within the
 * source code.
 * 
 * http://www.ddj.co.za/kannel-web-configuration-tool/
 */


$configFiles = array();

/* Only one primary config file supported at present */

// CHANGE THIS LINE
$configFiles[] = "/etc/kannel.conf";

$base_path = dirname(dirname(realpath(__FILE__)));

define("KWC_BASE_PATH", $base_path);
define("KWC_LIB_PATH", KWC_BASE_PATH . "/lib");
define("KWC_CONF_PATH", KWC_BASE_PATH . "/config");
define("KWC_WEB_PATH", KWC_BASE_PATH . "/web");
define("KWC_INCLUDE_PATH", KWC_BASE_PATH . "/web/include");

$scheme = "http";
if(isset($_SERVER['HTTPS'])) {
	if(strtolower($_SERVER['HTTPS']) == 'on') {
		$scheme .= "s";
	}
}
$scheme .= "://";

define("KWC_URL", $scheme.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/");
define("KWC_CONTROLLER", KWC_URL."index.php");



require(KWC_LIB_PATH  .	"/Cfg.class.php");
require(KWC_LIB_PATH  .	"/KWC_Dispatcher.class.php");
require(KWC_CONF_PATH .	"/kannel_config_groups.inc.php");



$cfg = new Cfg($kannelConfigGroups);

for($i=0;$i<count($configFiles);$i++) {
	$cfg->parse_config($configFiles[$i]);
}


?>