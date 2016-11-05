<?php
/**
* @package   com_zoo
* @author    ZOOlanders http://www.zoolanders.com
* @copyright Copyright (C) ZOOlanders.com
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
	
	$app->document->addScript('libraries:jquery/jquery-ui.custom.min.js');
	$app->document->addStylesheet('libraries:jquery/jquery-ui.custom.css');
	$app->document->addScript('libraries:jquery/plugins/timepicker/timepicker.js');
	$app->document->addStylesheet('libraries:jquery/plugins/timepicker/timepicker.css');
	
	$placeholder = JText::_($params->find('specific._placeholder', $params->find('layout._placeholder', '')));
	$required = $params->find('search._required', 0) ? 'true' : '';
	$time = $params->find('layout._time', 0) ? true : false;
	
	$attributes = array();
	$attributes['class'] = 'calendar-element';
	if (!empty($placeholder)) $attributes['placeholder'] = $placeholder;
	if (!empty($required)) $attributes['required'] = $required;
	
	echo $app->html->_('zoo.calendar', urldecode($value), 'elements[' . $element->identifier . ']', 'elements[' . $element->identifier. ']value', $attributes, $time);
	
?>