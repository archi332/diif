<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// add libraries
$zoo->document->addScript('modules:mod_zoofilter/assets/js/modernizr.custom.js');
//$zoo->document->addScript('modules:mod_zoofilter/assets/js/webforms2/webforms2-p.js');
$zoo->document->addScript('modules:mod_zoofilter/assets/js/jquery.placehold.min.js');

// add template css and js
$zoo->document->addStylesheet('modules:mod_zoofilter/tmpl/default/style.css');
$zoo->document->addScript('modules:mod_zoofilter/tmpl/default/script.min.js');
$module_id = $module->id;
?>

<div class="zoo-filter form-wrapper" id="mod-zoofilter-<?php echo $module_id;?>">
	<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="<?php echo $form_method; ?>" id="zoo-filter-form-<?php echo $module_id;?>" name="zoo-filter-form-<?php echo $module_id;?>">
		
		<div class="form-inner">

			<?php echo $renderer->render('item.'.$module_layout, compact('type', 'values', 'module_id')); ?>
			
			<input type="hidden" name="option" value="com_zoo" />
			<input type="hidden" name="task" value="search" />
			<input type="hidden" name="controller" value="zoofilter" />
			<input type="hidden" name="type" value="<?php echo $type_name;?>" />
			<input type="hidden" name="app_id" value="<?php echo $application->id; ?>" />
			<input type="hidden" name="show_title" value="<?php echo $show_title; ?>" />
			<input type="hidden" name="show_ordering" value="<?php echo $show_ordering; ?>" />
			<input type="hidden" name="show_permalink" value="<?php echo $show_permalink; ?>" />
			<input type="hidden" name="redirect_if_one" value="<?php echo $redirect_if_one; ?>" />
			<input type="hidden" name="columns" value="<?php echo $columns; ?>" />
			<input type="hidden" name="page_title" value="<?php echo $page_title; ?>" />
			<input type="hidden" name="layout" value="<?php echo $result_layout; ?>" />
			<input type="hidden" name="page_layout" value="<?php echo $page_layout; ?>" />
			<input type="hidden" name="elayout" value="<?php echo $elayout; ?>" />
			<input type="hidden" name="ordering_layout" value="<?php echo $ordering_layout; ?>" />
			<input type="hidden" name="type" value="<?php echo $type_name; ?>" />
			<input type="hidden" name="module_id" value="<?php echo $module_id; ?>" />
			
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />	
			<input type="hidden" name="items_per_page" value="<?php echo $items_per_page; ?>" />
			<input type="hidden" name="search_limit" value="<?php echo $search_limit; ?>" />
			
			<?php if ($show_submit) : ?>
			<button type="submit" class="button"><?php echo JText::_( 'PLG_ZOOFILTER_SEARCH' ); ?></button>
			<?php endif; ?>
			<?php if ($show_clear) : ?>
			<button type="button" class="button zfac-clear"><?php echo JText::_( 'PLG_ZOOFILTER_CLEAR' ); ?></button>
			<?php endif; ?>
			
			<div class="clearfix"></div>

		</div>
		
	</form>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#mod-zoofilter-<?php echo $module_id;?>').ZOOfilterSearchDefault({button: '.zfac-clear'});
		});
	</script>
</div>