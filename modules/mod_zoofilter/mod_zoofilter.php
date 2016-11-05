<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// load config & Helper
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');
require_once(dirname(__FILE__).'/helper.php');

// get app
$zoo = App::getInstance('zoo');

// load zoo frontend language file
$zoo->system->language->load('com_zoo');

// Type for which we are searching
$type = $params->get( 'type', 'blog' );

// Base params
$name_filter 		= $params->get( 'name_filter', false );
$form_method 		= $params->get( 'form_method', 'get' );
$load_ajax		 	= $params->get( 'load_ajax', 1 );
$ajax_div	 		= $params->get( 'ajax_div', null );
$search_limit 		= $params->get( 'search_limit', 1000 );
$show_title 		= $params->get( 'show_title', 1 );
$show_clear 		= $params->get( 'show_clear', 0 );
$show_submit 		= $params->get( 'show_submit', 1 );
$show_ordering 		= $params->get( 'show_ordering', 1 );
$show_permalink 	= $params->get( 'show_permalink', 1 );
$redirect_if_one 	= $params->get( 'redirect_if_one', 0 );
$columns 		 	= $params->get( 'columns', 2 );
$page_title		 	= $params->get( 'page_title', 'Search Results:' );
$page_layout	 	= $params->get( 'page_layout', 'search' );
$elayout		 	= $params->get( 'module_layout', 'default' );
$ordering_layout 	= $params->get( 'ordering_layout', 'ordering' );


// Result Layout
$result_layout = $params->get( 'result_layout', 'default' );

// POST do not support pagination, so let's diplay all
if ($form_method == 'get'){
	$items_per_page = $params->get('items_per_page', 10);
} else {
	$items_per_page = $search_limit;
}

// Get the app
$application = $zoo->table->application->get($params->get('application', 0));

// errors notice
$errors = '';
if (!JPluginHelper::isEnabled('system', 'zoofilter')) $errors .= JText::_('ZOOfilter plugin is disabled. Check <a href="http://www.zoolanders.com/docs/zoofilter" target="_blank" title="docs">documentation</a>').'<br />';
if (empty($application)) $errors .= JText::_('There is no App selected').'<br />';

if (empty($errors)) {

	// Get the app_id & type
	$type = $application->getType( $type );
	$type_name = $type->identifier;
	$app_id = $application->id;

	// render layout
	$Itemid = $params->get('Itemid', JRequest::getVar('Itemid', ''));

	// set renderer
	$zoo->path->register($zoo->path->path('modules:mod_zoofilter/renderer'), 'renderer');

	// ZOOfilter renderer
	$renderer = $zoo->renderer->create( 'zoofilter' )->addPath(array($zoo->path->path('component.site:'), dirname(__FILE__)));
	$module_layout = $params->get( 'module_layout', 'zoofilter' );

	include ( JModuleHelper::getLayoutPath( 'mod_zoofilter', 'default' ) );
	
} else {
	echo $errors;
}