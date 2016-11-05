<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="items <?php if ($itemstitle) echo 'has-box-title'; ?>">

	<?php if ($itemstitle) : ?>
		<h1 class="box-title"><span><span><?php echo $itemstitle; ?></span></span></h1>
	<?php endif; ?>

	<?php
	
		// init vars
		$i = 0;
		$columns = $this->columns;
		reset($this->items);
		
		// render rows
		while((list($key, $item) = each($this->items)) || ($i % $columns != 0)) {
			if ($i % $columns == 0) echo ($i > 0 ? '</div><div class="row">' : '<div class="row first-row">');
			$first = ($i % $columns == 0) ? ' first-item' : null;
			echo '<div class="width'.intval(100 / $columns).$first.'">'.$this->partial('item', compact('item', 'layout')).'</div>';
			$i++;
		}
		echo '</div>';

	?>
		
	<?php echo $this->partial('pagination'); ?>
	<div class="total"><?php echo JText::_('PLG_ZOOFILTER_TOTAL_ITEMS_FOUND') . ': '. $total; ?></div>

</div>