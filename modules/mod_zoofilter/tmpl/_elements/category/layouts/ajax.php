<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

	// add our script
	$app->document->addScript('modules:mod_zoofilter/tmpl/_elements/category/layouts/ajax/script.min.js');
	$app->document->addStylesheet('zlfw:assets/css/zl_ui.css');

	// init vars
	$txtChoose 		= $params->find('layout._choosetext', 'Choose');
	$hide_empty 	= $params->find('specific._hide_empty', 0);
	$application	= $element->getType()->getApplication();
	$type			= $element->getType()->id;
	$attribs		= '';
	$id 			= $element->identifier.$instance;
	$search_id 		= JRequest::getInt('search_id', null);

	// set attributes
	$attribs 		.= $params->find('search._required', 0) ? ' required' : '';

	// check Choose text
	$txtChoose = $txtChoose == 'Choose' || $txtChoose == '' ? JText::_('PLG_ZOOFILTER_CHOOSE') : JText::_($txtChoose);

	// set url
	$url = $app->link(array('controller' => 'zoofilter', 'call_type' => $type, 'format' => 'raw', 'app_id' => $application->id, 'search_id' => $search_id), false);

	// prepare values
	$value = isset($value[0]) ? $value : array('0' => '');
	$values = explode(',', urldecode($value[0]));
	
	// render selects
	$selects = array();
	foreach($values as $key => $val)
	{
		if($key != 0) $root_cat = $values[$key-1];

		// get categories
		$categories = $app->zfhtml->_('zoo.categoryList', $application, $root_cat, 0, $hide_empty, '', '', $type);

		// set options from categories
		foreach ($categories as $key => $category)
		{
			$categories[$key] = $app->html->_('select.option', $category->id, $category->treename);
		}

		// set Choose option
		array_unshift($categories, $app->html->_('select.option', '', $txtChoose));

		$selects[] = '<div class="form-element-subrow">'.$app->html->_('zoo.genericlist', $categories, '', $attribs, 'value', 'text', $val, false, false).'</div>';
	}
?>

	<?php echo implode('', $selects);?>
	<input type="hidden" name="<?php echo 'elements['.$id.'][]' ?>" value="<?php echo urldecode($value[0]) ?>">

	<script type="text/javascript">
	jQuery(document).ready(function ($) {
		jQuery('#mod-zoofilter-<?php echo $module_id ?> .itemcategory<?php echo $instance ?>').ZFchainedAjax({
			url: '<?php echo $url ?>',
			txtChoose: '<?php echo $txtChoose ?>',
			hide_empty: <?php echo $hide_empty ?>,
			searchAnySelection: <?php echo $params->find('layout._searchanyselection', 0) ?>
		})
	}); 
	</script>