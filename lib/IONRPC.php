<?php
/**
 * @author John <john@ionomy.com>
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace lib;


/**
 * Class IONRPC
 * @package lib
 */
class IONRPC {

	/**
	 * @var \jsonRPCClient
	 */
	public $ionDb;

	public function __construct($server = null) {
		/** @var $config array */
		include(__DIR__ . '/../conf/config.php');
		if ($server == null) {
			$rpcUrl = 'http://' . $config['iond']['rpcuser'] . ':' . $config['iond']['rpcpassword'] .
				'@' . $config['iond']['rpchost'] . ':' . $config['iond']['rpcport'] . '/';
		} else {
			$rpcUrl = 'http://' . $config['networknodes'][$server]['rpcuser'] . ':' . $config['networknodes'][$server]['rpcpassword'] .
				'@' . $config['networknodes'][$server]['rpchost'] . ':' . $config['networknodes'][$server]['rpcport'] . '/';
		}
		$this->ionDb = new \jsonRPCClient($rpcUrl);
	}

	public function getInfo() {
		return $this->ionDb->getinfo();
	}

	public function getPeerInfo() {
		return $this->ionDb->getpeerinfo();
	}

	public function help() {
		return $this->ionDb->help();
	}

	public function getBlockHash($blockHeight) {
		return $this->ionDb->getblockhash($blockHeight);
	}

	public function getBlock($blockHash) {
		return $this->ionDb->getblock($blockHash);
	}

	public function getTransaction($txId) {
		return $this->decodeRawTransaction($this->getRawTransaction($txId));
	}

	public function decodeRawTransaction($hex) {
		return $this->ionDb->decoderawtransaction($hex);
	}

	public function getTransactionHex($txId) {
		return $this->getRawTransaction($txId);
	}

	public function getRawTransaction($TxHex) {
		return $this->ionDb->getrawtransaction($TxHex);
	}

	public function getBlockCount() {
		return $this->ionDb->getblockcount();
	}

	public function getLatestBlockHeight() {
		return $this->ionDb->getblockcount();

	}
	public function getLastBlocks() {
		$lastBlock = $this->ionDb->getblockcount();
		$blocks = array();
		if ($lastBlock > 10) {
			for ($i=0; $i<10; $i++) {
				$blockHeight = $lastBlock - $i;
				$blocks[$blockHeight]['hash'] = $this->getBlockHash($blockHeight);
				$blocks[$blockHeight]['details'] = $this->getBlock($blocks[$blockHeight]['hash']);
			}

		}
		return $blocks;
	}

	public function verifySignedMessage($address, $message, $signature) {

		try {

			$result = $this->ionDb->verifymessage($address, $message, $signature);
			return $result;

		} catch (\Exception $e) {
			return $e->getMessage();
		}

	}

} 