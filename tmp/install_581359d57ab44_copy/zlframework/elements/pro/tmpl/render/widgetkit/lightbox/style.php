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

		"layout_settings": {'./* Params loaded from the Element layout */'
			"type":"subfield",
			"path":"elements:'.$element->getElementType().'\/tmpl\/render\/widgetkit\/lightbox\/params.php"
		}

	}}';