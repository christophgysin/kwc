<?php
/**
 * $Id: multi_choose.php 4 2008-12-28 07:41:19Z donald $:
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

$body = "<span id='multiheader'>What would you like to do?</span>";
if(count($group['values']))
	$body .= "<p><a href='{$base_action}pp=existing'>Configure existing {$name} group</a></p>";

$body .= "<p><a href='{$base_action}pp=new'>Configure new {$name} group</a></p>";


?>