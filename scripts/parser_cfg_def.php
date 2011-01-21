#!/usr/bin/php -q 
<?php
/**
 * $Id: parser_cfg_def.php 4 2008-12-28 07:41:19Z donald $:
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

/* cfg.def parser */

$file = $argv[1];

$fp = fopen($file, "r");

$buffer = fread($fp, filesize($file));

$real = "";

function removeStarredComments($str) {
	$start_pos = strpos($str, "/*");
	if($start_pos === FALSE) {
		return $str;
	}
	
	$end_pos = strpos($str, "*/", $start_pos);
	
	$str = substr($str, 0, $start_pos).substr($str, ($end_pos + strlen("*/")));
	
	return removeStarredComments($str);
}

function removeOtherComments($str) {
	$lines = explode("\n", $str);
	$str = "";
	for($i=0;$i<count($lines);$i++) {
		if(($lines[$i][0] != '#') && (substr($lines[$i],0,2) != '//'))  {
			$str .= $lines[$i];
		}
	}
	return $str;
}

function removeAllComments($str) {
	$str = removeStarredComments($str);
	$str = removeOtherComments($str);
	return $str;
}

function getSingleGroups($str) {
	$tok = explode("SINGLE_GROUP", $str);
	return getGroups($tok);
}

function getMultiGroups($str) {
	$tok = explode("MULTI_GROUP", $str);
	return getGroups($tok, "multi");
}

function getGroups($tok, $type = "single") {
	$l = count($tok);
	$groups = array();
	$tokens = array();
	for($i=0;$i<$l;$i++) {
		
		for($j=0;$j<strlen($tok[$i]);$j++) {
			switch($tok[$i][$j]) {
				case '(':
					array_push($tokens, $tok[$i][$j]);
					break;
				case ')':
					$token = array_pop($tokens);
					if($token != '(') {
						die("parse error");
					}
					if(!count($tokens)) {
						// Finished with this group
						$pos = $j;
						break 2;
					}
					break;
				default:
					
					;
			}
			
		}
		$group = trim(substr($tok[$i],strpos($tok[$i], "(")+1 ,$pos));
		if(!empty($group)) {
			$namepos = strpos($group, ",");
			$name = substr($group,0, $namepos);
			$groups[$name] = array("type" => $type, "vars" => array());
			$group = str_replace(" ", "", substr($group, $namepos+1));
			$vars = explode("OCTSTR", $group);
			$k = count($vars);
			for($j=0;$j<$k;$j++) {
				/* Find the first and last occurrence of brackets */
				$start_pos = strpos($vars[$j], "(")+1;
				$end_pos = strpos($vars[$j], ")")-1;
				
				$var = trim(substr($vars[$j], $start_pos, $end_pos));
				if(!empty($var)) {
					$groups[$name]['vars'][$var] = array("type" => "string", "default" => "");
				}
			}
			
		}
	}
	return $groups;
}

function getConfig($str) {
	$real = removeAllComments($str);
	$groups = array_merge(getMultiGroups($real), getSingleGroups($real));
	return $groups;
	
}

function writeGroups($groups, $varName = "kannelConfigGroups") {
	$str = "<?php \n";
	$str .= "\${$varName} = array();\n";
	foreach($groups as $name => $val) {
		$str .= "\${$varName}['{$name}'] = array();\n";
		$str .= "\t\${$varName}['{$name}']['type'] = '{$val['type']}';\n";
		$str .= "\t\${$varName}['{$name}']['vars'] = array();\n";
		$l = count($val['vars']);
		foreach($val['vars'] as $key => $options) {
			$str .= "\t\${$varName}['{$name}']['vars']['{$key}'] = array('type' => '{$options['type']}', 'default' => '{$options['default']}');\n";	
		}
		
		$str .= "\n\n\n";
		
	}
	
	$str .= "?>";
	echo $str;
	
}

$groups = getConfig($buffer);

writeGroups($groups);




fclose($fp);

?>