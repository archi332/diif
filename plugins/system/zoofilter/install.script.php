<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class plgSystemZoofilterInstallerScript
{
	protected $_error;
	protected $_src;
	protected $_target;
	protected $_ext = 'zoofilter';
	protected $_ext_name = 'ZOOfilter';
	protected $_lng_prefix = 'PLG_ZOOFILTER_SYS';

	/**
	 * Called before any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install)
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, $parent)
	{
		// init vars
		$db = JFactory::getDBO();
		$this->_src = $parent->getParent()->getPath('source'); // tmp folder
		$this->_target = JPATH_ROOT.'/plugins/system/zoofilter'; // install folder

		// load ZLFW sys language file
		JFactory::getLanguage()->load('plg_system_zlframework.sys', JPATH_ADMINISTRATOR, 'en-GB', true);

		/*
		 * when updating we don't wont to override renderer/item folder,
		 * so let's delete the temp folder before install only if it already exists
		 */
		if($type == 'update'){
			JFolder::exists($this->_target.'/zoofilter/renderer/item') && 
			JFolder::delete($this->_src.'/zoofilter/renderer/item');

			JFolder::exists($this->_target.'/zoofilter/ordering/renderer/item') && 
			JFolder::delete($this->_src.'/zoofilter/ordering/renderer/item');
		}
	}

	/**
	 * Called on installation
	 *
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install($parent)
	{
		// init vars
		$db = JFactory::getDBO();

        // enable plugin
        $db->setQuery("UPDATE `#__extensions` SET `enabled` = 1 WHERE `type` = 'plugin' AND `element` = '{$this->_ext}' AND `folder` = 'system'");
        $db->query();
    }

    /**
	 * Called on uninstallation
	 *
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function uninstall($parent)
	{
		// init vars
		$db = JFactory::getDBO();
		
        // disable all zoofilter modules
        $db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `element` LIKE '%zoofilter%'")->query();

        // drop table
        $db->setQuery('DROP TABLE IF EXISTS `#__zoo_zoofilter_searches`')->query();

        // enqueue Message
        JFactory::getApplication()->enqueueMessage(JText::_($this->langString('_UNINSTALL')));
    }

	/**
	 * Called after install
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install)
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, $parent)
	{
		// init vars
		$db = JFactory::getDBO();
		$release = $parent->get( "manifest" )->version;

		if($type == 'install'){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_INSTALL', $this->_ext_name, $release);
		}

		if($type == 'update'){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_UPDATE', $this->_ext_name, $release);
		}

		// create table
		$db->setQuery('CREATE TABLE IF NOT EXISTS `#__zoo_zoofilter_searches` ('
			.'`search_id` int(11) NOT NULL AUTO_INCREMENT,'
			.'`search_uuid` varchar(32) NOT NULL,'
			.'`search_params` text NOT NULL,'
			.'`user_id` int(11) NOT NULL,'
			.'`datetime` datetime NOT NULL,'
			.'`hits` int(11) NOT NULL,'
			.'PRIMARY KEY (`search_id`),'
			.'UNIQUE KEY `search_uuid` (`search_uuid`)'
			.') ENGINE=MyISAM DEFAULT CHARSET=utf8;');
		$db->query();
	}

	/**
	 * creates the lang string
	 * @version 1.0
	 *
	 * @return  string
	 */
	protected function langString($string)
	{
		return $this->_lng_prefix.$string;
	}
}