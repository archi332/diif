<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

class plgSystemZlframework extends JPlugin
{
	public $app;

	/**
	 * onAfterInitialise handler
	 *
	 * Add the language translation - Need to handle this event because of this line $newLang->load($extension); in languagefilter.php that loads frontend lang file
	 */
	public function onAfterRoute()
	{
		// load default and current language
		$this->app->system->language->load('plg_system_zlframework', JPATH_ADMINISTRATOR, 'en-GB');
		$this->app->system->language->load('plg_system_zlframework', JPATH_ADMINISTRATOR);
	}

	function onAfterInitialise()
	{
		if (!(file_exists($path = JPATH_ADMINISTRATOR.'/components/com_zoo/config.php')
            and JComponentHelper::getComponent('com_zoo', true)->enabled
            and (include_once $path)
            and class_exists('App')
            and $this->app = App::getInstance('zoo')
            and version_compare($this->app->zoo->version(), '2.5', '>=')
			and require_once(JPATH_ROOT.'/plugins/system/zlframework/config.php'))
        ) {
            return;
        }

		// check and perform installation tasks
		if(!$this->checkInstallation()) return; // must go after language, elements path and helpers

		// let's define the check was succesfull to speed up other plugins loading
		if (!defined('ZLFW_DEPENDENCIES_CHECK_OK')) define('ZLFW_DEPENDENCIES_CHECK_OK', true);

		// register events
		$this->app->event->register('TypeEvent');
		$this->app->event->dispatcher->connect('type:coreconfig', array($this, 'coreConfig'));
		$this->app->event->dispatcher->connect('application:sefparseroute', array($this, 'sefParseRoute'));
		$this->app->event->dispatcher->connect('type:beforesave', array($this, 'typeBeforeSave'));
		
		// perform admin tasks
		if ($this->app->system->application->isAdmin()) {
			$this->app->document->addStylesheet('zlfw:assets/css/zl_ui.css');
		}

		// init ZOOmailing if installed
		if ( $path = $this->app->path->path( 'root:plugins/acymailing/zoomailing/zoomailing' ) ) {
			
			// register path and include
			$this->app->path->register($path, 'zoomailing');
			require_once($path.'/init.php');
		}

		// load ZL Fields, workaround for first time using ZL elements
		if ($this->app->zlfw->isTheEnviroment('zoo-type-edit')) $this->app->zlfield->loadAssets();

		// load Separator ZL Field integration
		if ($this->app->zlfw->isTheEnviroment('zoo-type')) {
			$this->app->document->addStylesheet('elements:separator/assets/zlfield.css');
			$this->app->document->addScript('elements:separator/assets/zlfield.min.js');
			$this->app->document->addScriptDeclaration( 'jQuery(function($) { $("body").ZOOtoolsSeparatorZLField({ enviroment: "'.$this->app->zlfw->getTheEnviroment().'" }) });' );
		}
	}

	/**
	 * Actions for type:beforesave event
	 */
	public function typeBeforeSave($event, $arguments = array())
	{
		$type = $event->getSubject();
		$elements = $type->config->get('elements');

		// search for decrypted passwords and encrypt
		array_walk_recursive($elements, 'plgSystemZlframework::_find_and_encrypt');

		// save result
		$type->config->set('elements', $elements);
	}

	protected static function _find_and_encrypt(&$item, $key)
	{
		$matches = array();
		if (preg_match('/zl-decrypted\[(.*)\]/', $item, $matches)) {
			$item = 'zl-encrypted['.App::getInstance('zoo')->zlfw->crypt($matches[1], 'encrypt').']';
		}
	}

	/**
	 * Setting the Core Elements
	 */
	public function coreConfig( $event, $arguments = array() ){
		$config = $event->getReturnValue();
		$config['_itemlinkpro'] = array('name' => 'Item Link Pro', 'type' => 'itemlinkpro');
		$config['_staticcontent'] = array('name' => 'Static Content', 'type' => 'staticcontent');
		$event->setReturnValue($config);
	}
	
	/**
	 *  checkInstallation
	 */
	public function checkInstallation()
	{
		// if in admin views
		if ($this->app->system->application->isAdmin() && $this->app->zlfw->environment->is('admin.com_zoo admin.com_installer admin.com_plugins'))
		{
			return $this->_checkDependencies();
		}
		
		return true;
	}

	/**
	 *  _checkDependencies
	 */
	protected function _checkDependencies()
	{
		// prepare cache
		$cache = $this->app->cache->create($this->app->path->path('cache:') . '/plg_zlframework_dependencies', true, '86400', 'apc');

		// set plugins order
		$this->app->zlfw->checkPluginOrder();

		// checks if dependencies are up to date
		$status = $this->app->zlfw->dependencies->check("zlfw:dependencies.config");
		if (!$status['state']){

			// warn but not if in installer to avoid install confusions
			if (!$this->app->zlfw->environment->is('admin.com_installer'))
				$this->app->zlfw->dependencies->warn($status['extensions']);
		}

		// save state to cache
		if ($cache && $cache->check()) {
			$cache->set('updated', $status['state']);
			$cache->save();
		}
		
		return $status['state'];
	}

	/**
	 *  sefParseRoute
	 */
	public function sefParseRoute($event)
	{
		$app_id = $this->app->request->getInt('app_id', null);
		$app = $this->app->table->application->get($app_id);

		// check if was loaded
		if (!$app) return;
		
		$group = $app->getGroup();
		if($router = $this->app->path->path("applications:$group/router.php")){
			require_once $router;
			$class = 'ZLRouter'.ucfirst($group);
			$routerClass = new $class;
			$routerClass->parseRoute($event);
		}
	}
}