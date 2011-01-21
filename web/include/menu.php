<?php
/**
 * $Id: menu.php 4 2008-12-28 07:41:19Z donald $:
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

$groups = $cfg->getConfigGroups(false);

$in_use_single = array();
$in_use_multi = array();
$not_in_use = array();
for($i=0;$i<count($groups);$i++) {
	$res = $cfg->groupConfigured($groups[$i]);
	if($res !== FALSE) {
		if($res == 'multi') {
			$in_use_multi[] = $groups[$i];
		} else {
			$in_use_single[] = $groups[$i];
		}
	} else {
		$not_in_use[] = $groups[$i];
	}
}

?>
<span id='menuhead'>About KWC</span>
<ul>
	<li>Version 0.1</li>
	<li>By: <a href="mailto:kwc@ddj.co.za">Donald Jackson</a></li>
	<li><a href='<?php echo KWC_CONTROLLER."?p=roadmap"; ?>'>Roadmap</a></li>
	<li>Kannel CVS 1.4.2</li>
</ul>
<span id='menuhead'>Single groups</span>
<ul>

<?php
for($i=0;$i<count($in_use_single);$i++) {
	echo "<li id='menuitem'><a href='".KWC_CONTROLLER."?p=configure_group&group={$in_use_single[$i]}'>".ucfirst($in_use_single[$i])."</a></li>\n";
}
?>
</ul>
<span id='menuhead'>Multi groups</span>
<ul>

<?php
for($i=0;$i<count($in_use_multi);$i++) {
	echo "<li id='menuitem'><a href='".KWC_CONTROLLER."?p=configure_group&group={$in_use_multi[$i]}'>".ucfirst($in_use_multi[$i])."</a></li>\n";
}
?>
</ul>
<span id='menuhead'>Items not configured</span>
<ul>
<?php
for($i=0;$i<count($not_in_use);$i++) {
	echo "<li id='menuitem'><a href='".KWC_CONTROLLER."?p=configure_group&group=".$not_in_use[$i]."'>".ucfirst($not_in_use[$i])."</a></li>\n";
}
?>
</ul>