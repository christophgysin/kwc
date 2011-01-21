<?php
/**
 * $Id: multi_new.php 4 2008-12-28 07:41:19Z donald $:
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

global $configFiles;

$message = "";
$body = "";
$body .= "<form method='POST'><table id='singlegrouptable'>\n";
$body .= "<input type='hidden' name='add_group' value='1'>\n";
$body .= "<input type='hidden' name='write_action' value='append_new_params' />\n";
$body .= "<input type='hidden' name='line_no' value='EOF' />\n";

$body .= "<tr><td colspan='2'><span id='currentparams'>Enter fields for new group</span></td></tr>";
$body .= "<tr><td>File to save to</td><td><select name='file'>";
for($i=0;$i<count($configFiles);$i++) {
	$body .= "<option>{$configFiles[$i]}</option>";
}
$body .= "</select></td></tr>";
$body .= "<tr><td colspan='2'>&nbsp;</td></tr>";
foreach($group['params'] as $key => $val) {
	$body .= "<tr><td width='50%'>&nbsp;{$key}</td><td><input type='text' name='__new_{$key}' value=''></td></tr>";
}

$body .= "<tr><td colspan='2' align='center'><input type='submit' value='Save new settings'><br>{$message}</td></tr>";

$body .= "</table></form>";
?>