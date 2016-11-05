<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

	return 
	'{

		"control_wrapper": {
			"type": "control_wrapper",
			"adjust_ctrl":{
				"pattern":'.json_encode('/\[layout\]/').',
				"replacement":"[specific]"
			},
			"fields": {

				"_chosenapps":{
					"type": "apps",
					"label": "App",
					"specific": {
						"options": {
							"PLG_ZOOFILTER_CHOOSE_THE_APP":""
						}
					},
					"childs":{
						"loadfields": {									
							"_chosencat":{
								"type": "cats",
								"label": "PLG_ZOOFILTER_CAT_ROOT",
								"specific": {
									"value_map":{
										"apps":"_chosenapps"
									}
								}
							},
							"_default":{
								"type": "cats",
								"label": "PLG_ZOOFILTER_CAT_DEFAULT_SELECTION",
								"specific": {
									"value_map":{
										"apps":"_chosenapps"
									},
									"multi":"true"
								}
							}
						}
					}
				},

				"_max_level":{
					"label":"PLG_ZOOFILTER_CAT_MAXIMUM_LEVEL",
					"help":"PLG_ZOOFILTER_CAT_MAXIMUM_LEVEL_DESC",
					"type":"text"
				},

				"_hide_empty":{
					"label":"PLG_ZOOFILTER_CAT_HIDE_EMPTY",
					"help":"PLG_ZOOFILTER_CAT_HIDE_EMPTY_DESC",
					"type":"checkbox",
					"specific": {
						"label":"JYES"
					}
				}

			}
		}

		'.(!empty($params['total_items']) ? ',
		"_show_number_items":{
			"label":"PLG_ZOOFILTER_CAT_SHOW_NUMBER_ITEMS",
			"help":"PLG_ZOOFILTER_CAT_SHOW_NUMBER_ITEMS_DESC",
			"type":"checkbox",
			"specific": {
				"label":"JYES"
			},
			"dependents":"_name > 1"
		},

		"_name":{
			"type":"text",
			"label":"PLG_ZOOFILTER_CAT_NAME",
			"help":"PLG_ZOOFILTER_CAT_NAME_DESC",
			"specific":{
				"placeholder":"[name] ([total_items])"
			}
		}': '').'

	}';