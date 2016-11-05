<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

	// init vars
	$txtChoose 		= $params->find('layout._choosetext', 'Choose');
	$multiple   	= $params->find('layout._multiple', 0);
	$attribs		= '';
	$options 		= array();

	// check Choose text
	$txtChoose = $txtChoose == 'Choose' || $txtChoose == '' ? JText::_('PLG_ZOOFILTER_CHOOSE') : JText::_($txtChoose);

	// If it's not a multiple selection, add a "nothing selected" option
	if (!$multiple) {
		$options[] = $app->html->_('select.option', '', $txtChoose);
	}
	
	// set attributes
	$attribs .= $params->find('search._required', 0) ? ' required' : '';
	$attribs .= $multiple ? ' multiple="multiple" size="'.$params->find('layout._fieldsize', 5).'"' : '';
	
	foreach ($element_options as $option){
		// Value is name, as stored in the zoo search table
		$options[] = $app->html->_('select.option', urlencode($option['name']), ($params->find('layout.trans_values') ? JText::_($option['name']) : $option['name']));
	}
		
	echo $app->html->_('select.genericlist', $options, 'elements['.$element->identifier.'][]', $attribs, 'value', 'text', $value);

?>