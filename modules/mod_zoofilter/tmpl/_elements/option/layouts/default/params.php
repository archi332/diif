<?php
/**
* @package   com_zoo
* @author    ZOOlanders http://www.zoolanders.com
* @copyright Copyright (C) ZOOlanders.com
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');	
	
	return
	'{"fields": {
		"layout_wrapper":{
			"type": "fieldset",
			"fields": {

				"layout_separator":{
					"type":"separator",
					"text":"PLG_ZLFRAMEWORK_DEFAULT_LAYOUT",
					"big":1
				},

				"specific_params": {
					"type": "subfield",
					"path":"modules:mod_zoofilter\/tmpl\/_elements\/option\/specific.json.php",
					"adjust_ctrl":{
						"pattern":'.json_encode('/\[layout\]/').',
						"replacement":"[specific]"
					}
				},
	
				"_trans_values":{
					"type": "checkbox",
					"label": "PLG_ZOOFILTER_TRANSLATE_VALUES",
					"help": "PLG_ZOOFILTER_TRANSLATE_VALUES_DESC",
					"specific": {
						"label": "JYES"
					}
				},

				"_multiple":{
					"type": "checkbox",
					"label": "PLG_ZOOFILTER_MULTIPLE",
					"help": "PLG_ZOOFILTER_MULTIPLE_DESC",
					"default":"0",
					"specific": {
						"label": "JYES"
					},
					"dependents":"wrapper_multiple > 1 | _choosetext !> 1"
				},
				
				"_choosetext":{
					"type": "text",
					"label": "PLG_ZOOFILTER_CHOOSE_TEXT",
					"help":"PLG_ZOOFILTER_CHOOSE_TEXT_DESC",
					"specific":{
						"placeholder":"PLG_ZOOFILTER_CHOOSE"
					}
				},
				
				"wrapper_multiple":{
					"type": "wrapper",
					"fields": {
						"_mode":{
							"type": "radio",
							"label": "PLG_ZOOFILTER_MODE",
							"help": "PLG_ZOOFILTER_MODE_DESC",
							"default":"AND",
							"specific": {
								"options": {
									"PLG_ZLFRAMEWORK_AND":"AND",
									"PLG_ZLFRAMEWORK_OR":"OR"
								}
							}
						},
						"_fieldsize":{
							"type": "text",
							"label": "PLG_ZOOFILTER_FIELD_SIZE",
							"help": "PLG_ZOOFILTER_FIELD_SIZE_DESC",
							"default": "5"
						}
					}
				}

			}
		}
				
	}}';