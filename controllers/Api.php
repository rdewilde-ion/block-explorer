<?php
/**
 * @author John <john@ionomy.com>
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace controllers;
use lib\Cache;
use lib\IONDb;
use lib\IONRPC;

/**
 * Class Api
 * @package controllers
 */
class Api extends Controller {
	static $cache;

	public function __construct($bootstrap) {
		parent::__construct($bootstrap);

		if (empty(self::$cache)) {
			self::$cache = Cache::getInstance();
		}
	}

	public function index() {

		echo 'index';

	}

	public function info() {

		$ion = new IONRPC();
		$rpcInfo = $ion->getInfo();
		$info = array(
			'blocks' => $rpcInfo['blocks'],
			'moneysupply' => $rpcInfo['moneysupply'],
		);
		$this->outputJsonResponse($info);

	}

	public function getBlockByHeight() {
		$height = $this->bootstrap->route['height'];
		$ion = new IONDb();

		$block = $ion->getBlockByHeight($height);
		$block['transactions'] = $ion->getTransactionsInBlock($block['height']);
		$block['transactionsOut'] = $ion->getTransactionsOut($block['height']);
		$block['raw'] = unserialize($block['raw']);
		$this->outputJsonResponse($block);
	}



	public function getBlockByHash() {
		$hash = $this->bootstrap->route['hash'];
		$ion = new IONDb();

		$block = $ion->getBlockByHash($hash);
		$block['transaction'] = $ion->getTransactionsInBlock($block['height']);

		$this->outputJsonResponse($block);
	}



	public function getTransaction() {
		$txid = $this->bootstrap->route['txid'];
		$ion = new IONDb();
		$transaction = $ion->getTransaction($txid);
		$transaction['raw'] = unserialize($transaction['raw']);

		$this->outputJsonResponse($transaction);

	}


	public function getLatestBlocks() {

		$height = $this->bootstrap->httpRequest->get('height');
		$limit = $this->getLimit(10, 100);

		$ion = new IONDb();
		$blocks = $ion->getLatestBlocks($limit, $height);
		foreach ($blocks as &$block) {
			$block['raw'] = unserialize($block['raw']);
		}
		$this->outputJsonResponse($blocks);
	}

	public function getLatestTransactions() {

		$limit = $this->getLimit();

		$ionDb = new IONDb();
		$transactions = $ionDb->getLatestTransactions($limit);
		$this->outputJsonResponse($transactions);
	}


	public function getAddress() {

		$address = $this->bootstrap->route['address'];

		$limit = $this->getLimit();

		$ionDb = new IONDb();

		$addressInformation = $ionDb->getAddressInformation($address, $limit);
		$this->outputJsonResponse($addressInformation);
	}

	public function getRichlist() {

		$ion = new IONDb();
		$richList = $ion->getRichList();
		$this->outputJsonResponse($richList);

	}

	public function getBittrexTicker() {
		$currency = strtolower(substr($this->bootstrap->route['currency'], 0, 3));

		$url = 'https://bittrex.com/api/v1.1/public/getticker?market=btc-'.$currency;

		$return = null;//static::$cache->get($url);
		if (empty($return)) {
			$response = file_get_contents($url);

			$data = json_decode($response);
			
			$return = $data->result;

			static::$cache->set($url, $return, 60);
		}

		$this->outputJsonResponse($return);
	}

	public function getBittrexMarket() {
		$currency = strtolower(substr($this->bootstrap->route['currency'], 0, 3));

		$url = 'https://bittrex.com/api/v1.1/public/getmarketsummary?market=btc-'.$currency;

		$return = static::$cache->get($url);
		if (empty($return)) {
			$response = file_get_contents($url);

			$data = json_decode($response);

			$return = $data->result[0];

			static::$cache->set($url, $return, 60);
		}

		$this->outputJsonResponse($return);
	}


	public function getCoinmarketcap() {
		$currency = strtolower($this->bootstrap->route['currency']);

		$url = 'https://api.coinmarketcap.com/v1/ticker/'.$currency.'/';

		$return = static::$cache->get($url);
		if (empty($return)) {
			$response = file_get_contents($url);

			$data = json_decode($response);

			$return = $data[0];

			static::$cache->set($url, $return, 60);
		}

		$this->outputJsonResponse($return);
	}


	private function getLimit($default = 100, $max = 10000) {
		$limit = $this->bootstrap->httpRequest->get('limit');
		if (!$limit) {
			$limit = $default;
		}
		if ($limit > $max) {
			$limit = $max;
		}
		return $limit;
	}

	public function outputJsonResponse($data) {

		$cacheTime = 0;
		$ts = gmdate("D, d M Y H:i:s", time() + $cacheTime) . " GMT";
		header("Expires: $ts");
		header("Pragma: cache");
		header("Cache-Control: max-age=$cacheTime");
		header('Content-Type: application/json');
		header("Access-Control-Allow-Origin: *");

		echo json_encode(
			array(
				'version' => APP_VERSION,
				'data' => $data
			)
		);
	}

	public function disputeAddressTag() {

		$address = $this->bootstrap->httpRequest->request->getAlnum('address');
		$ionDb = new IONDb();
		$ionDb->disputeAddressTag($address);

		$response = array(
			'success' => true,
			'message' => 'Tag has been removed and tagging disabled. <a href="#" class="a-normal">Claim Address</a> to add a Tag .'
		);
		$this->outputJsonResponse($response);
	}

	public function tagAddress() {

		$address = $this->bootstrap->httpRequest->request->getAlnum('address');
		$tag = $this->bootstrap->httpRequest->request->getAlnum('tag');

		if (empty($address)) {
			$response = array(
				'success' => false,
				'error' => 'Error no address in request.'
			);
		} elseif (empty($tag)) {
			$response = array(
				'success' => false,
				'error' => 'You need to enter a tag for the address.'
			);
		} else {

			$ionDb = new IONDb();

			try {
				$ionDb->addTagToAddress($address, $tag);
			} catch (\Exception $e) {
				if (stristr($e->getMessage(), 'Duplicate') !== false) {
					$response = array(
						'success' => false,
						'error' => 'This address is already tagged. Tagging disabled. <a href="#" class="a-normal">Claim Address</a> to add a Tag',
					);
					$this->disputeAddressTag($address);
				} else {
					$response = array(
						'success' => false,
//						'error' => 'Error adding tag to address.',
						'error' => $e->getMessage()
					);
				}
			}
			if (empty($response)) {
				$response = array(
					'address' => $address,
					'tag' => $tag,
					'success' => true,
					'error' => false
				);
			}

		}

		$this->outputJsonResponse($response);
	}

	public function nodes() {
		$subver = $this->bootstrap->httpRequest->request->get('subversion');
		//$subver = urldecode($subver);
		$subver = $_GET['subversion'];
		$ionDb = new IONDb();
		$nodes = $ionDb->getNodes($subver);
		$nodes = array_column($nodes, 'addr');
		foreach ($nodes as &$node) {
			$node = str_replace(':12705', '', $node);

		}


		$response['nodes'] = $nodes;
		$this->outputJsonResponse($response);
	}

} 