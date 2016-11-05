<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

	// render values
	$result = $this->getRenderedValues($params);

	$separator = $params->find('separator._by_custom') != '' ? $params->find('separator._by_custom') : $params->find('separator._by');

	echo $this->app->zlfw->applySeparators($separator, $result['result'], $params->find('separator._class'), $params->find('separator._fixhtml'));
?>