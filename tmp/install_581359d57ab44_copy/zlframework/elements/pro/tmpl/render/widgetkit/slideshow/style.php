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

		"widget_separator":{
			"type": "separator",
			"text": "Slideshow Widget",
			"big":"1"
		},
		"_style":{
			"type": "layout",
			"label": "Style",
			"default": "default",
			"specific": {
				"path":"elements:pro\/tmpl\/render\/widgetkit\/slideshow",
				"mode":"folders"
			},
			"childs":{
				"loadfields": {

					"_style_settings": {
						"type":"subfield",
						"path":"elements:'.$element->getElementType().'\/tmpl\/render\/widgetkit\/slideshow\/{value}\/params.php"
					}

				}
			}
		}

	}}';