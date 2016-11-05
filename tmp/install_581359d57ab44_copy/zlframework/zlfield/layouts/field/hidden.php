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
	$id = $params->get('id');
	
	$class = ($params->get('class') ? " {$params->get('class')}" : '');

	// attributes
	$attrs .= $params->get('type') ? " data-type='hidden'" : '';

?>

<div data-id="<?php echo $id ?>" data-layout="hidden" class="zl-row<?php echo $class ?>" <?php echo $attrs ?>>
	<?php echo $field ?>
</div>