<?php
/**
 * @author John <john@ionomy.com>
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace controllers;
use lib\IONDb;
use lib\IONRPC;

/**
 * Class Cli
 * @package controllers
 */
class Cli extends Controller {

	const LOCK_FILE = "/tmp/clibuildDatabase2.lock";
	const NETWORK_LOCK_FILE = "/tmp/getNetworkInfo.lock";

	public function getNetworkInfo() {


		if (!$this->tryLock(self::NETWORK_LOCK_FILE)) {
			die("Already running.\n");
		}

		$ionDb = new IONDb();
		$ionDb->updateNetworkInfo();

	}

	public function buildDatabase() {


		if (!$this->tryLock(self::LOCK_FILE)) {
			die("Already running.\n");
		}
		register_shutdown_function('unlink', self::LOCK_FILE);

		echo 'Building Database' . PHP_EOL;

		$IONRPC = new IONRPC();
		$ionDb = new IONDb();

		$startBlockHeight = $ionDb->getLastBlockInDb();
		$startBlockHeight = (int)$startBlockHeight;


		$endBlockHeight = $IONRPC->getBlockCount();

		if ($startBlockHeight == $endBlockHeight) {
			echo "Caught up.  Last block was $endBlockHeight" . PHP_EOL;
			return;
		} else {
			echo "Catching up with blockchain  $startBlockHeight => $endBlockHeight" . PHP_EOL;
		}

		//@todo move this...
		$startBlockHeight++;
		$ionDb->buildDb($startBlockHeight, $endBlockHeight);

		echo "Complete" . PHP_EOL;

	}

	public function buildWalletDatabase() {

		$ionDb = new IONDb();
		echo "Building wallet database" . PHP_EOL;
		$ionDb->buildWalletDb();

	}

	public function buildRichList() {

		$ionDb = new IONDb();
		echo "Building rich list" . PHP_EOL;
		$ionDb->buildRichList();

	}

	private function tryLock($lockFile) {


		if (@symlink("/proc/" . getmypid(), $lockFile) !== FALSE) # the @ in front of 'symlink' is to suppress the NOTICE you get if the LOCK_FILE exists
			return true;

		# link already exists
		# check if it's stale
		if (is_link($lockFile) && !is_dir($lockFile)) {
			unlink($lockFile);
			# try to lock again
			return $this->tryLock($lockFile);
		}

		return false;
	}



} 