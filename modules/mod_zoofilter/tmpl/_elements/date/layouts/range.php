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
	
	$placeholder_from = JText::_($params->find('layout._placeholder_from', ''));
	$placeholder_to = JText::_($params->find('layout._placeholder_to', ''));
	$required = $params->find('search._required', 0) ? 'true' : '';
	$time = $params->find('layout._time', 0) ? true : false;

	$attributes_from = array();
	$attributes_to = array();
	$attributes_from['class'] = $attributes_to['class'] = 'calendar-element';
	if (!empty($placeholder_from)) $attributes_from['placeholder'] = $placeholder_from;
	if (!empty($placeholder_to)) $attributes_to['placeholder'] = $placeholder_to;
	if (!empty($required)) $attributes_from['required'] = $attributes_to['required'] = $required;
	
	$label_from = '<span class="field-label">'.JText::_($params->find('layout._label_from', '')).'</span>';
	$label_to = '<span class="field-label">'.JText::_($params->find('layout._label_to', '')).'</span>';
	
	$html_from = $app->html->_('zoo.calendar', urldecode(@$value['from']), 'elements[' . $element->identifier . '][from]', 'elements[' . $element->identifier. '][from]value', $attributes_from, $time);
	
	$html_to = $app->html->_('zoo.calendar', urldecode(@$value['to']), 'elements[' . $element->identifier . '][to]', 'elements[' . $element->identifier. '][to]value', $attributes_to, $time);
	
	echo '<div class="field">'. $label_from . $html_from .'</div>'
			.'<div class="field">'. $label_to   . $html_to   .'</div>';
	
?>