<?php
/**
 * @package   ZOO Component
 * @file      zooapplication.php
 * @version   2.0.1 May 2010
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// load config
require_once ( JPATH_ADMINISTRATOR . '/components/com_zoo/config.php' );

class JElementZooElement extends JElement
{
	
	var $_name = 'ZooElement';
	
	function fetchElement( $name, $value, &$node, $control_name )
	{
		// Base name of the HTML control.
		$ctrl = $control_name . '[' . $name . '][]';
		// init vars
		$params = $this->_parent;
		
		// Get the ZOO App instance
		$zoo = App::getInstance( 'zoo' );
		JHTML::script( 'zooelement.js', '/modules/mod_zoosearch/elements/' );
		
		// Type
		$type = $params->get( 'type', 'product' );
		// Application
		$app = $zoo->table->application->get( $params->get( 'application', '1' ) );
		
		if ( $app )
		{
			// Application Type
			$type = $app->getType( $type );
			
			if ( $type )
			{
				// Elements
				$elements = $type->getElements( );
			}
			else
			{
				$elements = array( );
			}
			
			$list = "";
			$operators = '<table border="0" width="100%">';
			
			if ( count( $elements ) )
			{
				$opts = array( );
				
				foreach ( $elements as $element )
				{
					$element->loadAssets( );
					
					// Exclude the elements without search data
					if ( $this->hasSearchData( get_class( $element ) ) )
					{
						// set label
						$name = $element->getConfig( )->get( 'name' );
						$opts[] = JHTML::_( 'select.option', $element->identifier, $name );
						
						// Add the operators options
						$op = $params->get( 'elements_operators_' . $element->identifier, array( ) );
						$ops = array( );
						foreach ( $op as $o )
						{
							$ops[$o] = 'selected="selected"';
						}
						
						$operators .= '<tr><td><span style="font-weight:bold;">' . $name . '</span></td>';
						$operators .= '<td><select multiple="multiple" name="params[elements_operators_' . $element->identifier . '][]">';
						$operators .= '<option value="equal" ' . @$ops['equal'] . '>=</option>';
						$operators .= '<option value="less" ' . @$ops['less'] . '>&lt;</option>';
						$operators .= '<option value="more" ' . @$ops['more'] . '>&gt;</option>';
						$operators .= '<option value="contains" ' . @$ops['contains'] . '>Contains</option>';
						$operators .= '<option value="exact" ' . @$ops['exact'] . '>Is Exactly</option>';
						$operators .= '</select></td></tr>';
					}
					
				}
				$attribs = ' multiple="multiple"';// " onChange="addOperator(this, \'elementsOperators\');"';
				$list = JHTML::_( 'select.genericlist', $opts, $ctrl, $attribs, 'value', 'text', $value, $control_name . $name );
			}
			
			$operators .= '</table>';
			
			$html = '<div>' . $list . '</div>';
			//$html .= '<div id="elementsOperators">' . $operators . '</div>';
			
			return $html;
		}
	}
	
	/**
	 * Check if getSearchData method is overriden to Element
	 * 
	 * @param string $class the class name
	 */
	protected function hasSearchData( $class, $method = 'getSearchData' )
	{
		$rClass = new ReflectionClass( $class);
		$array = array( );
		
		try
		{
			$rMethod = new ReflectionMethod( $rClass->getName( ), $method);
			
			// attempt to find method in parent class 
			new ReflectionMethod( $rClass->getParentClass( )->getName( ), $method);
			// check whether method is explicitly defined in this class
			if ( $rMethod->getDeclaringClass( )->getName( ) == $rClass->getName( ) )
			{
				return true;
			}
			else
			{
				if ( $rClass->getParentClass( )->getName( ) != 'Element' )
				{
					return $this->hasSearchData( $rClass->getParentClass( )->getName( ), $method );
				}
				else
				{
					return false;
				}
			}
		}
		catch ( exception $e )
		{ /* was not in parent class! */ }
		
		return false;
	}
	
}
