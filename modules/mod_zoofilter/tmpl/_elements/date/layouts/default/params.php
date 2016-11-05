<?php
/**
* @package   com_zoo
* @author    ZOOlanders http://www.zoolanders.com
* @copyright Copyright (C) ZOOlanders.com
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

	$app = App::getInstance('zoo');
	
	return 
	'{"fields": {
		"layout_wrapper":{
			"type": "fieldset",
			"fields": {

				"layout_separator":{
					"type":"separator",
					"text":"PLG_ZLFRAMEWORK_DEFAULT_LAYOUT",
					"big":1
				},
	
				"_search_type":{
					"type": "hidden",
					"specific": {
						"value":"default"
					}
				},
				
				"_time":{
					"type": "checkbox",
					"label": "PLG_ZOOFILTER_TIME",
					"default": "0",
					"specific":{
						"label":"JYES"
					}
				},
				
				"_placeholder":{
					"type": "text",
					"label": "PLG_ZOOFILTER_PLACEHOLDER",
					"help": "PLG_ZOOFILTER_PLACEHOLDER_DESC"
				}

			}
		}
				
	}}';