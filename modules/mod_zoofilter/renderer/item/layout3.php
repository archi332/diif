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


<?php if ($this->checkPosition('elements')) : ?>
<div class="form-elements">
	<?php echo $this->renderPosition('elements', array('style' => 'submission.block')); ?>
</div>
<?php else : ?>
	<?php echo JText::_('ZOOfilter Module layout is empty. Check <a href="http://www.zoolanders.com/docs/zoofilter" target="_blank" title="docs">documentation</a>, you probably missed "Choose the filters" step.'); ?>
<?php endif; ?>