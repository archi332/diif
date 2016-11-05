<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

App::getInstance('zoo')->loader->register('zlfwHelperEnvironment', 'helpers:zlfw/environment.php');

// workaround for outdated extensions calling this helper instead of the new one
class zlfwHelperEnviroment extends zlfwHelperEnvironment {}