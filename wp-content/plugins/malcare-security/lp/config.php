<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVLPConfig')) :
class BVLPConfig {
	public $bvmain;
	public static $requests_table = 'lp_requests';
	
	#mode
	const DISABLED = 1;
	const AUDIT    = 2;
	const PROTECT  = 3;

	public function __construct($bvmain) {
		$this->bvmain = $bvmain;
	}

	public function setMode($mode) {
		if (!$mode) {
			$this->bvmain->info->deleteOption('bvlpmode');
		} else {
			$this->bvmain->info->updateOption('bvlpmode', intval($mode));
		}
	}

	public function setCaptchaLimit($count) {
		if (!$count) {
			$this->bvmain->info->deleteOption('bvlpcaptchaLimit');
		} else {
			$this->bvmain->info->updateOption('bvlpcaptchaLimit', intval($count));
		}
	}

	public function setTempBlockLimit($count) {
		if (!$count) {
			$this->bvmain->info->deleteOption('bvlptempblocklimit');
		} else {
			$this->bvmain->info->updateOption('bvlptempblocklimit', intval($count));
		}
	}

	public function setBlockAllLimit($count) {
		if (!$count) {
			$this->bvmain->info->deleteOption('bvlpblockalllimit');
		} else {
			$this->bvmain->info->updateOption('bvlpblockalllimit', intval($count));
		}
	}
	
	public function setWhitelistedIPs($ips) {
		if (!$ips) {
			$this->bvmain->info->deleteOption('bvlpwhitelistedips');
		} else {
			$this->bvmain->info->updateOption('bvlpwhitelistedips', $ips);
		}
	}

	public function setBlacklistedIPs($ips) {
		if (!$ips) {
			$this->bvmain->info->deleteOption('bvlpblacklistedips');
		} else {
			$this->bvmain->info->updateOption('bvlpblacklistedips', $ips);
		}
	}

	public function getMode() {
		$mode = $this->bvmain->info->getOption('bvlpmode');
		return intval($mode ? $mode : BVLPConfig::DISABLED);
	}

	public function getCaptchaLimit() {
		$limit = $this->bvmain->info->getOption('bvlpcaptchalimit');
		return ($limit ? $limit : 3);
	}

	public function getTempBlockLimit() {
		$limit = $this->bvmain->info->getOption('bvlptempblocklimit');
		return ($limit ? $limit : 10);
	}

	public function getBlockAllLimit() {
		$limit = $this->bvmain->info->getOption('bvlpblockAlllimit');
		return ($limit ? $limit : 100);
	}

	public function getBlacklistedIPs() {
		$ips = $this->bvmain->info->getOption('bvlpblacklistedips');
		return ($ips ? $ips : array());
	}

	public function getWhitelistedIPs() {
		$ips = $this->bvmain->info->getOption('bvlpwhitelistedips');
		return ($ips ? $ips : array());
	}
	
	public function clear() {
		$this->setMode(false);
		$this->setCaptchaLimit(false);
		$this->setTempBlockLimit(false);
		$this->setBlockAllLimit(false);
		$this->setBlacklistedIPs(false);
		$this->setWhitelistedIPs(false);
		$this->bvmain->db->dropBVTable(BVLPConfig::$requests_table);
		$this->bvmain->info->deleteOption('bvptplug');
		return true;
	}
}
endif;