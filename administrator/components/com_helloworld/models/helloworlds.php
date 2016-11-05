<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorldList Model
 *
 * @since  0.0.1
 */
class HelloWorldModelHelloWorlds extends JModelList
{
    /**
     * @var
     */
    private $_PDO;

    /**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
			  ->from($db->quoteName('__helloworld'))
//			  ->from($db->quoteName('exp_categories'))
        ->where('id = 1');





//echo date('Y_m_d H:i:s', '1392826753');
//die();







        $result = $this->setItems();
        echo '<pre>';
        print_r($result);
        die;
		return $query;
	}


	protected function getNew()
    {
        $option = array(); //Инициализация

        $option['driver']   = 'mysqli';
        $option['host']     = 'localhost';
        $option['user']     = 'root';
        $option['password'] = '1111';
        $option['database'] = 'dissif';
        $option['prefix']   = '';

//        $db = & JDatabase::getInstance( $option );
        $db = & JDatabaseDriver::getInstance( $option );
        return $db;
    }









    public function connect()
    {
        $host = 'localhost';
        $db = 'diif';
        $charset = 'UTF8';
        $user = 'root';
        $pass = '1111';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $this->_PDO = new PDO($dsn, $user, $pass);
    }

    function setCategories()
    {
        $this->connect();
        /** @var PDOStatement $stmt */
//        $stmt = $this->_PDO->query('SELECT * FROM `exp_categories` AS cat LEFT JOIN `exp_category_groups` AS cg ON cat.group_id = cg.group_id ');
        $stmt = $this->_PDO->query('SELECT *  FROM `exp_categories`', PDO::FETCH_ASSOC);

        $result = [];
        foreach ($stmt->fetchAll() as $item) {


            $profile = new stdClass();
            $profile->id = $item['cat_id'];
            $profile->application_id = 11;
            $profile->name = $item['cat_name'];
            $profile->alias = $item['cat_url_title'];
            $profile->description = $item['cat_description'];
            $profile->parent =$item['parent_id'];
            $profile->ordering = $item['cat_order'];
            $profile->published = 1;
            $profile->params = ' {
        "content.teaser_description": "",
"content.teaser_image": "images\/yootheme\/zoo\/test\/ic_add_shopping_cart_black_24dp.png",
"content.teaser_image_width": "",
"content.teaser_image_height": "",
"content.image": "",
"content.image_width": "",
"content.image_height": "",
"metadata.title": "",
"metadata.description": "",
"metadata.keywords": "",
"metadata.robots": "",
"metadata.author": "" 
}';
            $result[] = JFactory::getDbo()->insertObject('ds4m1_zoo_category', $profile);

        }
        return $result;
    }

    function setCategory_post()
    {
        $this->connect();
        /** @var PDOStatement $stmt */
//        $stmt = $this->_PDO->query('SELECT * FROM `exp_categories` AS cat LEFT JOIN `exp_category_groups` AS cg ON cat.group_id = cg.group_id ');
        $stmt = $this->_PDO->query('SELECT *  FROM `exp_category_posts`', PDO::FETCH_ASSOC);

        $result = [];
        foreach ($stmt->fetchAll() as $item) {

            $profile = new stdClass();
            $profile->category_id = $item['cat_id'];
            $profile->item_id = $item['entry_id'];

            $result[] = JFactory::getDbo()->insertObject('ds4m1_zoo_category_item', $profile);
        }
        return $result;
    }

    function setItems()
    {
        $this->connect();
        /** @var PDOStatement $stmt */
//        $stmt = $this->_PDO->query('SELECT * FROM `exp_categories` AS cat LEFT JOIN `exp_category_groups` AS cg ON cat.group_id = cg.group_id ');
//        $stmt = $this->_PDO->query('SELECT * FROM `exp_channel_data` AS data
//  LEFT JOIN exp_channel_titles AS titles ON data.entry_id=titles.entry_id
//  LEFT JOIN exp_channel_images AS images ON data.entry_id=images.entry_id
//  LEFT JOIN exp_mx_google_map AS map ON data.entry_id=map.entry_id
//WHERE data.entry_id <> 18731 OR data.entry_id <> 17921 LIMIT 0,1 /*10523*/'/*, PDO::FETCH_NUM*/);
////  WHERE data.entry_id = 18815 /*10523*/'/*, PDO::FETCH_ASSOC*/);
        $stmt = $this->_PDO->query('SELECT DISTINCT data.entry_id AS id, data.field_id_3 AS zip, data.field_id_6 AS phones, data.field_id_8 AS email, 
data.field_id_10, titles.title, titles.entry_date,
  data.field_id_20 AS description, data.field_id_4 as street, data.field_id_5 as house, data.field_id_7 as fax, 
  data.field_id_9 as site, data.field_id_16 as city,

  titles.author_id AS created_by,  concat(titles.year,\'_\',titles.month,\'_\',titles.day) AS publish_up, titles.expiration_date as publish_down,
  titles.edit_date, images.filename

FROM `exp_channel_data` AS data
  LEFT JOIN exp_channel_titles AS titles ON data.entry_id=titles.entry_id
  LEFT JOIN exp_channel_images AS images ON data.entry_id=images.entry_id
  LEFT JOIN exp_mx_google_map AS map ON data.entry_id=map.entry_id 
  
where data.entry_id = 9381 
  ORDER BY data.entry_id

  /*LIMIT 3000, 20000*/
  
  
  /*
  where data.entry_id <> 18731 
  WHERE data.entry_id <> 7675
  LIMIT 1900, 3000*/
  
  /*10523*/', PDO::FETCH_ASSOC);





        $result = [];
        foreach ($stmt->fetchAll() as $item) {
$result[] = $item;


            $profile = new stdClass();
            $profile->id = $item['id'];
            $profile->application_id = 11;
            $profile->type = 'company';
            $profile->name = $item['title'];
            $profile->alias = $item['id'];
            $profile->created = $item['entry_date'];
            $profile->modified = $item['edit_date'];
            $profile->modified_by = '';
            $profile->publish_up = $item['publish_up'];
            $profile->publish_down = $item['publish_down'];
            $profile->priority = 0;
            $profile->hits = 0;
            $profile->state = 1;
            $profile->access = 1;
            $profile->created_by = $item['created_by'];
            $profile->created_by_alias = '';
            $profile->searchable = 1;
            $profile->elements = ' {
	"ed9cdd4c-ae8b-4ecb-bca7-e12a5153bc02":  {
		"0":  {
			"value": ""
		}
	},
	"a77f06fc-1561-453c-a429-8dd05cdc29f5":  {
		"0":  {
			"value": "'. $item['description'] . '"
		}
	},
	"1a85a7a6-2aba-4480-925b-6b97d311ee6c":  {
		"0":  {
			"value": "/*'. $item['description'] . '*/"
		}
	},
	"ffcc1c50-8dbd-4115-b463-b43bdcd44a57":  {
		"file": "images\/yootheme\/zoo\/test\/logos\/' . $item['filename'] . '",
		"title": "",
		"link": "",
		"target": "0",
		"rel": "",
		"lightbox_image": "",
		"spotlight_effect": "",
		"caption": "",
		"width": 24,
		"height": 24
	},
	"4339a108-f907-4661-9aab-d6f3f00e736e":  {
		"0":  {
			"value": "\u0412\u0443\u043b\u0438\u0446\u044f"
		}
	},
	"ea0666d7-51e3-4e52-8617-25e3ad61f8b8":  {
		"0":  {
			"value": "'. $item['zip'] .'"
		}
	},
	"90a18889-884b-4d53-a302-4e6e4595efa0":  {
		"0":  {
			"value": "\u041c\u0456\u0441\u0442\u043e"
		}
	},
	"6a20c005-7bd3-4014-919a-6edfd2239284":  {
		"0":  {
			"value": "'. $item['city'] .'"
		}
	},
	"81c5f642-2f7f-491f-b1c2-ce32ec688125":  {
		"country":  {
			"0": "UA"
		}
	},
	"b870164b-fe78-45b0-b840-8ebceb9b9cb6":  {
		"0":  {
			"value": "' . $item['phones'] . '"
		}
	},
	"8a91aab2-7862-4a04-bd28-07f1ff4acce5":  {
		"0":  {
			"value": "'. $item['fax'] . '"
		}
	},
	"3f15b5e4-0dea-4114-a870-1106b85248de":  {
		"0":  {
			"value": "' . $item['email'] . '",
			"text": "",
			"subject": "",
			"body": ""
		}
	},
	"0b3d983e-b2fa-4728-afa0-a0b640fa34dc":  {
		"0":  {
			"value": "'. $item['site'] . '",
			"text": "",
			"target": "0",
			"custom_title": "",
			"rel": ""
		}
	},
	"7056f1d2-5253-40b6-8efd-d289b10a8c69":  {

	},
	"cf6dd846-5774-47aa-8ca7-c1623c06e130":  {
		"votes": 0,
		"value": 0
	},
	"160bd40a-3e0e-48de-b6cd-56cdcc9db892":  {
		"location": "Maps"
	}
}';



            $profile->params = ' {
	"metadata.title": "",
	"metadata.description": "",
	"metadata.keywords": "",
	"metadata.robots": "",
	"metadata.author": "",
	"config.enable_comments": "1",
	"config.primary_category": "1"
}';


            try {
                $result[] = JFactory::getDbo()->insertObject('ds4m1_zoo_item', $profile);
            } catch (Exception $e) {
                $result[] = 'Error : ' . $e;
            }
        }
        return $result;
    }

}
