<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

	return 
	'{"fields": {
		"wrapper-filter":{
			"type": "wrapper",
			"toggle": "Toggle Style Options",
			"fields": {
				"_style_settings": {
					"type":"subfield",
					"path":"elements:pro\/tmpl\/render\/widgetkit\/gallery\/{value}\/settings.php"
				}
			}
		}
	},
	"control": "settings"}';