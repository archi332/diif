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
	$attributes 	= '';
	$hide_empty     = $params->find('specific._hide_empty', 0);
	$application	= $element->getType()->getApplication();
	$type			= $element->getType()->id;
	$maxLevel	    = $params->find('specific._max_level_number', 0);

	// check max level
	$maxLevel = !$params->find('specific._max_level') || empty($maxLevel) ? 9999 : $maxLevel;

	// set required
	$attributes .= $params->get('required') ? ' required' : '';

	// get categories
	$categories = $app->zfhtml->_('zoo.categoryList', $application, $root_cat, $maxLevel, $hide_empty, '', '', $type);

	// convert categories to options
	foreach ($categories as &$category)
	{
		$total_items = $name = '';
		if ($params->find('layout._show_number_items', 0))
		{
			// set query
			$model = $app->zlmodel->getNew('item');
			$model->setState('select', 'DISTINCT a.*');
			$model->setState('apps', $application->id);
			$model->setState('types', $type);
			$model->setState('categories', array($category->id));
			$model->setState('published', true);

			// retrieve items
			$total_items = count($model->getList());

			// set name and total items
			$pattern = array('/\[name\]/', '/\[total_items\]/');
			$replace = array($category->name, $total_items);
			$tree = str_replace($category->name, '', $category->treename);
			$name = $params->find('layout._name');
			$name = $tree.preg_replace($pattern, $replace, empty($name) ? '[name] ([total_items])' : $name);
		}
		else
		{
			// set name
			$name = $category->treename;
		}

		// save category
		$category = $app->html->_('select.option', $category->id, $name);
	}

	$result = $app->zfhtml->_('zoo.radiolist', $categories, 'elements['.$element->identifier.$instance.'][]', $attributes, 'value', 'text', $value ? $value[0] : '');

	// render with custom separator - TODO
	echo $app->zlfw->applySeparators('tag=[<span>%s</span>]', $result);
?>