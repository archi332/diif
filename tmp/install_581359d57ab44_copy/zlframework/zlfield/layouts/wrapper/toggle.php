<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();
	
	$toggle = JText::_($fld->get('toggle'));
?>
	
	<div class="zltoggle-btn open">
		<span>-</span>
		<?php echo $toggle ?>
		<div class="toggle-text">...<small><?php echo strtolower($toggle) ?></small>...</div>
	</div>
	<div class="wrapper zltoggle" data-id="<?php echo $id ?>" >
		<?php echo $content ?>
	</div>