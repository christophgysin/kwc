<?php
/**
 * $Id: multi_existing.php 4 2008-12-28 07:41:19Z donald $:
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

$body = "Configuring group type '{$name}'";
$body .= "<script language='javascript'>\n";
$body .= "function toggleGroup(id) { 
ah = document.getElementById('a_'+id);
el = document.getElementById('group_'+id);
dsp = el.style.display;
if(dsp == 'none') {
	el.style.display = 'block';
	ah.innerHTML = '-';
} else {
	el.style.display = 'none';
	ah.innerHTML = '+';
}
}
</script>\n";
$body .= "<table id='multitable'>";
for($i=0;$i<count($group['values']);$i++) {
	$blurb = "";
	$j = 0;
	if(isset($group['id']))
	if(isset($group['values'][$i][$group['id']])) {
		
		$blurb .= $group['values'][$i][$group['id']]['val'].", ";
	}
	foreach($group['values'][$i] as $key => $val) {
		if($j >= 4) {
			break;
		}
		$blurb .= $val['val'].", ";
		$j++;
	}
	$blurb = substr($blurb, 0, -2);
	$body .= "<tr><td><a id='a_{$i}' href='javascript:toggleGroup({$i});' class='plusSign'>+</a>&nbsp;&nbsp;&nbsp;<span id='multiblurb'>{$blurb}</span><br>";
	$body .= "<div style='display:none' id='group_{$i}'><table id='multitable'>";
	$body .= "<form method='POST'>";
	$body .= "<input type='hidden' name='write_action' value='save_existing' />";
	$curGroup = $group['params'];
	$fileName = false;
	$last_line_no = -1;
	foreach($group['values'][$i] as $key => $val) {
		$body .= "<tr><td width='50%'>{$key}</td><td><input type='hidden' name='__line_{$key}' value='{$val['lineno']}'><input type='hidden' name='__file_{$key}' value='{$val['file']}'><input type='text' name='__current_{$key}' value='".htmlspecialchars($val['val'])."'></td></tr>";
		$fileName = $val['file'];
		$last_line_no = $val['lineno'];
		unset($curGroup[$key]);
	}
	$body .= "<tr><td colspan='2'><input type='submit' value='Save this group'></td></tr>";
	$body .= "</form>";
	$body .= "</table>";
	$body .= "<form method='POST'><table id='multitable'>\n";
	$body .= "<input type='hidden' name='write_action' value='append_new_params' />\n";
	$body .= "<input type='hidden' name='line_no' value='{$last_line_no}' />\n";
	$body .= "<input type='hidden' name='file' value='{$fileName}' />\n";
	/* Now show unset vars */
	foreach($curGroup as $key => $val) {
		$body .= "<tr><td width='50%'>&nbsp;{$key}</td><td><input type='text' name='__new_{$key}' value=''></td></tr>";
	}
	$body .= "<tr><td colspan='2'><input type='submit' value='Save new parameters'></td></tr>";
	$body .= "</table></form></div>";
	$body .= "</td></tr>";
	$body .= "<tr><td colspan=2>&nbsp;</td></tR>";
	
}
$body .= "</table>";
?>