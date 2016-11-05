<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class modZOOfilterHelper 
{
	
	/**
	 * Get the html search input field for the given element
	 * 
	 * @param Element The element
	 */
	public static function getSearchInput( $element, $value = '', $params = array(), $instance = null, $module_id = 0 )
	{
	
		// Get the ZOO App instance
		$app = App::getInstance('zoo');
		
		$html = array();
		$options = array();
		$attributes = '';
		$instance = '-'.(string)($instance);
		$elType = $element->getElementType();
		
		// reencode values as url (they are decoded for the search)
		self::encodeValue($value);
		
		// if no group declared, use Element Type
		$group = $params->find('specific._zfgroup', $elType);
		switch($group)
		{
			/**
			 * Date: display the calendar
			 */
			case 'date':
				
				// render layout
				if ($layout = self::getLayout('date/layouts/'.$params->find('layout._layout'))) {
					$html[] = self::renderLayout($layout, compact('params', 'element', 'value', 'instance', 'app'));
				}
				break;
				
			/**
			 * Rating: display a list of possible ratings
			 */
			case 'rating':
				$options = array( JText::_('PLG_ZOOFILTER_SELECT_RATING'), '1', '2', '3', '4', '5' );
				$html[] = $app->html->arrayList( $options, array(), $element->identifier );
				break;
				
			/**
			 * Categories
			 */
			case 'category': // group
			case 'itemcategory':
			case 'relatedcategories':
			case 'relatedcategoriespro':

				$root_cat = $params->find('specific._chosencat', 0);
				$default  = $params->find('specific._default', array());
				$value	  = $value ? $value : $default;
				
				// render layout
 				if ($layout = self::getLayout('category/layouts/'.$params->find('layout._layout'))) {
					$html[] = self::renderLayout($layout, compact('params', 'element', 'value', 'instance', 'app', 'root_cat', 'module_id'));
				}
				break;

			/**
			 * Option display
			 */
			case 'option': // group
			case 'checkbox':
			case 'radio':
			case 'select':
			case 'selectpro':

				// init vars
				$element_options = array();
				$default = $params->find('specific._default', array());
				$value 	 = $value ? $value : $default;
				
				// get options
				if ($elType == 'country'){
					$element_options = $app->zf->getCountryOptions($element->config->get('selectable_country', array()));
				} else {
					$element_options = $element->config->get('option', array());
				}
				
				if (count($element_options)) {
					// render layout
					if ($layout = self::getLayout('option/layouts/'.$params->find('layout._layout'))) {
						$html[] = self::renderLayout($layout, compact('params', 'element', 'value', 'instance', 'app', 'element_options', 'default'));
					}
				}
				break;
				
			/**
			 * Input display
			 */
			case 'input': // group
			case 'text':
			case 'textarea':
			case 'textareapro':
			case 'textpro':
			case 'itemname':
			
				// render layout
				if ($layout = self::getLayout('input/layouts/'.$params->find('layout._layout'))) {
					$html[] = self::renderLayout($layout, compact('params', 'element', 'value', 'instance', 'app'));
				}
				break;
				
			/**
			 * Default
			 */
			default:
			
				
				break;
		}

		return implode("\n", $html);
	}

	protected static function encodeValue(&$value)
	{
		if(is_array($value))
		{
			foreach($value as &$single)
			{
				self::encodeValue($single);
			}
		} 
		else {
			$value = urlencode($value);	
		}
	}

	public static function addParentCategories($category, $value = null)
	{
		if(is_array($category))
		{
			$category = array_shift($category);
		}
		$zoo = App::getInstance('zoo');
		$parent = $zoo->table->category->get($category)->parent;
		$categories = array();	
		if($parent)
		{
			if(!$value)
			{
				$value = $category;
			}
			$categories[$parent] = $value;
			return self::addParentCategories($parent, $categories);
		}
		
		return $value;
	}
	
	/*
		Function: getLayout
			Get element ZOOfilter template layout path

		Returns:
			String - Layout path
	*/
	public function getLayout($layout = null) {

		// set default
		if (!$layout) {
			$layout = "default.php";
		}

		// find layout
		return App::getInstance('zoo')->path->path("modules:mod_zoofilter/tmpl/_elements/{$layout}");
	}
	
	/*
		Function: renderLayout
			Renders the element using template layout file.

	   Parameters:
            $__layout - layouts template file
	        $__args - layouts template file args

		Returns:
			String - html
	*/
	protected function renderLayout($__layout, $__args = array()) {

		// init vars
		if (is_array($__args)) {
			foreach ($__args as $__var => $__value) {
				$$__var = $__value;
			}
		}

		// render layout
		$__html = '';
		ob_start();
		include($__layout);
		$__html = ob_get_contents();
		ob_end_clean();

		return $__html;
	}

}