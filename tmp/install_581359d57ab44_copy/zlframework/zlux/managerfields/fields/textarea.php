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

echo '<textarea '.$attrs.' name="'.$name.'" >'.$this->values->get($id).'</textarea>';