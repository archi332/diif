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
					"type": "select",
					"label": "PLG_ZOOFILTER_SEARCH_TYPE",
					"help": "PLG_ZOOFILTER_SEARCH_TYPE_DESC",
					"specific": {
						"options": {
							"PLG_ZOOFILTER_PARTIAL":"partial",
							"PLG_ZOOFILTER_EXACT":"exact",
							"&gt;":"from",
							"&lt;":"to",
							"&gt;=":"fromequal",
							"&lt;=":"toequal"
						}
					},
					"default": "rangeequal",
					"dependents":"_convert !> partial"
				},

				"_convert":{
					"type": "select",
					"label": "PLG_ZOOFILTER_CONVERT",
					"help": "PLG_ZOOFILTER_CONVERT_DESC",
					"specific": {
						"options": {
							"PLG_ZOOFILTER_DECIMAL" : "DECIMAL",
							"PLG_ZOOFILTER_INTEGRER" : "SIGNED",
							"PLG_ZOOFILTER_DATE"	  : "DATE",
							"PLG_ZOOFILTER_DATE_TIME": "DATETIME"
						}
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