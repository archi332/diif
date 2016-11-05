<?php
/**
 * @package   com_zoo
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="uk-grid" data-uk-grid-margin>

	<div class="uk-width-medium-3-4 <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-push-medium-1-4'; ?>">
		<?php if ($item->canEdit()) : ?>
		    <?php $edit_link = $this->app->route->submission($item->getApplication()->getItemEditSubmission(), $item->type, null, $item->id, 'itemedit'); ?>
		    <div class="uk-align-right">
		        <a href="<?php echo JRoute::_($edit_link); ?>" title="<?php echo JText::_('Edit Item'); ?>" class="item-icon edit-item"><?php echo JText::_('Edit Item'); ?></a>
		    </div>
		<?php endif; ?>
		<?php if ($this->checkPosition('title')) : ?>
		<h1 class="uk-h1"><?php echo $this->renderPosition('title'); ?></h1>
		<?php endif; ?>

		<?php if ($this->checkPosition('specification')) : ?>
		<ul class="uk-list">
			<?php echo $this->renderPosition('specification', array('style' => 'uikit_list')); ?>
		</ul>
		<?php endif; ?>

		<?php if ($this->checkPosition('description')) : ?>
			<?php echo $this->renderPosition('description', array('style' => 'uikit_block')); ?>
		<?php endif; ?>

		<?php if ($this->checkPosition('movie')) : ?>
			<?php echo $this->renderPosition('movie', array('style' => 'uikit_block')); ?>
		<?php endif; ?>

	</div>

	<?php if ($this->checkPosition('sidebar')) : ?>
	<div class="uk-width-smal-1-4 <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-pull-medium-3-4'; ?>">
		<?php echo $this->renderPosition('sidebar', array('style' => 'uikit_panel')); ?>
	</div>
	<?php endif; ?>

</div>