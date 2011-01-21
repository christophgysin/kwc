<?php
/**
 * $Id: roadmap.php 4 2008-12-28 07:41:19Z donald $:
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

$body = "<h3>In progress</h3>";
$body .= "<ul>";
$body .= "<li>'Live' SMSC (SMPP + EMI) configuration without Kannel restart</li>";
$body .= "<li>'Live' smsbox-route configuration without Kannel restart</li>";
$body .= "</ul>";

$body .= "<h3>To be planned</h3>";
$body .= "<ul>";
$body .= "<li>User authentication</li>";
$body .= "<li>Kannel analytics</li>";
$body .= "<li>Audit trails</li>";
$body .= "<li>Other 'live' configuration changes to Kannel</li>";
$body .= "</ul>";



require("default.php");


?>