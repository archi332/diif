<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class ZFXmlparamsHelper extends AppHelper {

	/**	
	 * This methods adds parameters to any application.xml file
	 * 
	 * @param	Application		the Application object
	 * @param	string			The xml file where the extra application params are stored (absolute path)
	 */
	public static function addApplicationParams( &$app, $file )
	{
		// Zoo App Instance
		$zoo = App::getInstance('zoo');
		
		// Custom XML File
		$xml = simplexml_load_file( $file );
		
		// Appication XML file
		$old_file = $zoo->path->path( $app->getResource() . $app->metaxml_file );
		$old_xml = simplexml_load_file( $old_file );
		
		// App changed?
		$app_file_changed = false;
		
		// Application is right?
		if ( isset( $xml->application) )
		{			
			foreach ( $xml->application as $a )
			{
				// Check the parameter group
				$group = (string) $a->attributes()->group ? (string) $a->attributes()->group : 'all';
				if( $group == 'all' || $group == $app->application_group )
				{
					if ( isset( $a->params ) )
					{
						foreach ( $a->params as $param )
						{
							// Second level grouping	
							$group = (string)$param->attributes()->group ? (string)$param->attributes()->group : '_default';
							$new_params = $zoo->xml->create('params');
							$new_params->addAttribute('group', $group);
							
							if(@$old_xml->params)
							{
								$param_added = false;
								// Merge with already existing param groups
								foreach( $old_xml->params as $ops)
								{
									if( (string)$ops->attributes()->group == $group )
									{
										$param_added = true;
										
										// Check for addPath
										if( ($a->params->attributes()->addpath != '') && !($old_xml->params->attributes()->addpath) )
										{
											$ops->addAttribute('addpath', $a->params->attributes()->addpath);
											$app_file_changed = true;
										}
										
										// Add the parameters for this group
										foreach($param->param as $p)
										{
											// If it doesn't exists already
											if( !count( $ops->xpath("param[@name='".$p->attributes()->name."']" ) )  )
											{
												// Push changes
												$ops->appendChild( $p );
												$app_file_changed = true;
											}
										}
									}
								}
								
								// Create a new param group if necessary
								if( !$param_added )
								{
									$old_xml->appendChild( $param );
									$app_file_changed = true;
								}
							}
						}
					}
				}
			}
		}

		// If any param was added
		if( $app_file_changed )
		{
			// backup the old xml file
			$old_file = $zoo->path->path( $app->getResource() ) . '/' . $app->metaxml_file . '.bak';
			$new_file = $zoo->path->path( $app->getResource() ) . '/' . $app->metaxml_file;
			JFile::copy( $new_file, $old_file );
			
			// Save the new version
			$data = $old_xml->asXML();
			JFile::write( $new_file, $data);
		}	
	}

	/**
	 * This method adds parmeters to the elements on the fly, reading them from an xml file
	 * 
	 * @param	AppXmlElement	The xml config of the element
	 * @param	string			The file where the extra params are stored (absolute path)
	 */
	public static function addElementParams(&$config, $files)
	{
		if(!empty($files))
		{
			$zoo = App::getInstance('zoo');
			
			// save current parameters
			$old_params = $config->getXML('render') ? $config->getXML('render') : array();

			// empty all params
			$xml = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><wrapper><params group="render"></params></wrapper>');
			$config->loadXML($xml);

			// add zoofilter params
			foreach($files as $file){
				$xml = simplexml_load_file($file);
				$config->loadXML($xml);
			}

			// recover integration params
			$params = '';
			for($i=0; $i < count($old_params->param); $i++){
				$name = (string)$old_params->param[$i]->attributes()->name;
				if($name == 'zoolingual' || $name == 'zooaksubs' || $name == 'zooaccess'){
					$params .= $old_params->param[$i]->asXML();
				}
			}

			$new_param = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><wrapper><params group="render">'.$params.'</params></wrapper>');
			$new_param->addAttribute('group', 'render');
			$config->addXML($new_param);
		}
	}
	
	/**
	 * This method gets the extra parmeters names on the fly, reading them from an xml file
	 * 
	 * @param	Element	The element
	 * @param	string			The file where the extra params are stored (absolute path)
	 */
	public static function getElementExtraParams( $element, $file )
	{
		// Zoo app
		$zoo = App::getInstance('zoo');
		
		// get form config
		$el_xml = $element->getPath() . '/' . $element->getElementType() . '.xml';
		
		$config = $zoo->parameterform->create( $el_xml );
		$config->addElementPath( $zoo->path->path('joomla:elements') );
		$config->setValues( $element->getConfig() );
		$config->element = $element; // add reference to element
		
		// Already present parameters
		$old_params = array_reverse( $config->getXml( ) ? $config->getXml( ) : array( ) );
		
		// New Parameters
		$xml = $zoo->system->getXmlParser( 'Simple' );
		$xml_data = JFile::read( $file );
		$xml->loadString( $xml_data );
		
		$return = array();
		
		// Add the parameters to the already existing ones
		if ( isset( $xml->document->params ) )
		{
			foreach ( $xml->document->params as $param )
			{
				foreach( $param->children() as $p )
				{
					$return[] = $p->attributes( 'name' );
				}
			}
		}
		
		return $return;
	}
}