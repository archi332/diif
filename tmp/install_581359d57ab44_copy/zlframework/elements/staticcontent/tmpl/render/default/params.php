<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

	return
	'{"fields": {

		"layout_wrapper":{
			"type": "fieldset",
			"fields": {

				"layout_sep":{
					"type": "separator",
					"text": "Default Layout",
					"big": "1"
				},

				"render_options": {
					"type": "subfield",
					"path":"elements:'.$element->getElementType().'\/params\/render.php",
					"adjust_ctrl":{
						"pattern":'.json_encode('/\[layout\]/').',
						"replacement":"[specific]"
					}
				}

			}
		}

	}}';
