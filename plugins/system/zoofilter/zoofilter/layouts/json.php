<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$css_class = $this->application->getGroup( ) . '-' . $this->template->name;
$layout = $this->item_layout;
$total = $this->total;

$json = array();

// render items
if ( count( $this->items ) )
{
	foreach($this->items as $item)
	{
		$item->html = $this->partial('item', compact('item', 'layout'));
		$item->url = $this->app->route->item($item);
		$json[] = json_encode($item);  
	}
}

echo json_encode($json);
