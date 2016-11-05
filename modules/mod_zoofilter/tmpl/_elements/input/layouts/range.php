<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

	$attributes_from  = $params->find('layout._placeholder_from') ? 'placeholder="'.$params->find('layout._placeholder_from').'"' : '';
	$attributes_from .= $params->find('search._required') ? ' required' : '';

	$attributes_to  = $params->find('layout._placeholder_to') ? 'placeholder="'.$params->find('layout._placeholder_to').'"' : '';
	$attributes_to .= $params->find('search._required') ? ' required' : '';
	
	$label_from = '<span class="field-label">'.JText::_($params->find('layout._label_from', '')).'</span>';
	$label_to = '<span class="field-label">'.JText::_($params->find('layout._label_to', '')).'</span>';

	echo '<div class="field">'. $label_from .'<input type="text" name="elements['.$element->identifier.'][from]" value="'.urldecode(@$value['from']).'" '.$attributes_from.' /></div>'
		.'<div class="field">'. $label_to   .'<input type="text" name="elements['.$element->identifier.'][to]" value="'.urldecode(@$value['to']).'" '.$attributes_to.' /></div>';

?>