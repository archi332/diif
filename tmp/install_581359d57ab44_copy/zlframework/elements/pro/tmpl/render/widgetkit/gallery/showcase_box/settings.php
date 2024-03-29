<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();

// load config
require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

	return 
	'{"fields": {

		"thumb_width":{
			"type": "text",
			"label": "Thumbnail Width",
			"default": "80"
		},
		"thumb_height":{
			"type": "text",
			"label": "Thumbnail Height",
			"default": "60"
		},
		"autoplay":{
			"type": "radio",
			"label": "Autoplay",
			"default": "1"
		},
		"interval":{
			"type": "text",
			"label": "Autoplay Interval (ms)",
			"default": "5000"
		},
		"animated":{
			"type": "select",
			"label": "Effect",
			"default": "randomSimple",
			"specific":{
				"options":{
					"Top":"top",
					"Bottom":"bottom",
					"Left":"left",
					"Right":"right",
					"Fade":"fade",
					"ScrollLeft":"scrollLeft",
					"ScrollRight":"scrollRight",
					"Scroll":"scroll",
					"SliceUp":"sliceUp",
					"SliceDown":"sliceDown",
					"SliceUpDown":"sliceUpDown",
					"Swipe":"swipe",
					"Fold":"fold",
					"Puzzle":"puzzle",
					"Boxes":"boxes",
					"BoxesRtl":"boxesRtl",
					"KenBurns":"kenburns",
					"RandomSimple":"randomSimple",
					"RandomFx":"randomFx"
				}
			}
		},
		"duration":{
			"type": "text",
			"label": "Effect Duration (ms)",
			"default": "500"
		},
		"index":{
			"type": "text",
			"label": "Start Index",
			"default": "0"
		},
		"buttons":{
			"type": "radio",
			"label": "Buttons",
			"default": "1",
			"specific":{
				"options":{
					"show":"1",
					"hide":"0"
				}
			}
		},
		"slices":{
			"type": "text",
			"label": "Slices",
			"default": "20"
		},
		"zl_captions":{
			"type": "select",
			"label": "Captions",
			"default": "1",
			"specific":{
				"options":{
					"PLG_ZLFRAMEWORK_DISABLED":"0",
					"PLG_ZLFRAMEWORK_DEFAULT":"1",
					"PLG_ZLFRAMEWORK_CUSTOM":"2"
				}
			},
			"dependents":"_custom_caption > 2 | caption_animation_duration !> 0"
		},
		"_custom_caption":{
			"label":"Custom Caption",
			"type":"text"
		},
		"caption_animation_duration":{
			"type": "text",
			"label": "Caption Animation Duration",
			"default": "500"
		},
		"_sep_slideset":{
			"type": "separator",
			"text": "Slideset"
		},
		"slideset_buttons":{
			"type": "radio",
			"label": "Slideset Buttons",
			"default": "1",
			"specific":{
				"options":{
					"Show":"1",
					"hide":"0"
				}
			}
		},
		"items_per_set":{
			"type": "text",
			"label": "Items Per Set",
			"default": "3"
		},
		"effect":{
			"type": "select",
			"label": "Slideset Effect",
			"default": "slide",
			"specific":{
				"options":{
					"Slide":"slide",
					"Zoom":"zoom",
					"Deck":"deck"
				}
			}
		},
		"slideset_effect_duration":{
			"type": "text",
			"label": "Slideset Effect Duration (ms)",
			"default": "300"
		}
	}}';