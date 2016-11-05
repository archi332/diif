<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

	// JSON
	return 
	'"date": {
		"type":"subfield",
		"path":"zlfield:json/itemfilter/_date.json.php"
	},
	"is_date":{
		"type":"hidden",
		"specific":{
			"value":"1"
		}
	}';

?>