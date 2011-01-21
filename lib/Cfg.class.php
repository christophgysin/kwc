<?php
/**
 * $Id: Cfg.class.php 4 2008-12-28 07:41:19Z donald $:
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

class Cfg {
	var $config;
	var $recurse_stack = null;
	
	var $groups = array();
	
	var $configGroups = null;
	
	var $multiGroups = array();
	var $singleGroups = array();
	
	var $primaryConfigFile = false;
	var $primaryConfigFileLastLine = -1;
	
	function __construct($configGroups) {
		$this->configGroups = $configGroups;
		if(isset($_POST['write_action'])) {
			/* we have a write action to take place */
			switch($_POST['write_action']) {
				case 'append_new_params':
					$this->appendNewParams();
					break;
				case 'save_existing':
					$this->saveExisting();
					break;
				default:
					;
			}
		}
	}
	
	function saveExisting() {
		$lines = array();
		$search = "__current_";
		

		foreach($_POST as $key => $val) {
			$var = substr($key,0,strlen($search));
			if(substr($key,0,strlen($search)) == $search) {
				$key = substr($key, strlen($search));
				$line = $_POST['__line_'.$key];
				$file = $_POST['__file_'.$key];
				$this->changeLine($file, $line, "{$key}={$val}\n");
			}
		}
	}
	
	function changeLine($file, $line_no, $value) {
		$fp = fopen($file, "r");
		$lines = array();
		while($line = fgets($fp, 8192)) {
			$lines[] = $line;
		}
		fclose($fp);
		
		/* Going to do a sanity check so we don't change bizarre lines */
		$existing_var = trim(substr($lines[$line_no], 0, strpos($lines[$line_no], "=")));
		$new_var = trim(substr($value, 0, strpos($value, "=")));
		
		if($existing_var != $new_var) {
			return;
		}
		
		$lines[$line_no] = $value;
		
		$this->writeLines($file, $lines);
	}
	
	function appendNewParams() {
		if(!isset($_POST['line_no'])) {
			// Need a line number to start at
			return;
		}
		
		if(!isset($_POST['file'])) {
			// Need a file to change
			return;
		}
		
		if(!file_exists($_POST['file'])) {
			// File doesn't exist
			return;
		}
		
		$lines = array();
		
		$fp = fopen($_POST['file'], "r");
		
		if($_POST['line_no'] == 'EOF') {
			// Read to the last line
			while($line = fgets($fp, 8192)) {
				$lines[] = $line;
			}
		} else {
			for($i=0;$i<$_POST['line_no'];$i++) {
				$line = fgets($fp, 8192);
				if($line === FALSE) {
					die("line not available");
					/* Could not seek to the line */
					return;
				}
				$lines[] = $line;
			}
		}
		
		$search = "__new_";
		if(isset($_POST['add_group']))
		if($_POST['add_group'] == '1') {
			$lines[] = "\n";
			$lines[] = "group={$_REQUEST['group']}\n";
		}

		foreach($_POST as $key => $val) {
			$var = substr($key,0,strlen($search));
			if(substr($key,0,strlen($search)) == $search) {
				if(!empty($val)) {
					$key = substr($key, strlen($search));
					$lines[] = $key ."=".$val."\n";
				}
			}
		}
		
		while($line = fgets($fp, 8192)) {
			$lines[] = $line;
		}
		
		fclose($fp);
		
		$this->writeLines($_POST['file'], $lines);
	
		
	}
	
	function writeLines($filename, $lines) {
		/* Possibly create backups here */
		$data = implode("", $lines);
		$fp = fopen($filename, "w");
		fwrite($fp, $data);
		fclose($fp);
	}
	
	function expand_file($file, $firstFile = false) {
		
		if(in_array($file, $this->recurse_stack)) {
			return;
		} else {
			$this->recurse_stack[] = $file;
		}
		$this->recurse_stack[] = $file;
		$data = "";
		
		if(is_dir($file)) {
			$n = dir($file);
			while($tmp = $n->read()) {
				if(($tmp != '.') && ($tmp != '..')) {
					$data .= $this->expand_file($file."/".$tmp);
				}
			}
			$n->close();
		}
		
		$fp = fopen($file, "r");
		
		while($tmp = fread($fp, 8192)) {
			$data .= $tmp;
		}
		fclose($fp);
		
		$lines = explode("\n", $data);
		
		$data = "";
		$l = count($lines);
		if($firstFile === TRUE) {
			$this->primaryConfigFile = $file;
			$this->primaryConfigFileLastLine = $l-1;
		}
		for($i=0;$i<$l;$i++) {
			if(eregi("^include( )*=( )*", $lines[$i])) {
				// Include line
				$pos = strpos($lines[$i], "=")+1;
				$filename = trim(substr($lines[$i], $pos));
				
				$data .= $this->expand_file($filename);
			} else {
				if(!empty($lines[$i])) {
					if($lines[$i][0] != '#') 
						$data .= $lines[$i]."//FN-{$i}-{$file}\n";
				} else {
					$data .= "\n";
				}
			}
		}
		
		return $data."\n\n";
		
		
		
		
	}
	
	function parse_params($str) {
		$searchString = "//FN-";
		$lines = explode("\n", $str);
		$l = count($lines);
		$currentGroup = null;
		for($i=0;$i<$l;$i++) {
			if(eregi("^group( )*=( )*", $lines[$i])) {
				if($currentGroup != null) {
					/* Save the group */
					$this->addGroup($currentGroup, $groupVars);
				}
				$pos = strpos($lines[$i], "=")+1;
				
				$currentGroup = trim(substr($lines[$i], $pos));
				
				$pos = strrpos($currentGroup, "//FN-");
				$currentGroup = substr($currentGroup, 0, $pos);
				
				$groupVars = array();
				continue;
			}
			if($currentGroup == null) {
				/* We can't have variables not within a group */
				continue;
			}
			
			$tmp = explode("=", $lines[$i]);
			$var = trim($tmp[0]);
			$val = trim(implode("=", array_slice($tmp, 1)));
			
			$meta = explode("-",substr($val, strrpos($val, $searchString)+strlen($searchString)));
			$pos = strpos($val, $searchString);
			$val = substr($val, 0, $pos);
			
			$lineno = $meta[0];
			$filename = implode("-", array_slice($meta, 1));
			
			$val = array("val" => $val, "lineno" => $lineno, "file" => $filename);
			
			
			
			if(!empty($var))
			if($this->allowedVariableName($currentGroup, $var)) {
				$groupVars[$var] = $val;
			} else {
				/* Variable name not allowed */
			}
			
			
			
			
			
			
		}

		if($currentGroup != null) {
			/* Save the group */
			$this->addGroup($currentGroup, $groupVars);
		}
	}
	
	function allowedVariableName($group, $name) {
		if(!array_key_exists($group, $this->configGroups)) {
			return false;
		}
		if(!array_key_exists($name, $this->configGroups[$group]['vars'])) {
			return false;
		}
		return true;
	}
	
	function parse_config($file) {
		$this->configEntry = $file;
		$this->recurse_stack = array();
		$res = $this->expand_file($file, true);
		$this->parse_params($res);
	}
	
	function addGroup($group, $params) {
		if($this->configGroups[$group]['type'] == 'multi') {
			if(!array_key_exists($group, $this->multiGroups)) {
				$this->multiGroups[$group] = array();
			}
			$this->multiGroups[$group][] = $params;
		} else {
			$this->singleGroups[$group] = $params;
		}
	}
	
	function groupConfigured($group) {
		if(array_key_exists($group, $this->multiGroups)) {
			return "multi";
		}
		
		if(array_key_exists($group, $this->singleGroups)) {
			return "single";
		}
		
		return false;
	}
	
	function getGroup($group) {
		$detail = array();
		$params = $this->getConfigGroup($group);
		if(array_key_exists($group, $this->multiGroups))
		{
			$detail['values'] = $this->multiGroups[$group];
		}
			
	
		if(array_key_exists($group, $this->singleGroups))
		{
			$detail['values'] = $this->singleGroups[$group];
		}
		
		if(!isset($detail['values'])) {
			$detail['values'] = array();
		}
		
		$detail['type'] = $params['type'];
		if(isset($params['id']))
			$detail['id'] = $params['id'];
		$detail['params'] = $params['vars'];
		
		
			
		return $detail;
	}
	
	function getConfigGroups($type) {
		$result = array();
		foreach($this->configGroups as $key => $val) {
			if(($val['type'] == $type) || ($type === false)) {
				$result[] = $key;
			}
		}
		return $result;
	}
	
	function getConfigGroup($group) {
		return $this->configGroups[$group];
	}
	
}