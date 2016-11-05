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
	
	return
	'{"fields": {
		"layout_wrapper":{
			"type": "fieldset",
			"fields": {

				"layout_separator":{
					"type":"separator",
					"text":"Checkbox Layout",
					"big":1
				},

				"specific_params": {
					"type": "subfield",
					"path":"modules:mod_zoofilter\/tmpl\/_elements\/category\/specific.json.php",
					"arguments":{
						"params":{
							"total_items":"true"
						}
					}
				}

			}
		}
				
	}}';