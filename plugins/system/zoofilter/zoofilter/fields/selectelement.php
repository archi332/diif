<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

	// get element from parent parameter form
	$element 	 = $parent->element;
	$config  	 = $element->config;
	
	if ($config_options = $config->get('option', 0)) {
	
		//init vars
		$options = array();
		$options[] = $this->app->html->_('select.option', '', '-' . JText::_('None') . '-');
		foreach ($config_options as $option) {
			$options[] = $this->app->html->_('select.option', $option['value'], $option['name']);
		}

		//$attributes['size'] = 3;
		echo $this->app->html->_('select.genericlist', $options, $control_name.'['.$name.']', '', 'value', 'text', $value);
	}
	
	echo JText::_("There are no options to choose from.");