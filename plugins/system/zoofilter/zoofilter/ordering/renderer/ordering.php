<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: OrderingRenderer
		The class for fetching elements to show for the ordering bar
*/
class OrderingRenderer extends AppRenderer {

	protected $_type;
    protected $_form;
    protected $_config;
	protected $_config_file = 'positions.config';
	protected $_xml_file = 'positions.xml';

	/*
		Function: render
			Render objects using a layout file.

		Parameters:
			$layout - Layout name.
			$args - Arguments to be passed to into the layout scope.

		Returns:
			String
	*/
	public function render($layout, $args = array()) {

		// set type
		$this->_type = isset($args['type']) ? $args['type'] : null;
		
		$this->_layout = $layout;
		
		$result = array();
		// render layout
		foreach($this->getPositions($layout) as $position)
		{
			$result = $this->renderPosition($position);
		}

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
	public function checkPosition($position) {

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
	public function renderPosition($position, $args = array()) {
		
		// init vars
		$elements = array();
		$output   = array();
		$user	  = $this->app->user->get();

		// get style
		$style = isset($args['style']) ? $args['style'] : 'default';

		// store layout
		$layout = $this->_layout;

		// render elements
		foreach ($this->_getConfigPosition($position) as $data) {
            if ($element = $this->_type->getElement($data['element'])) {

				if (!$element->canAccess()) {
					continue;
				}

                // set params
                $params = array_merge($data, $args);

                // check value
                $elements[] = compact('element', 'params');
            }
        }

        foreach ($elements as $i => $data) {
            $params  = array_merge(array('first' => ($i == 0), 'last' => ($i == count($elements)-1)), $data['params']);
			$params = $this->app->data->create($params);
			
			// create label
			$output[$i]['label'] = $params->get('altlabel') ? $params->get('altlabel') : $data['element']->getConfig()->get('name');
			$output[$i]['element'] = $data['element'];
        }

		// restore layout
		$this->_layout = $layout;
		return $output;
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
						if ( $pos_name = $child->attributes()->name) {
							$positions['name'] = (string) $pos_name;
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

	/*
		Function: getConfig
			Retrieve position configuration.

		Parameter:
			$dir - path to config file

		Returns:
			AppParameter
	*/
	public function getConfig($dir) {

		// config file
		if (empty($this->_config)) {

			if ($file = $this->_path->path('default:'.$dir.'/'.$this->_config_file)) {
				$content = JFile::read($file);
			} else {
				$content = null;
			}

			$this->_config = $this->app->parameter->create($content);
		}

		return $this->_config;
	}

	/*
		Function: saveConfig
			Save position configuration.

		Parameter:
			$config - Configuration
			$file - File to save configuration

		Returns:
			Boolean
	*/
	public function saveConfig($config, $file) {

		if (JFile::exists($file) && !is_writable($file)) {
			throw new AppException(sprintf('The config file is not writable (%s)', $file));
		}

		if (!JFile::exists($file) && !is_writable(dirname($file))) {
			throw new AppException(sprintf('Could not create config file (%s)', $file));
		}

		// Joomla 1.6 JFile::write expects $buffer to be reference
		$config_string = (string) $config;
		return JFile::write($file, $config_string);
	}

	public function pathExists($dir) {
		return (bool) $this->_getPath($dir);
	}

    protected function _getConfigPosition($position) {
    	
		if( is_array($position) )
		{
			$position = array_shift(array_keys($position));
		}
		
		$parts = explode(".", $this->_layout);
		$layout = array_pop($parts);
		
        $application = $this->_type->getApplication();
		$type		 = $this->_type;
		$config		 = $this->getConfig('item')->get($application->getGroup().'.'.$type->id.'.'.$layout);

        return $config && isset($config[$position]) ? $config[$position] : array();
    }

}