<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

	// init vars
	$options = array();

	$groups = $this->app->zoo->getGroups();
	foreach ($groups as $group) {
		$options[JText::_($group->name)] = $group->id;
	};

	return
	'{
		"access":{
			"type": "select",
			"label": "'.$params->find('access.label').'",
			"help": "'.$params->find('access.help').'",
			"default":"'.$this->app->joomla->getDefaultAccess().'",
			"specific": {
				"options":'.json_encode($options).'
			}
		}
	}';
?>