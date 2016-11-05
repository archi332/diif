<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

	// set attributes
	$attribs = $params->find('search._required', 0) ? ' required' : '';

	$options = array();
	foreach ($element_options as $option) {
		$options[] = $app->html->_('select.option', urlencode($option['name']), ($params->find('layout.trans_values') ? JText::_($option['name']) : $option['name']));
	}

	$result = $app->zfhtml->_('zoo.radiolist', $options, 'elements['.$element->identifier.'][]', $attribs, 'value', 'text', $value ? $value[0] : '');

	// render with custom separator - TODO
	echo $app->zlfw->applySeparators('tag=[<span>%s</span>]', $result);
?>