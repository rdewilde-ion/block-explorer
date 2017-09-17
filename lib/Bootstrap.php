<?php
/**
 * @author John <john@ionomy.com>
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace lib;

use controllers\Home;
use DebugBar\DataCollector\ConfigCollector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use DebugBar\StandardDebugBar;

/**
 * Class Bootstrap
 * @package lib
 */
class Bootstrap {

	public $route;
	public $httpRequest;
	/**
	 * @var \controllers\Controller
	 */
	public $controller;
	public $config;
	/** @var StandardDebugBar */
	public $debugbar;
	public $bootstrap;

	public static $instance;

	public static function getInstance() {

		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {

		$this->httpRequest = Request::createFromGlobals();
		spl_autoload_register(array($this, "autoload"));
		if (DEBUG_BAR) {
			$this->debugbar = new StandardDebugBar();
		}
		return $this;
	}

	public function setConfig(array $config) {
		$this->config = $config;
	}

	//@todo make this work for dots.. ie site.name
	public function getConfig($config = null, $default = false) {
		if ($config == null) {
			return $this->config;
		}
		if (isset($this->config[$config])) {
			return $this->config[$config];
		}
		return $default;
	}

	public function getParam($param, $default = false) {
		if (isset($this->bootstrap->route[$param])) {
			return $this->bootstrap->route[$param];
		}
		return $default;
	}

	public function run($uri = false) {

		$this->preRoute();

		try {
			$this->route($uri);
		} catch (ResourceNotFoundException $e) {
			// 404
			if (DEBUG_BAR) {
				Bootstrap::getInstance()->debugbar['exceptions']->addException($e);
			}
			$this->routeNotFound();
		} catch (Exception $e) {
			if (DEBUG_BAR) {
				Bootstrap::getInstance()->debugbar['exceptions']->addException($e);
			}
			trigger_error('Error: ' . $e);
		}

		$this->postRoute();

	}

	public function redirect($url) {

		header('location: ' . $url);
	}

	public function routeNotFound() {
		if (DEBUG_BAR) {
			Bootstrap::getInstance()->debugbar['messages']->error("404: Page not found");
		}
		header('HTTP/1.0 404 Not Found');
		$controller = new Home($this);
		$controller->pageNotFound();

	}

	public function route($uri = false) {

		if (!$uri) {
			if (empty($_SERVER['REDIRECT_URL'])) {
				if (stristr($_SERVER['REQUEST_URI'], '?') !== false) {
					$uri = stristr($_SERVER['REQUEST_URI'], '?', true);
				} else {
					$uri = $_SERVER['REQUEST_URI'];
				}
			} else {
				$uri = $_SERVER['REDIRECT_URL'];
			}
		}
		$context = new RequestContext($uri);
		$locator = new FileLocator(array(dirname(__FILE__) . '/../conf'));

		$router = new Router(
			new PhpFileLoader($locator),
			'routes.php',
			array('cache_dir' => null),
			$context
		);
		if (!$uri) {
			$uri = $this->httpRequest->getPathInfo();
		}

		$this->route = $router->match($uri);

		$this->controller = new $this->route['class']($this);

		if (DEBUG_BAR) {
			$this->debugbar->addCollector(new ConfigCollector($this->config));
			$debugbarRenderer = $this->debugbar->getJavascriptRenderer();
			$this->debugbar["messages"]->addMessage("Debug Bar enabled");
			$this->controller->setData('debugbarRenderer', $debugbarRenderer);
		}
		//set action to index is its not set
		if (empty($this->route['action'])) {
			$this->route['action'] = ($this->route['_route'] == '/') ? "index" : $this->route['_route'];
		}

		$action = $this->route['action'];

		if (!method_exists($this->controller, $action)) {
			throw new Exception('Method Not found');
		}

		$this->controller->$action();
	}

	public function autoload($className) {

		$extLibFilename = dirname(__FILE__) . '/../extlib/' . $className . '.php';
		if (file_exists($extLibFilename)) {
			include($extLibFilename);
			return true;
		}
		return false;
	}

	public function preRoute() {}
	public function postRoute() {}


} 