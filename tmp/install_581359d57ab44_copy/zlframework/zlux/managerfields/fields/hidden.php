<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// init vars
$value = $fld->find('settings.value');

echo '<input type="hidden" name="'.$name.'" value="'.$value.'" />';