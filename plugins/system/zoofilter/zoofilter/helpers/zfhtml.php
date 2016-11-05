<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: HTMLHelper
   	  A class that contains zoo html functions
*/
class ZFHTMLHelper extends AppHelper {

	public function _($type) {

		// get arguments
		$args = func_get_args();

		// Check to see if we need to load a helper file
		$parts = explode('.', $type);

		if (count($parts) >= 2) {
			$func = array_pop($parts);
			$file = array_pop($parts);

			if (in_array($file, array('zoo', 'control')) && method_exists($this, $func)) {
				array_shift($args);
				return call_user_func_array(array($this, $func), $args);
			}
		}

		return call_user_func_array(array('JHTML', '_'), $args);

	}

	/*
		Function: categoryList
			Returns category list
	*/
	public function categoryList($application, $root_cat=0, $maxLevel=9999, $hide_empty=false, $prefix='-&nbsp;', $spacer='.&nbsp;&nbsp;&nbsp;', $type = null)
	{
		// get category tree list
		$list = $this->app->tree->buildList($root_cat, $application->getCategoryTree(true, null, false), array(), $prefix, $spacer, '', 0, $maxLevel);

		// create options
		$categories = array();
		foreach ($list as $category)
		{
			if ($hide_empty && $type) 
			{
				// set query
				$model = $this->app->zlmodel->getNew('item');
				$model->setState('select', 'DISTINCT a.*');
				$model->setState('apps', $application->id);
				$model->setState('types', $type);
				$model->setState('categories', array($category->id));
				$model->setState('published', true);

				// retrieve items
				$items = $model->getList();

				// if no items skip category
				if (empty($items)) continue;
			}
			
			// save category
			$categories[] = $category;
		}

		return $categories;
	}

	/**
	 * Generates an HTML radio list.
	 *
	 * @param   array    $data       An array of objects
	 * @param   string   $name       The value of the HTML name attribute
	 * @param   string   $attribs    Additional HTML attributes for the <select> tag
	 * @param   mixed    $optKey     The key that is selected
	 * @param   string   $optText    The name of the object variable for the option value
	 * @param   string   $selected   The name of the object variable for the option text
	 * @param   boolean  $idtag      Value of the field id or null by default
	 * @param   boolean  $translate  True if options will be translated
	 *
	 * @return  string HTML for the select list
	 *
	 * @since  3.0
	 */
	public function radiolist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false,
		$translate = false)
	{
		reset($data);
		$result = array();

		if (is_array($attribs))
		{
			$attribs = JArrayHelper::toString($attribs);
		}

		$id_text = $idtag ? $idtag : $name;

		foreach ($data as $obj)
		{
			$k = $obj->$optKey;
			$t = $translate ? JText::_($obj->$optText) : $obj->$optText;
			$id = (isset($obj->id) ? $obj->id : null);

			$extra = '';
			$extra .= $id ? ' id="' . $obj->id . '"' : '';
			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object($val) ? $val->$optKey : $val;
					if ($k == $k2)
					{
						$extra .= ' selected="selected"';
						break;
					}
				}
			}
			else
			{
				$extra .= ((string) $k == (string) $selected ? ' checked="checked"' : '');
			}
			$result[] = '<input type="radio" name="' . $name . '"' . ' id="' . $id_text . $k . '" value="' . $k . '"' . ' ' . $extra . ' '
				. $attribs . '/>' . "\n\t" . '<label for="' . $id_text . $k . '"' . ' id="' . $id_text . $k . '-lbl" class="radiobtn">' . $t
				. '</label>';
		}
		return $result;
	}
}