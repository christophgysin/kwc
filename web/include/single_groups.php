<?php
/**
 * $Id: single_groups.php 4 2008-12-28 07:41:19Z donald $:
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

$last_line_no = -1;
$fileName = false;

$body = "<span id='singlegroupheader'>Now configuring '{$name}' configuration group</span><form method='POST'>";
$body .= "<input type='hidden' name='write_action' value='save_existing' />";
$body .= "<table id='singlegrouptable'>";
$body .= "<tr><td colspan='2'><span id='currentparams'>Current parameters</span></td></tr>";
foreach($group['values'] as $key => $val) {
	$body .= "<tr><td width='50%'>&nbsp;{$key}</td><td><input type='hidden' name='__line_{$key}' value='{$val['lineno']}'><input type='hidden' name='__file_{$key}' value='{$val['file']}'><input type='text' name='__current_{$key}' value='".htmlspecialchars($val['val'])."'></td></tr>";
	unset($group['params'][$key]);
	if($val['lineno'] > $last_line_no) {
		$last_line_no = $val['lineno'];
		$fileName = $val['file'];
		
	}
}
$body .= "<tr><td colspan='2' align='center'><input type='submit' value='Save settings'></td></tr>";
$body .= "</table></form>";

$addgroup = 0;
$message = "";
if($fileName === FALSE) {
	/* This means this group is not currently configured */
	$fileName = $cfg->primaryConfigFile;
	$last_line_no = $cfg->primaryConfigFileLastLine;
	$message = "This group does not exist, so it will be created in the primary config file ({$fileName}) ";
	$addgroup = 1;
}

$body .= "<form method='POST'><table id='singlegrouptable'>\n";
$body .= "<input type='hidden' name='add_group' value='{$addgroup}'>\n";
$body .= "<input type='hidden' name='write_action' value='append_new_params' />\n";
$body .= "<input type='hidden' name='line_no' value='{$last_line_no}' />\n";
$body .= "<input type='hidden' name='file' value='{$fileName}' />\n";

$body .= "<tr><td colspan='2'><span id='currentparams'>Parameters not currently configured</span></td></tr>";
foreach($group['params'] as $key => $val) {
	$body .= "<tr><td width='50%'>&nbsp;{$key}</td><td><input type='text' name='__new_{$key}' value=''></td></tr>";
}

$body .= "<tr><td colspan='2' align='center'><input type='submit' value='Save new settings'><br>{$message}</td></tr>";

$body .= "</table></form>";

require("default.php");
?>