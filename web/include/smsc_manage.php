<?php
/**
 * $Id: smsc_manage.php 4 2008-12-28 07:41:19Z donald $:
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


$base_action = KWC_CONTROLLER . "?p=".urlencode($_REQUEST['p'])."&";
require_once(KWC_LIB_PATH  .	"/KWC_Kannel.class.php");

$kannel = new Kannel($cfg);

if(isset($_REQUEST['kannel_action'])) {
	switch($_REQUEST['kannel_action']) {
		case 'stop':
			$kannel->stopsmsc($_REQUEST['smsc']);
			break;
		case 'start':
			$kannel->startsmsc($_REQUEST['smsc']);
			break;
	}
}
$info = $kannel->read_status($cfg);
$smscs = $info['_smscs'];
$body = "<table id='infotable'>";

if(count($smscs)) {
	$body .= "<tr>";
	foreach($smscs[0] as $key => $val) {
		$body .= "<td id='smschead'>".ucfirst($key)."</td>";
	}
	$body .= "<td>&nbsp;</td>";
	$body .= "</tr>";
}

for($i=0;$i<count($smscs);$i++) {
		$body .= "<tr>";
		foreach($smscs[$i] as $key => $val) {
			$body .= "<td>{$val}</td>";
		}
		if(strpos($smscs[$i]['status'], "online") !== FALSE) {
			$body .= "<td><a href='{$base_action}kannel_action=stop&smsc={$smscs[$i]['id']}'>stop</a></td>";
		} else {
			$body .= "<td><a href='{$base_action}kannel_action=start&smsc={$smscs[$i]['id']}'>start</a></td>";
		}
		$body .= "<td><a href='{$base_action}pp=test&smsc={$smscs[$i]['id']}'>test</a></td>";
		$body .="</tr>";
}
$body .= "</table>";

require("default.php");
?>