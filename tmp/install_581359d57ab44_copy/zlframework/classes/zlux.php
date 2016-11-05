<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

/**
 * The ZLUX class
 */
class zlux {

	/**
	 * A reference to the global App object
	 *
	 * @var App
	 */
	public $app;

	/**
	 * Class constructor
	 *
	 * @param string $app App instance.
	 */
	public function __construct($app)
	{
		// set application
		$this->app = $app;
	}
}