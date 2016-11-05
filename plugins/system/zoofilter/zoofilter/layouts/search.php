<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// include assets css/js
if (strtolower(substr($GLOBALS[($this->app->joomla->isVersion('1.5') ? 'mainframe' : 'app')]->getTemplate(), 0, 3)) != 'yoo') {
	$this->app->document->addStylesheet('assets:css/reset.css');
}
$this->app->document->addStylesheet($this->template->resource.'assets/css/zoo.css');
$this->app->document->addStylesheet('zoofilter:assets/css/zoofilter.css');

$css_class = $this->application->getGroup( ) . '-' . $this->template->name;
$layout = $this->item_layout;
$total = $this->total;

	/* <?php if ( $this->show_permalink ): ?>
		<div class="permalink"><a href="<?php echo $this->permalink;?>"><?php echo JText::_('PLG_ZOOFILTER_GET_PERMALINK'); ?></a></div>
	<?php endif; ?> */

?>

<div id="yoo-zoo" class="yoo-zoo zoo-filter-result <?php echo $css_class; ?>">
	
	<?php if ($this->show_ordering): ?>
	<div class="ordering">
		<form name="zoofilter_ordering" method="GET" action="<?php echo JRoute::_('index.php');?>">
			<input type="hidden" name="option" value="com_zoo" />
			<input type="hidden" name="controller" value="zoofilter" />
			<input type="hidden" name="task" value="dosearch" />
			<input type="hidden" name="search_id" value="<?php echo $this->search_id; ?>" />
			<input type="hidden" name="app_id" value="<?php echo $this->app_id; ?>" />
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid', ''); ?>" />
			<select name="order">
				<?php if (!empty($this->order_elements)): foreach( $this->order_elements as $element ): ?>
				<option value="<?php echo $element['element']->identifier; ?>" <?php echo (JRequest::getVar('order', '') == $element['element']->identifier) ? 'selected="selected"' : ''; ?>>
					<?php echo $element['label']; ?>
				</option>
				<?php endforeach; else : ?>
				<option value="_itemname">
					<?php echo JText::_('PLG_ZOOFILTER_NAME'); ?>
				</option>
				<?php endif; ?>
			</select>
			<select name="direction">
				<option value="asc" <?php echo (JRequest::getVar('direction', '') == 'asc' ? 'selected="selected"' : ''); ?>><?php echo JText::_('PLG_ZOOFILTER_ASC');?></option>
				<option value="desc" <?php echo (JRequest::getVar('direction', '') == 'desc' ? 'selected="selected"' : ''); ?>><?php echo JText::_('PLG_ZOOFILTER_DESC');?></option>
			</select>
			<input type="submit" name="do_order" value="<?php echo JText::_('PLG_ZOOFILTER_SORT');?>" />
		</form>
	</div>
	<?php endif; ?>
		
	<?php if ( $this->show_title ): ?>
		<h1 class="title"><?php echo JText::_( $this->page_title ); ?></h1>
	<?php endif; ?>
	

	
	<?php
	// render items
	if ( count( $this->items ) )
	{
		$itemstitle = '';
		echo $this->partial( 'items', compact( 'itemstitle', 'layout', 'total' ) );
	}
	else
	{
		echo JText::_('PLG_ZOOFILTER_NO_RESULTS');	
	}
	?>

</div>