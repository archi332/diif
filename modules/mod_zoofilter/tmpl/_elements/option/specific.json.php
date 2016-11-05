<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

	// get options
	$element_options = array();
	if ($element->getElementType() == 'country'){
		$element_options = $this->app->zf->getCountryOptions($element->config->get('selectable_country', array()));
	} else {
		$element_options = $element->config->get('option', array());
	}

	// format options
	$options = array();
	foreach ($element_options as $opt){
		$options[$opt['name']] = $opt['name'];
	}
	
	return 
	'{
		"_default":{
			"type": "select",
			"label": "Default Selection",
			"specific": {
				"options": '.json_encode($options).',
				"multi":"true"
			}
		}
	}';