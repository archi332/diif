<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ZOOfilterRenderer
		The class for rendering elements in the module
*/

// We need to load the ItemRenderer class if not existing (i.e.: outside zoo)
if(!class_exists('ItemRenderer'))
{
	$zoo = App::getInstance('zoo');
	$zoo->renderer->create('item');
}

class ZOOfilterRenderer extends ItemRenderer {

	protected $_type;
    protected $_form;

	/*
		Function: render
			Render objects using a layout file.

		Parameters:
			$layout - Layout name.
			$args - Arguments to be passed to into the layout scope.

		Returns:
			String
	*/
	public function render($layout, $args = array())
	{
		// set type
		$this->_type = isset($args['type']) ? $args['type'] : null;
		
		if(array_key_exists('module_id', $args))
		{
			$this->_module_id = $args['module_id'];
		}
		
		// render layout
		$result = parent::render($layout, $args);

		return $result;
	}

	/*
		Function: checkPosition
			Check if position generates output.

		Parameters:
			$position - Position name.

		Returns:
			Boolean
	*/
	public function checkPosition($position) 
	{
		foreach ($this->_getConfigPosition($position) as $data) {
            if ($element = $this->_type->getElement($data['element'])) {
                return true;
            }
        }
		
		return false;
	}

	/*
		Function: renderPosition
			Render position output.

		Parameters:
			$position - Position name.
			$args - Arguments to be passed to into the position scope.

		Returns:
			Void
	*/
	public function renderPosition($position, $args = array()) 
	{	
		// init vars
		$elements = array();
		$output   = array();
		$el_instances = array();
		$user	  = $this->app->user->get();

		// get style
		$style = isset($args['style']) ? $args['style'] : 'default';

		// store layout
		$layout = $this->_layout;

		// render elements
		foreach ($this->_getConfigPosition($position) as $i => $data) {
		
			
            if ($element = $this->_type->getElement($data['element'])) {

				if (!$element->canAccess()) continue;
				
				// save the element instance
				if (empty($el_instances[$element->identifier])) {
					$el_instances[$element->identifier] = 1;
				} else {
					$el_instances[$element->identifier] += 1;
				}
				
                // set arguments
                $params = array_merge($data, $args);
				$instance = $el_instances[$element->identifier];
				
				// ZOOlingual integration
				$render = true;
				$item = $element->getItem();

				$this->app->event->dispatcher->notify($this->app->event->create(null, 'element:beforedisplay', array('render' => &$render, 'element' => $element, 'params' => $params)));

				if ($render) {				
	                // check value
	                $elements[] = compact('element', 'params', 'instance');
				}
            }
        }

		// posted values
		if(!isset($this->_module_id))
		{
			$this->_module_id = 0;
		}
		$values = unserialize($this->app->system->application->getUserState( 'ZOOFILTER_SEARCH_FORM_' . $this->_module_id));
		$this->app->system->application->setUserState('ZOOFILTER_SEARCH_FORM_'.$this->_module_id, serialize(null));		
        foreach ($elements as $i => $data) 
		{
			$params = array_merge(array('first' => ($i == 0), 'last' => ($i == count($elements)-1)), $data['params']);
			$params = $this->app->data->create($params);
			$elType = $data['element']->getElementType();
			$instance = $data['instance']-1;
			$row_id = $i.'_'.$data['element']->identifier;
			
			// if element compatible add instance to control
			$instanceID = '';
			if ($elType == 'relatedcategories' || $elType == 'relatedcategoriespro' || $elType == 'itemcategory') {
				$instanceID = '-'.$instance;
			}
			
			// add tooltip
			$tooltip = '';
			if ($params->get('show_tooltip') && ($description = $element->config->get('tooltip'))) {
				$tooltip = ' class="hasTip" title="'.$description.'"';
			}
			
			// TODO
			$tooltip = $params->get('tooltip');
			
			// create label
			$label  = '<label for="'.$row_id.'">';
			$label .= $params->get('altlabel') ? $params->get('altlabel') : $data['element']->config->get('name');
			$label .= '</label>';
			
			$output[$i] = '<div class="form-element-row '.$elType.' '.$elType.'-'.$instance.'">';
			$output[$i] .= $params->get('showlabel') ? $label : '';
			
			if ($elType == 'staticcontent'){
				$output[$i] .= $data['element']->render($params);
			} else {
				$output[$i] .= modZOOfilterHelper::getSearchInput($data['element'], @$values[$data['element']->identifier.$instanceID], $params, $instance, $this->_module_id);
			}
			
			$output[$i] .= '</div>';
        }

		// restore layout
		$this->_layout = $layout;

		return implode("\n", $output);
	}

		/*
		Function: getPositions
			Retrieve positions of a layout.

		Parameter:
			$dir - point separated path to layout, last part is layout

		Returns:
			Array
	*/
	public function getPositions($dir) {

		// init vars
		$positions = array();

		$parts  = explode('.', $dir);
		$layout = array_pop($parts);
		$path   = implode('/', $parts);

		// parse positions xml
		if ($xml = simplexml_load_file(JPath::find($this->_getPath($path), $this->_xml_file))) {
			foreach ($xml->children() as $pos) {
				if ((string) $pos->attributes()->layout == $layout) {
					$positions['name'] = $layout;

					foreach ($pos->children() as $child) {

						if ($child->getName() == 'name') {
							$positions['name'] = (string) $child;
						}

						if ($child->getName() == 'position') {
							if ($child->attributes()->name) {
								$name = (string) $child->attributes()->name;
								$positions['positions'][$name] = (string) $child;
							}
						}
					}

					break;
				}
			}
		}

		return $positions;
	}


    protected function _getConfigPosition($position) {
    	
        $application = $this->_type->getApplication();
		$type		 = $this->_type;
		$config		 = $this->getConfig('item')->get($application->getGroup().'.'.$type->id.'.'.$this->_layout);

        return $config && isset($config[$position]) ? $config[$position] : array();
    }

}