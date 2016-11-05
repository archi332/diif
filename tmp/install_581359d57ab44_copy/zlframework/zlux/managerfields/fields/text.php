<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// init vars
$placeholder = $fld->find('settings.placeholder');

// initialize some field attributes.
$placeholder = $placeholder ? ' placeholder="'.JText::_($placeholder).'"' : '';

echo '<input type="text" name="' . $name . '" id="' . $fld->get('id') . '"' . ' value="'
	. htmlspecialchars($this->values->get($id), ENT_COMPAT, 'UTF-8') . '"' . $placeholder . '/>';