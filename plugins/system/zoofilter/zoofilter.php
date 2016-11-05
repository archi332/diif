<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class plgSystemZooFilter extends JPlugin {

	public $joomla;
	public $app;
	
	/**
	 * onAfterInitialise handler
	 *
	 * Adds ZOO event listeners
	 *
	 * @access	public
	 * @return null
	 */
	public function onAfterInitialise()
	{
		// Get Joomla instances
		$this->joomla = JFactory::getApplication();
		$jlang = JFactory::getLanguage();
		
		// load default and current language
		$jlang->load('plg_system_zoofilter', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('plg_system_zoofilter', JPATH_ADMINISTRATOR, null, true);

		// check dependences
		if (!defined('ZLFW_DEPENDENCIES_CHECK_OK')){
			$this->checkDependencies();
			return; // abort
		}

		// Get the ZOO App instance
		$this->app = App::getInstance('zoo');
		
		// register plugin path
		if ( $path = $this->app->path->path( 'root:plugins/system/zoofilter/zoofilter' ) ) {
			$this->app->path->register($path, 'zoofilter');
		}
		
		// register fields path
		if ( $path = $this->app->path->path( 'zoofilter:fields' ) ) {
			$this->app->path->register($path, 'zlfields');
		}
		
		// register helpers
		if ( $path = $this->app->path->path( 'zoofilter:helpers' ) ) {
			$this->app->path->register($path, 'helpers');

			$this->app->loader->register('ZFHelper', 'helpers:zfhelper.php');
			$this->app->loader->register('ZFHTMLHelper', 'helpers:zfhtml.php');
			$this->app->loader->register('ZFXmlparamsHelper', 'helpers:zfxmlparams.php');
		}
		
		// register controllers
		if ( $path = $this->app->path->path( 'zoofilter:controllers' ) ) {
			$this->app->path->register( $path, 'controllers' );
		}
		
		// register models
		if ( $path = $this->app->path->path( 'zoofilter:models' ) ) {
			$this->app->path->register( $path, 'models' );

			$this->app->loader->register('ZLModelZoofilter', 'models:zoofilter.php');
		}
		
		// register events
		$this->app->event->dispatcher->connect('element:configform', array($this, 'configForm'));
		$this->app->event->dispatcher->connect('layout:init', array($this, 'initTypeLayouts'));
		$this->app->event->dispatcher->connect('application:sh404sef', array($this, 'sh404sef'));
		$this->app->event->dispatcher->connect('application:sefbuildroute', array($this, 'sefBuildRoute'));
		$this->app->event->dispatcher->connect('application:sefparseroute', array($this, 'sefParseRoute'));
	}

	/*
		Functions: sefBuildRoute / sefParseRoute
	*/
	public function sefBuildRoute($event)
	{
		$segments = $event['segments'];
		$query 	  = $event['query'];

		$task = 'dosearch';
		$controller = 'zoofilter';

		if ((((@$query['task'] == $task) && @$query['controller'] == $controller ))) {
			$segments[] = 'advanced-search';
			$segments[] = @$query['search_id'];
			unset($query['task']);
			unset($query['controller']);
			unset($query['search_id']);
			unset($query['app_id']);
		}

		$event['segments'] = $segments;
		$event['query']	   = $query;
	}

	public function sefParseRoute($event)
	{	
		$segments = $event['segments'];
		$vars     = $event['vars'];

		$count = count($segments);

		$task = 'dosearch';
		$controller = 'zoofilter';

		if ($count == 2 && $segments[0] == 'advanced-search') {
			$vars['task']   = $task;
			$vars['controller'] = $controller;
			$vars['search_id'] = $segments[1];

			// as we need an App ID to get to ZF controller, we are using the first valid one
			$app = $this->app->table->application->all();
			$app = array_shift($app);
			$vars['app_id'] = $app->id;
		}

		$event['segments']	= $segments;
		$event['vars']		= $vars;
	}
	
	/*
		Function: initTypeLayouts
			Callback function for the zoo layouts

		Returns:
			void
	*/
	public function initTypeLayouts($event)
	{
		$extensions = (array) $event->getReturnValue();
		
		// clean all ZOOfilter layout references
		$newextensions = array();
		foreach ($extensions as $ext) {
			if (stripos($ext['name'],'zoofilter') === false) {
				$newextensions[] = $ext;
			}
		}
		
		// add new ones
		$newextensions[] = array('name' => 'ZOOfilter Result', 'path' => $this->app->path->path('zoofilter:'), 'type' => 'plugin');
		$newextensions[] = array('name' => 'ZOOfilter Search', 'path' => $this->app->path->path('modules:mod_zoofilter'), 'type' => 'module');
		$newextensions[] = array('name' => 'ZOOfilter Order', 'path' => $this->app->path->path('zoofilter:ordering'), 'type' => 'plugin');
		
		$event->setReturnValue($newextensions);
	}
	
	/**
	 * Add parameter to the elements config form
	 */
	public function configForm($event, $arguments = array())
	{
		$element = $event->getSubject();
		$type	 = $element->getType();
		$config  = $event['form'];
		$files	 = array();

		// add XML params path
		$config->addElementPath($this->app->path->path('zoofilter:fields'));
		$config->addElementPath($this->app->path->path('zlfw:fields'));

		// if type has value, we are in Backend and the module path is correct
		if (!empty($type) && JFactory::getApplication()->isAdmin())
		{
			$el_type = $element->getElementType();
			
			// filter
			if (strstr(JRequest::getVar('path'), 'mod_zoofilter'))
			{
				// custom elements can add an "group" information on the Element XML and the element will be threated correspondly
				$xml = simplexml_load_file($this->app->path->path("elements:$el_type/$el_type.xml"));
				$group = $xml->attributes()->zfgroup ? (string) $xml->attributes()->zfgroup : $el_type;

				$files[] = $this->app->path->path('zoofilter:params/params.xml');
			
				switch($group) {
					case 'input':
					case 'itemname': case 'text': case 'textpro': case 'textarea': case 'textareapro':
						$files[] = $this->app->path->path('zoofilter:params/params-input.xml');
						break;
					
					case 'option':
					case 'select': case 'selectpro': case 'checkbox': case 'radio': case 'country':
						$files[] = $this->app->path->path('zoofilter:params/params-option.xml');
						break;
						
					case 'category':
					case 'itemcategory': case 'itemcategorypro': case 'relatedcategoriespro':
						$files[] = $this->app->path->path('zoofilter:params/params-category.xml');
						break;
					case 'relatedcategories': // not using search_index table, use Categories Element instead
						$files = array($this->app->path->path('zoofilter:params/params-category-use-instead.xml'));
						break;
						
					case 'date':
					case 'datepro':
						$files[] = $this->app->path->path('zoofilter:params/params-date.xml');
						break;

					// Elements, without group
					case 'staticcontent': // do nothing to allow using it as usual
						$files = array();
						break;

					default: // all other elements deny setting
						$files = array($this->app->path->path('zoofilter:params/params-nocompatible.xml'));
						break;
				}
			
			// order
			} else if (strstr(JRequest::getVar('path'), 'ordering')) {
				$files[] = $this->app->path->path('zoofilter:params/ordering.xml');
			}
			
			// apply new params
			$this->app->zfxmlparams->addElementParams($config, $files);
		}
	}

	/*
	 *  sh404sef
	 */
	public function sh404sef($event, $arguments = array())
	{
		$query = $event['query'];
		$title = $event['title'];
		
		// Add tasks and variables here
		if(array_key_exists('controller', $query))
		{
			if(in_array($query['controller'], array('zoofilter')))
			{
				$event['dosef'] = false;
			}
		}
		
		$event['query'] = $query;
		$event['title'] = $title;
	}

	/*
	 *  checkDependencies
	 */
	public function checkDependencies()
	{
		if($this->joomla->isAdmin())
		{
			// if ZLFW not enabled
			if(!JPluginHelper::isEnabled('system', 'zlframework') || !JComponentHelper::getComponent('com_zoo', true)->enabled) {
				$this->joomla->enqueueMessage(JText::_('PLG_ZOOFILTER_MISSING_DEPENDENCIES'), 'notice');
			} else {
				// load zoo
				require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

				// fix plugins ordering
				$zoo = App::getInstance('zoo');
				$zoo->loader->register('ZlfwHelper', 'root:plugins/system/zlframework/zlframework/helpers/zlfwhelper.php');
				$zoo->zlfw->checkPluginOrder('zoofilter');
			}
		}
	}

}