<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: Zoofilter
		The controller class for zoofilter tasks
*/
class ZoofilterController extends AppController {
	
	protected $search_config = array();
	protected $items = array();
	protected $itemsTotal = 0;
	protected $search_params = null;
	protected $search_id = 0;
	protected $application = null;
	
	/*
		Function: search
			Do the actual search

		Returns:
			HTML
	*/
	public function search( )
	{
		// init vars
		$db 		= $this->app->database;
		$menuId		= JRequest::getInt('Itemid') ? '&Itemid='.JRequest::getInt('Itemid') : '';

		// get App
		$this->application = $this->app->table->application->get(JRequest::getInt('app_id', 1));

		// for ZOOorder
		$this->addDefaultParams();

		// get compare ID
		$id = $this->getSearchId();
		
		$this->setRedirect(JRoute::_('index.php?option=com_zoo&controller=zoofilter&task=dosearch&search_id='.$id.'&app_id='.$this->application->id.$menuId, false));
		$this->redirect();
	}

	/*
		Function: getSearchId
	*/
	protected function getSearchId()
	{
		// init vars
		$params 	= JRequest::get('request');
		$params 	= json_encode($params);
		$user_id 	= JFactory::getUser()->id;
		$db 		= $this->app->database;
		$query 		= new ZLQuery();

		// set hash
		$uuid = md5($params);

		// seek for saved search
		$query->select('search_id')->from('#__zoo_zoofilter_searches')->where('search_uuid LIKE '.$db->Quote($uuid));
		$db->setQuery($query);
		
		$this->search_id = $db->loadResult();
		
		// generate one if first time searching with this params
		if(!$this->search_id)
		{
			$query = "INSERT INTO #__zoo_zoofilter_searches (search_uuid, search_params, user_id, datetime) ";
			$query .= "VALUES (".$db->quote($uuid).", ".$db->quote($params).", ".$user_id.", NOW())";
			$db->query($query);
			
			$this->search_id = $db->insertid();
		}
		
		return $this->search_id;
	}
	
	/*
		Function: addDefaultParams
	*/
	protected function addDefaultParams()
	{
		// init vars
		$category_id 		= JRequest::getVar('category_id', 0);
		$params	          	= $category_id ? $this->app->table->category->get($category_id)->getParams('site') : $this->application->getParams('frontpage');
		$items_per_page   	= $params->get('config.items_per_page', 15);
		
		// Ordering Layout
		jimport( 'joomla.plugin.plugin' );
		jimport( 'joomla.html.parameter' );
		$plugin =& JPluginHelper::getPlugin('system', 'zoofilter');
		$pluginParams = $this->app->data->create($plugin->params);
		
		$ordering_layout = $pluginParams->get('ordering_layout', 'ordering');
		
		// Render Layout
		$layout = JRequest::getVar('result_layout', 'default');
	}
	
	protected function addDefaultParam($name, $value)
	{
		if(!JRequest::getVar($name, null))
		{
			JRequest::setVar($name, $value);
		}
	}
	
	/*
		Function: dosearch
	*/
	public function dosearch()
	{
		// init vars
		$search_id 				= JRequest::getInt('search_id', null);
		$search_params 			= $this->app->zf->getSearchParams($search_id);

		// get App
		$this->application = $this->app->table->application->get($search_params->get('app_id', 1));

		// get Type
		$type = $this->application->getType($search_params->get('type', ''));

		// save params
		$this->search_params = $search_params;
			
		// Request Variables
		$page = JRequest::getInt( 'page', 1 );
		
		// Get site params for the current application
		$params = $this->application->getParams('site');
		
		// Search Configuration
		$elayout = JFile::stripExt($search_params->get('elayout'));

		// set renderer
		$search_render = $this->app->renderer->create('item')->addPath(array($this->app->path->path('modules:mod_zoofilter')));
		$search_config = $search_render->getConfig('item')->get($this->application->getGroup().'.'.$type->id.'.'.$elayout);
		$this->search_config = @$search_config['elements'];
		
		if(!$this->search_config)
		{
			$this->search_config = array();
		}
		
		// as logic is new feature in zoofilter 2.5, we make sure it's allways set
		foreach ($this->search_config as $key => $row) 
		{
			if (!array_key_exists('logic', $row)) 
			{
				$this->search_config[$key]['logic'] = "AND";
			}
		}
		
		// The data passed by the search form
		$elements = $search_params->get('elements', array());
		
		// Apply filters
		$this->applyFilters( $elements );		
		
		$this->search_id = $search_id;
		
		$session_key = 'ZOOFILTER_SEARCH_FORM_' . $search_params->get('module_id');
		$this->app->system->application->setUserState($session_key, serialize($elements));
		
		// Display	
		$this->display();
	}
		
	public function display()
	{	
		$search_params 		= $this->search_params;
		$items 				= $this->items;
		
		// Get site params for the current application
		$params = $this->application->getParams( 'site' );
	
		if (count($items) == 1 && $search_params->get('redirect_if_one')) {
			
			$item = array_pop($items);
			$link = JRoute::_('index.php?option=com_zoo&task=item&item_id='.$item->id, false);
			JFactory::getApplication()->redirect($link);
		}
		
		// Pepare the view
		$view = new AppView( array(
			'name' => 'category'
		));
		
		$layout = strlen($search_params->get('page_layout')) ? $search_params->get('page_layout') : 'search';
		
		// Json support
		if(JRequest::getVar('format', '') == 'json')
		{
			$layout = 'json';
		}
		
		$item_layout = $search_params->get('layout');

		$view->addTemplatePath( $this->application->getTemplate( )->getPath( ) );
		$view->addTemplatePath( $this->app->path->path('zoofilter:layouts') );
		$view->setLayout( $layout );

		// Add the necessary variables for the view
		$view->app = $this->app;
		$view->items = $items;
		$view->application = $this->application;
		$view->item_layout = $item_layout;
		
		$item_ids = array( );
		foreach ( $items as $item ) 
		{
			$item_ids[] = $item->id;
		}

		// get item pagination
		$items_per_page = $search_params->get('items_per_page');
		$page = JRequest::getVar( 'page', 1 );
		$view->pagination = $this->app->pagination->create( $this->itemsTotal, $page, $items_per_page, 'page', 'app' );
		$view->pagination->setShowAll( $items_per_page == 0 );
		$uri = JURI::getInstance();
		$uri->setVar('page', null); 
		$view->total = $this->itemsTotal;
		
		$view->pagination_link = $uri->toString();
		
		// set template and params
		$view->assign('template', $this->application->getTemplate());
		$view->params = $params;
		$view->assign('search_params', $search_params);
		
		// set renderer
		$uri = JURI::getInstance();
		$permalink = $uri->toString(array('scheme', 'host', 'port')) . JRoute::_('index.php?option=com_zoo&controller=zoofilter&task=dosearch&app_id='.$this->application->id.'&search_id='.$this->search_id);
		
		$view->show_permalink = $search_params->get('show_permalink');
		$view->permalink = $permalink;
		$view->app_id = $this->application->id;
		$view->search_id = $this->search_id;
		$view->show_title = $search_params->get('show_title');
		$view->show_ordering = $search_params->get('show_ordering');
		$view->columns = $search_params->get('columns');
		$view->page_title = $search_params->get('page_title');
		$view->renderer = $this->app->renderer->create( 'item' );
		$view->renderer->addPath( array($this->application->getTemplate( )->getPath( ), $this->app->path->path('zoofilter:'), $this->app->path->path( 'component.site:' )) );
		
		// Add ordering
		$this->app->path->register($this->app->path->path('zoofilter:ordering/renderer'), 'renderer');
		$order_renderer = $this->app->renderer->create('ordering')->addPath(array( $this->app->path->path('zoofilter:ordering')) );
		$elements_layout = $search_params->get('ordering_layout');
		$type = $this->application->getType($search_params->get('type'));
		
		$order_elements = $order_renderer->render('item.'.$elements_layout, compact('type') );
		$view->assign('order_elements', $order_elements);
		
		// display view				
		$view->display( );
	}
	
	/**
	 * Get element param
	 */
	protected function getParamFrom( $param, $element, $default='' )
	{
		// Search for the right element config
		foreach($this->search_config as $oc) if (@$oc['element'] == $element) {
			$params = $this->app->data->create($oc);
			return $params->find($param);
		}
		return '';
	}

	/**
	 * Apply the filters
	 */
	protected function applyFilters( $elements )
	{
		$search_params = $this->search_params;
		
		$type_id = $search_params->get('type');
		$app_id = $search_params->get('app_id');
		
		// Now thanks to the model
		$model = ZLModel::getInstance('Zoofilter', 'ZLModel');
		// $model = $this->app->zlmodel->getNew('zoofilter');
		
		$model->setState('select', 'DISTINCT a.*');
		$model->setState('app_id', $app_id);
		$model->setState('type', $type_id);
			  
		// Core: Name element
		if( strlen( trim( @$elements['_itemname'] ) ) )
		{
			$name = array(
				'value' => trim( @$elements['_itemname'] ),
				'type' => $this->getParamFrom('layout._search_type', '_itemname'),
				'logic' => $this->getParamFrom('search._logic', '_itemname')
			);
			
			$model->setState('itemname', $name);
		}
		unset($elements['_itemname']);
		
		/**
		 * Categories Core Element filter
		 */
		// retrieve Cat params
		$cat_search_config = array(); 
		$i = 0;
		
		foreach( $this->search_config as $key => $oc ) 
		{
			if ( @$oc['element'] == '_itemcategory' ) 
			{
				$cat_search_config['_itemcategory-'.$i] = $oc;
				$i++;
			}
		}
		
		// create the Category object
		$cat_elements = array(); $i = 0;

		foreach ( $elements as $key => $value ) 
		{
			if ( strpos( $key, "_itemcategory" ) !== false ) 
			{
				$el_key = '_itemcategory-'.$i;
				$cat_elements[$el_key]['values'] = $value;
				$cat_elements[$el_key]['params'] = @$cat_search_config[$el_key];
				unset($elements[$key]);
				$i++;
			}
		}
		
		if ( !empty($cat_elements) )
		{
			$cats_filter = array();
			foreach($cat_elements as $element)
			{
				$cat_filter = array();
				$params = $this->app->data->create($element['params']);

				$values = $element['values'];
				$cat_filter['logic'] = $params->find('search._logic', 'AND');
				$cat_filter['mode'] = $params->find('layout._mode', 'AND');
				$cat_filter['value'] = array();
				
				foreach ($values as $id) 
				{
					// Skip id if empty
					if (empty( $id )) continue;

					$id = explode(',', $id);
					$cat_filter['value'][] = array_pop($id);
				}
				
				$cats_filter[] = $cat_filter;
			}


			
			if( count($cat_filter) )
			{
				$model->setState('categories', $cats_filter);
			}			
		}
		
		/**
		 * Parse the other search values
		 */
		$filters = array();
		foreach ( $elements as $identifier => $value )
		{
			if(is_array($value))
			{
				$empty = true;
				// Skip unused elements
				foreach($value as $v)
				{
					if ( !empty( $v ) ) 
					{
						$empty = false;
					}	
				}
				
				if($empty)
				{
					continue;	
				}
			} 
			else 
			{
				if(empty($value))
				{
					continue;
				}
			}
			
			// Search for the right element config
			$search_type = $this->getParamFrom('layout._search_type', $identifier);
			$logic = $this->getParamFrom('search._logic', $identifier);
			$mode = $this->getParamFrom('layout._mode', $identifier);
			$convert = $this->getParamFrom('layout._convert', $identifier);
			
			// get Element type
			$el_class = $this->app->table->application->get($app_id)->getType($type_id)->getElement($identifier);
			
			$el_type = $el_class->config->type;
			
			// Options elements should always be searched exatcly, not partial, and we must decode them
			$is_select = ( $el_class instanceof ElementOption ) ? true : false;
			
			// is a multiple choice ?
			if ( is_array( $value ) && count( $value ) )
			{
				// more than one selected?
				if( count($value) > 1 )
				{
					// Search for 'value\nvalue\nvalue' pattern
					$selections = array();
					foreach ($value as $key => $option)
					{
						// Was something selected?
						if ( !empty( $option ) )
						{
							$selections[$key] = $option;
						}
					}
					
					$filters[$identifier] = array(
						'logic' => $logic,
						'value' => $selections,
						'mode' => $mode,
						'type' => $search_type,
						'el_type' => $el_type,
						'is_select' => $is_select,
						'convert' => $convert
					);
				}
				else // only one selection
				{
					$is_select ? $value = urldecode(array_shift($value)) : $value = array_shift( $value );
					$is_select ? $search_type = 'partial' : $search_type;
					$filters[$identifier] = array(
						'logic' => $logic,
						'value' => array($value),
						'mode' => $mode,
						'type' => $search_type,
						'el_type' => $el_type,
						'is_select' => $is_select,
						'convert' => $convert
					);
				}
			}
			else // is a single choice, multiselection is not allowed
			{
				if( strlen($value) )
				{					
					// Decode value
					if ($is_select) 
					{
						$value = urldecode($value);
					}
					
					$filters[$identifier] = array(
						'logic' => $logic,
						'value' => $value,
						'type' => $search_type,
						'el_type' => $el_type,
						'is_select' => $is_select,
						'convert' => $convert
					);
				}
			}
		}

		if( count( $filters ) )
		{
			$model->setState('elements', $filters);
		}
		
		$items_per_page = $search_params->get('items_per_page');
		$page = JRequest::getVar( 'page', 1 );
		
		$this->setOrder($model);

		if(!$items_per_page == 0)
		{
			$model->setState('limitstart', ($page - 1) * $items_per_page );
			$model->setState('limit', $items_per_page);
		}

		$this->items = $model->getList();
		$this->itemsTotal = $model->getResult();

		// Debug
		jimport( 'joomla.plugin.plugin' );
		jimport( 'joomla.html.parameter' );
		$plugin =& JPluginHelper::getPlugin('system', 'zoofilter');
		$pluginParams = $this->app->data->create($plugin->params);
		
		if ($pluginParams->get('debug', false) && JRequest::getVar('format', '') != 'json')
		{
			// pretty print of sql
			$find = Array("FROM", "WHERE", "AND", "ORDER BY", "LIMIT", "OR");
			$replace = Array("<br />FROM", "<br />WHERE", "<br />AND", "<br />ORDER BY", "<br />LIMIT", "<br />OR");
			$in = $model->getQuery();
			echo '<b>Query</b>';
			echo str_replace($find, $replace, $in);
			
			$in = $model->getResultQuery();
			echo '<br /><b>Result Query</b>';
			echo str_replace($find, $replace, $in);
		}
	}
	
	protected function setOrder(&$model)
	{
		$search_params = $this->search_params;
		
		$order = JRequest::getVar('order', $search_params->get('order'));
		$direction = JRequest::getVar('direction', $search_params->get('direction'));
		
		// default ordering from app config
		if(!$order)
		{
			$order = array_values((array)$this->application->getParams()->get('global.config.item_order', array('_itemname')));
		}
		else // order from result form
		{
			$order = array(
				0 => $order, // main order
				1 => $direction == 'desc' ? '_reversed' : '' // direction
			);
		}

		$model->setState('order_by', $order);
	}
	

	/***********************************************
	********** AJAX CATEGORY SEACH *****************
	*************************************************/
	/*
		Function: getCats
			Get the categories

	   Parameters:
            	root - root that will be used, subcategories are returned
            	allTheRest - if true all subcategories with their subcategories will be returned as formated tree
            		         otherwise only immediately subcategories are returned;
            		         used when max depth is set
            	app_id - application id

		Returns: JSON
	*/
	function getCats()
	{		
		$root 					= $this->app->request->getInt('root', 0);
		$allTheRest 			= $this->app->request->getInt('all', 0);
		$application 			= $this->app->request->getInt('app_id', 0);
		$hide_empty 			= $this->app->request->get('hide_empty', 'int', 0);

		$search_id = JRequest::getInt('search_id', null);
		$search_params = $this->app->zf->getSearchParams($search_id);

		// if no instance, get app from session
		$application = ($application != 0) ? $this->app->table->application->get($application) : $this->app->getApplication();

		if (is_object($application))
		{
			if ($allTheRest) {
				$maxLevel = 9999;
			} else {
				$maxLevel = 0;	
			} 

			// get categories
			$list = $this->app->zfhtml->_('zoo.categoryList', $application, $root, $maxLevel, $hide_empty, '', '');

			// create options
			$categories = array();
			foreach ($list as $category) {
				$categories[$category->id] = $category->treename;
			}

			echo json_encode($categories);
		}
		return;
	}

}

/*
	Class: ZoofilterControllerException
*/
class ZoofilterControllerException extends AppException {}