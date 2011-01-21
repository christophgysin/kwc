<?php
/**
 * $Id: KWC_Kannel.class.php 4 2008-12-28 07:41:19Z donald $:
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
 
class Kannel {
	var $status = null;
	
	var $baseParams = array();
	var $cfg;
	
	function Kannel($cfg) {
		$this->cfg = &$cfg;
	}
	
	function getsmsc($str) {
		$smsc = array();
		$data = explode(":", $this->getTag($str, "name"));
		$smsc['type'] = $data[0];
		$smsc['host'] = $data[1].":".$data[2];
		$smsc['id'] = $this->getTag($str, "id");
		$smsc['received'] = $this->getTag($str, "received");
		$smsc['status'] = $this->getTag($str, "status");
		$smsc['sent'] = $this->getTag($str, "sent");
		$smsc['failed'] = $this->getTag($str, "failed");
		$smsc['queued'] = $this->getTag($str, "queued");
		return $smsc;
	}
	
	
	function read_status($cfg, $force = false) {
		if($this->status == null || $force) {
			$grp = $cfg->getGroup("core");
			if(!isset($grp['values']['admin-port'])) 
				return;
			
			$port = $grp['values']['admin-port']['val'];
			$password = "";
			if(isset($grp['values']['admin-password'])) {
				$password = "&password=".urlencode($grp['values']['admin-password']['val']);
			}
			$url = "http://localhost:{$port}/status.xml?".$password;
			
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			$this->status = array();
			$this->status['version'] = implode("<br>", explode("\n", $this->getTag($data, "version")));
			$this->status['status'] = $this->getTag($data, "status");
			$str = "";
			$val = $this->getTag($data, "wdp");
			$val = $this->getTag($val, "received");
			$str .= "received (".$this->getTag($val, "total")."/".$this->getTag($val, "queued").") ";
			$val = $this->getTag($data, "wdp");
			$val = $this->getTag($val, "received");
			$str .= "sent (".$this->getTag($val, "total")."/".$this->getTag($val, "queued").") ";
			$this->status['wdp'] = $str;

			$str = "";
			$val = $this->getTag($data, "sms");
			$val = $this->getTag($val, "received");
			$str .= "received (".$this->getTag($val, "total")."/".$this->getTag($val, "queued").") ";
			$val = $this->getTag($data, "sms");
			$val = $this->getTag($val, "received");
			$str .= "sent (".$this->getTag($val, "total")."/".$this->getTag($val, "queued").") ";
			$val = $this->getTag($data, "sms");
			$str .= "storesize (".$this->getTag($val, "storesize").") ";
			$str .= "inbound/sec (".$this->getTag($val, "inbound").") ";
			$str .= "outbound/sec (".$this->getTag($val, "outbound").") ";
			$this->status['sms'] = $str;
			
			$str = "";
			$val = $this->getTag($data, "dlr");
			$str .= "storage ".$this->getTag($val, "storage")." queued ".$this->getTag($val, "queued");
			$this->status['dlr'] = $str;
			
			$this->status['_smscs'] = array();
			$smscs = $this->getMultiTag($this->getTag($data, "smscs"), "smsc");
			
			for($i=0;$i<count($smscs);$i++) {
				$this->status['_smscs'][] = $this->getsmsc($smscs[$i]);
			}
		}
		return $this->status;
	}
	
	function getTag($str, $tag) {
		$searchStr = "<{$tag}>";
		$str = substr($str, strpos($str, $searchStr)+strlen($searchStr));
		$searchStr = "</{$tag}>";
		$str = substr($str, 0, strpos($str, $searchStr));
		return $str;
	}
	
	function getMultiTag($str, $tag, $offset = 0) {
		$data = array();
		$i = 0;
		do {
			$str = substr($str, $offset);
			$searchStr = "<{$tag}>";	
			$pos = strpos($str, $searchStr);
			if($pos !== FALSE) {
				$busy = true;
				$data[$i] = $this->getTag($str, $tag);
				$searchStr = "</{$tag}>";	
				$offset = strpos($str, $searchStr) + strlen($searchStr);
				$i++;
			} else {
				$busy = false;
			}
		} while($busy);
		return $data;
	}
	
	function stopsmsc($id) {
		$grp = $this->cfg->getGroup("core");
		if(!isset($grp['values']['admin-port']))
			return;

		$port = $grp['values']['admin-port']['val'];
		$password = "";
		if(isset($grp['values']['admin-password'])) {
			$password = "&password=".urlencode($grp['values']['admin-password']['val']);
		}
		$url = "http://localhost:{$port}/stop-smsc?smsc={$id}".$password;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		
	}
	
	function startsmsc($id) {
		$grp = $this->cfg->getGroup("core");
		if(!isset($grp['values']['admin-port']))
			return;

		$port = $grp['values']['admin-port']['val'];
		$password = "";
		if(isset($grp['values']['admin-password'])) {
			$password = "&password=".urlencode($grp['values']['admin-password']['val']);
		}
		$url = "http://localhost:{$port}/start-smsc?smsc={$id}".$password;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		
	}
}
?>