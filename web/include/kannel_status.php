<?php
/**
 * $Id: kannel_status.php 4 2008-12-28 07:41:19Z donald $:
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

require_once(KWC_LIB_PATH  .	"/KWC_Kannel.class.php");

$kannel = new Kannel($cfg);
$info = $kannel->read_status($cfg);

$body = "<table id='infotable'>";

foreach($info as $key => $val) {
	if($key[0] != '_') 
		$body .= "<tr><td valign=top id='infovar'>".ucfirst($key)."</td><td>{$val}</td></tr>";
}
$body .= "</table>";

require("default.php");
?>