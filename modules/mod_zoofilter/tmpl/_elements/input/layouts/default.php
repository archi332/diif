<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

	$placeholder = $params->find('specific._placeholder', $params->find('layout._placeholder', ''));
	$attributes  = $placeholder ? 'placeholder="'.$placeholder.'"' : '';
	$attributes .= $params->find('search._required') ? ' required' : '';

	echo '<input type="text" name="elements['.$element->identifier.']" value="'.urldecode($value).'" '.$attributes.' />';
	
?>