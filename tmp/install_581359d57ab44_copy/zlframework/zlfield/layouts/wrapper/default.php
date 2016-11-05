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
	$attrs .= $fld->get('dependent') ? " data-dependent='{$fld->get('dependent')}'" : '';
	
?>

	<div class="wrapper" data-id="<?php echo $id ?>" <?php echo $attrs ?>>
		<?php echo $content ?>
	</div>