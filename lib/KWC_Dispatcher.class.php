<?php
/**
 * $Id: KWC_Dispatcher.class.php 4 2008-12-28 07:41:19Z donald $:
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

class KWC_Dispatcher {
	
	function __construct() {
		
	}
	
	function main() {
		global $cfg;
		if(!isset($_REQUEST['p'])) {
			$p = "default";
		} else {
			$p = $_REQUEST['p'];
		}
		
		
		
		require(KWC_INCLUDE_PATH."/{$p}.php");
		
	}
	
	
	
}