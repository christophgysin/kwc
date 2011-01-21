<?php
/**
 * $Id: header.php 4 2008-12-28 07:41:19Z donald $:
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
?>
<html>
<head>
<title>Kannel Web Configurator</title>
<style>
body, td {
	font-family: verdana;
	font-size:11px;
}

a {
	color: #111111;
}

.plusSign {
	border: 1px #111111 solid;
	font-size: 13px;
	text-align: center;
	text-decoration: none;
	padding: 3px;
	background-color: #ffffff;
}



#headingrow {
	height: 50;
}
#footerrow {
	height: 50;
}
#heading {
	font-size: 18px;
	font-weight:bold;
}

#bodytable {
	border: 1px #333333 solid;
	background-color: #e5e5e5;
	height: 100%;
	width: 780;
}

#menuhead {
	font-weight: bold;
	font-size: 12px;
}

#singlegroupheader {
	text-decoration: underline;
	font-size: 12px;
	line-height: 3.5;
}

#singlegrouptable {
	width: 95%;
}


#multitable {
	width: 95%;
}

#multiblurb {
	text-decoration: underline;
}

#currentparams {
	text-decoration: underline;
}

#content {
	width: 100%;
	height: 100%;
	overflow: auto;
}

#infotable {
	width: 95%;
}

#infovar {
	font-weight: bold;
	font-size: 12px;
}

#smschead {
	text-decoration: underline;
}

</style>
<body>
<table id="bodytable" align="center">
<tr><td colspan="2" id="headingrow"><span id="heading">Kannel Web Configuration</span> - <a href='<?php echo KWC_CONTROLLER."?p=kannel_status"; ?>'>Status</a> - <a href='<?php echo KWC_CONTROLLER."?p=smsc_manage"; ?>'>Manage SMSC(s)</a></td></tr>
<tr><td valign="top" width="150">
<?php
require(KWC_INCLUDE_PATH."/menu.php");
?>
</td><td valign="top" align="left"><div id='content'>