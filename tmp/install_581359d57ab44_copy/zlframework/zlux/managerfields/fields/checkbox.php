<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// init vars
$attrs = '';

// init vars
$extra_label = $fld->find('settings.label');
$input_value = $fld->find('settings.value', 1);
$checked = $this->values->get($id) ? ' checked="checked"' : '';

echo '<input type="checkbox" '.$attrs.' name="'.$name.'" id="' . $fld->get('id') . '"'. $checked .' value="'.$input_value.'" />'.($extra_label ? '<span>'.JText::_($extra_label).'</span>' : '');