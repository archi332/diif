<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: ZF Helper
		The ZOOfilter helper Class for ZOO
*/
class ZfHelper extends AppHelper {
	
	/*
		Function: getCountryOptions
			Returns well formated Countries options
		
		Parameters:
			$selectable_countries - Element Chosen Countries
	*/
	public function getCountryOptions($selectable_countries)
	{
		$element_options = array();
		$countries = $this->app->country->getIsoToNameMapping();
		$keys = array_flip($selectable_countries);
		$countries = array_intersect_key($countries, $keys);
		foreach ($countries as $key => $country) {
			$element_options[] = array('name' => $country, 'value' => $key);
		}
		return $element_options;
	}

	/*
		Function: getSearchParams
			Returns the search params from ZF database
		
		Parameters:
			$search_id
	*/
	public function getSearchParams($search_id)
	{
		// init vars
		$db 	= $this->app->database;
		$query 	= new ZLQuery();

		$query->select('search_params')->from('#__zoo_zoofilter_searches')->where('search_id = '.(int)($search_id));
		$db->setQuery($query);
		
		jimport( 'joomla.html.parameter' );
		$params = json_decode($db->loadResult(), true); 
		
		if($params){
			$this->decodeParams($params);
		} else {
			$params = array();
		}

		return $this->app->data->create($params);
	}
	
	protected function decodeParams(&$params)
	{
		foreach($params as &$param)
		{
			if(is_array($param))
			{
				$this->decodeParams($param);
			}
			else
			{
				$param = urldecode($param);
			}	
		}
	}
}