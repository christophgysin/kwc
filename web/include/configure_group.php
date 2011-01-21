<?php
/**
 * $Id: configure_group.php 4 2008-12-28 07:41:19Z donald $:
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

$group = $cfg->getGroup($_REQUEST['group']);
$name = $_REQUEST['group'];

$base_action = KWC_CONTROLLER . "?p=".urlencode($_REQUEST['p'])."&group=".urlencode($_REQUEST['group'])."&";

if($group['type'] == 'single') {
	require("single_groups.php");
} else {
	require("multi_groups.php");
}



?>