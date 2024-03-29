<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

/**
 * The helper class for routing
 */
class zlHelperRoute extends AppHelper {

	/**
	 * The inited routers
	 * @var array
	 */
	protected $_routers = array();

	/**
	 * Class Constructor
	 * 
	 * @param App $app A reference to the global App object
	 */
	public function __construct($app) {
		parent::__construct($app);

		// load class
		$this->app->loader->register('zlRouter', 'classes:router.php');
	}

	/**
	 * Retrieve a router
	 *
	 * @param string $name Router name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		return $this->get($name);
	}

	/**
	 * Retrieve a helper
	 *
	 * @param string $name Helper name
	 * @param string $prefix Helper prefix
	 *
	 * @return mixed
	 */
	public function get($name) {
		return $this->_routers[$name];
	}

	/**
	 * Returns all routers
	 *
	 * @return array
	 */
	public function getRouters() {
		return $this->_routers;
	}

	/**
	 * Register a router
	 *
	 * @param string $name Router name
	 * @param string $file Router file path
	 */
	public function register($name, $file)
	{
		// load class
		$class = 'zlRouter' . ucfirst($name);

		// register
		$this->app->loader->register($class, $file);

		// add router, if not exists
		if (!isset($this->_routers[$name])) {
			$this->_routers[$name] = new $class($this->app);
		}
	}
}