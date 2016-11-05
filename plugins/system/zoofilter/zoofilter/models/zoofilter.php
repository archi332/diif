<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register ZLModelItem class
App::getInstance('zoo')->loader->register('ZLModelItem', 'zlfw:models/item.php');

class ZLModelZoofilter extends ZLModelItem
{
	/*
        Function: _buildQueryWhere
            Builds WHERE query
    */
	protected function _buildQueryWhere(&$query)
	{
		// Apply basic filters
		$this->basicFilters($query);
		
		// Apply general item filters (type, app, etc)
		$this->itemFilters($query);
		
		// Element filters
		$this->elementFilters($query);
	}
	
	/**
	 * Apply element filters
	 */
	protected function elementFilters(&$query)
	{
		// Basic array
		$wheres = array(
			'OR' =>	array(),
			'AND' => array()
		);
		
		// item name filtering
		$name = $this->getState('itemname', null);

		if( $name )
		{
			$wheres[strtoupper($name['logic'])][] = 'a.name LIKE ' . $this->getQuotedValue( $name );
		}
		
		// Category filtering
		$categories = $this->getState('categories', null);
		
		if( $categories )
		{
			foreach($categories as $cats)
			{
				// build the where for ORs
				if ( strtoupper($cats['mode']) == 'OR' )
				{
					$wheres[$cats['logic']][] = "b.category_id IN (".implode(',', $cats['value']).")";
				} 
				else
				{
					// it's heavy query but the only way for AND mode
					foreach ($cats['value'] as $id) 
					{
						$wheres[$cats['logic']][]  = "a.id IN ("
									." SELECT b.id FROM ".ZOO_TABLE_ITEM." AS b"
									." LEFT JOIN " . ZOO_TABLE_CATEGORY_ITEM . " AS y"
									." ON b.id = y.item_id"
									." WHERE y.category_id = ".(int) $id .")";
					}
				}
				
			}
		}
		
		// Elements filters
		$elements = $this->getState('elements', null);
		
		$k = 0;
		if( $elements )
		{
			foreach($elements as $identifier => $element)
			{
								
				// Multiple choice!
				if( is_array( $element['value'] ) && !array_key_exists('from', $element['value']) && !array_key_exists('to', $element['value']))
				{
					$wheres[strtoupper($element['logic'])][] = $this->getMultipleSearch($identifier, $element['value'], $element['mode'], $k, $element['is_select'], $element['logic']);				
				} 
				else 
				{					
					// Special case : from and to values are here
					if(in_array(@$element['type'], array('range', 'rangeequal', 'outofrange', 'outofrangeequal'))){
						
						// Check if from or to are empty
						if(empty($element['value']['from'])){
							// Revert to the corresponding search type
							switch(@$element['type']){
								case 'range':
									$element['type'] = 'to';
									$element['value'] = @$element['value']['to'];
									break;
								case 'rangeequal':
									$element['type'] = 'toequal';
									$element['value'] = @$element['value']['to'];
									break;
								case 'outofrange':
									$element['type'] = 'from';
									$element['value'] = @$element['value']['to'];
									break;
								case 'outofrangeequal':
									$element['type'] = 'fromequal';
									$element['value'] = @$element['value']['to'];
									break;
							}
						} elseif(empty($element['value']['to'])){
							// Revert to the corresponding search type
							switch(@$element['type']){
								case 'range':
									$element['type'] = 'from';
									$element['value'] = @$element['value']['from'];
									break;
								case 'rangeequal':
									$element['type'] = 'fromequal';
									$element['value'] = @$element['value']['from'];
									break;
								case 'outofrange':
									$element['type'] = 'to';
									$element['value'] = @$element['value']['from'];
									break;
								case 'outofrangeequal':
									$element['type'] = 'toequal';
									$element['value'] = @$element['value']['from'];
									break;
							}
						}
						// No else here. In case that both from and to and compiled, the data is already ok
						// The "empty both" case was alredy taken care before (multiple choice)
						
						// Special case : from and to values are here
						if(in_array(@$element['type'], array('range', 'rangeequal', 'outofrange', 'outofrangeequal'))){
							$temp = $element;
							$temp['value'] = @$element['value']['from'];
							$search_value_from = $this->getQuotedValue($temp);
							$temp['value'] = @$element['value']['to'];
							$search_value_to = $this->getQuotedValue($temp);
						} else {
							$search_value = $this->getQuotedValue($element);
						}
					} 
					else 
					{
						$search_value = $this->getQuotedValue($element);
					}
					
					$convert = @$element['convert'];
					if(!$convert){
						$convert = 'DECIMAL';
					}
					
					if (@$element['el_type'] == 'datepro' || @$element['el_type'] == 'date') {
						$search_type = $element['type'];
						$element['type'] = 'date';
						$d_value = !empty($search_value) ? $search_value : '';
						$d_value_to = !empty($search_value_to) ? $search_value_to : '';
						$d_value_from = !empty($search_value_from) ? $search_value_from : '';
					}
					
					switch(@$element['type']){
						case 'from':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) > " . $search_value .') ';
							break;
						case 'fromequal':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) >= " . $search_value .') ';
							break;
						case 'toequal':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) <= " . $search_value .') ';
							break;
						case 'to':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) < " . $search_value .') ';
							break;
						case 'range':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) > " . $search_value_from ." AND CONVERT(TRIM(b$k.value+0), $convert) < " . $search_value_to .') ';
							break;
						case 'rangeequal':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) >= " . $search_value_from ." AND CONVERT(TRIM(b$k.value+0), $convert) <= " . $search_value_to .') ';
							break;
						case 'outofrange':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) < " . $search_value_from ." AND CONVERT(TRIM(b$k.value+0), $convert) > " . $search_value_to .') ';
							break;
						case 'outofrangeequal':
							// number conversion too
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND CONVERT(TRIM(b$k.value+0), $convert) <= " . $search_value_from ." AND CONVERT(TRIM(b$k.value+0), $convert) >= " . $search_value_to .') ';
							break;
						case 'date':
							$wheres[strtoupper($element['logic'])][] = $this->getDateSearch($identifier, $k, $d_value, $d_value_to, $d_value_from, $search_type);
							break;
						default:
							$wheres[strtoupper($element['logic'])][] = "(b$k.element_id = '" . $identifier . "' AND TRIM(b$k.value) LIKE " . $search_value .') ';		
					}
				}
				$k++;
			}
		}
		
		// At the end, merge ORs
		if( count( $wheres['OR'] ) )
		{
			$query->where('(' . implode(' OR ', $wheres['OR']) . ')');
		}
		
		// and the ANDs
		foreach($wheres['AND'] as $where)
		{
			$query->where($where);
		}
		
		// Add repeatable joins
		$this->addRepeatableJoins($query, $k);
		
	}

	protected function getDateSearch($identifier, $k, $value, $value_to, $value_from, $search_type)
	{
		if (!empty($value)) { // search_type = to:from:default
			$date = substr($value, 1, 10);
			$from = $date.' 00:00:00';
			$to   = $date.' 23:59:59';
		} else { // search_type = range
			$from = substr($value_from, 1, 10).' 00:00:00';
			$to   = substr($value_to, 1, 10).' 23:59:59';
		}
		
		$from = $this->_db->Quote($this->_db->escape($from));
		$to   = $this->_db->Quote($this->_db->escape($to));
		
		switch ($search_type) {
			case 'to':
				$el_where = "(b$k.element_id = '$identifier' AND ((SUBSTRING(b$k.value, 1, 19) <= $to) OR ($to >= SUBSTRING(b$k.value, 1, 19)))) ";
				break;
			case 'from':
				$el_where = "(b$k.element_id = '$identifier' AND ((SUBSTRING(b$k.value, -19) >= $from) OR ($from <= SUBSTRING(b$k.value, -19)))) ";
				break;
			case 'range':
				$el_where = "(b$k.element_id = '$identifier' AND (($from BETWEEN SUBSTRING(b$k.value, 1, 19) AND SUBSTRING(b$k.value, -19)) OR ($to BETWEEN SUBSTRING(b$k.value, 1, 19) AND SUBSTRING(b$k.value, -19)) OR (SUBSTRING(b$k.value, 1, 19) BETWEEN $from AND $to) OR (SUBSTRING(b$k.value, -19) BETWEEN $from AND $to))) ";
				break;
			default:
				$date = $this->_db->escape($date);
				$el_where = "(b$k.element_id = '$identifier' AND ((b$k.value LIKE '%$date%') OR (('$date' BETWEEN SUBSTRING(b$k.value, 1, 10) AND SUBSTRING(b$k.value, -19, 10)) AND b$k.value NOT REGEXP '[[.LF.]]'))) ";
		}
		
		return $el_where;
	}
	
	protected function getMultipleSearch($identifier, $values, $mode, $k, $is_select = true, $logic='AND')
	{
		$el_where = "b$k.element_id = " . $this->_db->Quote($identifier);				
		$el_where .= " $logic ";

		// lets be sure mode is set
		$mode = $mode ? $mode : "AND";
		
		$multiples = array();
		
		// Normal selects / radio / etc (ElementOption)
		if($is_select)
		{
			foreach($values as $value)
			{
				$multiple = "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim($this->_db->escape($value)))." OR ";
				$multiple .= "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim($this->_db->escape($value)."\n%"))." OR ";
				$multiple .= "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim("%\n".$this->_db->escape($value)))." OR ";
				$multiple .= "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim("%\n".$this->_db->escape($value)."\n%"));
				$multiples[] = "(".$multiple.")";
			}
		} 
		// This covers country element too
		else 
		{
			foreach($values as $value)
			{
				$multiple = "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim($this->_db->escape($value)))." OR ";
				$multiple .= "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim($this->_db->escape($value).' %'))." OR ";
				$multiple .= "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim('% '.$this->_db->escape($value)))." OR ";
				$multiple .= "TRIM(b$k.value) LIKE ".$this->_db->Quote(trim('% '.$this->_db->escape($value).' %'));
				$multiples[] = "(".$multiple.")";
			}
		}
		
		$el_where .= "(".implode(" ".$mode. " ", $multiples).")";
		
		return $el_where;
	}

	/**
	 * One Join for each element filter
	 */
	protected function addRepeatableJoins(&$query, $k)
	{
		// 1 join for each parameter
		for ( $i = 0; $i < $k; $i++ ){
			$query->join('LEFT', ZOO_TABLE_SEARCH . " AS b$i ON a.id = b$i.item_id");
		}
	}
	
	/**
	 * Get the value quoted and with %% if needed
	 */
	protected function getQuotedValue($name, $quote = true)
	{
		// || @$name['is_select'] != true  -> this make allways partial searches
		if( $name['type'] == 'partial'){
			$value = '%' . $name['value'] . '%';

		} else {
			$value = $name['value'];	

		} if($quote) {
			return $this->_db->Quote( $value );
		}
		
		return $value;
	}
	
	/**
	 * Apply general item filters (type, app, etc)
	 */
	protected function itemFilters(&$query)
	{
		// Filters
		$app_id	= $this->getState('app_id');
		$type = $this->getState('type');
		
		// Same application
		if ($app_id){
			$query->where('a.application_id = ' . (int)$app_id);
		}

		// Same type
		if ($type){
			$query->where('a.type LIKE ' . $this->_db->Quote($type));
		}
	}

	/**
	 * Apply general filters like searchable, publicated, etc
	 */
	protected function basicFilters(&$query)
	{
		// init vars
		$user = JFactory::getUser();
		$date = JFactory::getDate();
		$now  = $this->_db->Quote($date->toSql());
		$null = $this->_db->Quote($this->_db->getNullDate());

		// Basic filters
		$query->where('a.searchable = 1');
		// Searchable
		$query->where( 'a.' . $this->app->user->getDBAccessString());
		// User accessible
		$query->where('a.state = 1');
		// published

		// Publication up
		$where = array();
		$where[] = 'a.publish_up = ' . $null;
		$where[] = 'a.publish_up <= ' . $now;
		$query->where('(' . implode(' OR ', $where) . ')');

		// Publication down
		$where = array();
		$where[] = 'a.publish_down = ' . $null;
		$where[] = 'a.publish_down >= ' . $now;
		$query->where('(' . implode(' OR ', $where) . ')');
	}

}