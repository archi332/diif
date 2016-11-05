<?php
/**
* @package		ZOOfilter
* @author    	ZOOlanders http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class mod_zoofilterInstallerScript
{
	protected $_error;
	protected $_src;
	protected $_target;
	protected $_ext = 'mod_zoofilter';
	protected $_ext_name = 'Module ZOOfilter';
	protected $_lng_prefix = 'PLG_ZOOFILTER_SYS';

	/* List of obsolete files and folders */
	protected $_obsolete = array(
		'files'	=> array(
			'modules/mod_zoofilter/tmpl/_elements/category/specific_params.php',
			'modules/mod_zoofilter/tmpl/_elements/option/specific_params.php'
		),
		'folders' => array(
		)
	);

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
		$this->_target = JPATH_ROOT.'/modules/mod_zoofilter'; // install folder

		// load ZLFW language file
		JFactory::getLanguage()->load('plg_system_zlframework.sys', JPATH_ADMINISTRATOR, 'en-GB', true);

		/*
		 * when updating we don't wont to override renderer/item folder,
		 * so let's delete the temp folder before install only if it already exists
		 */
		if ($type == 'update' && JFolder::exists($this->_target.'/renderer/item')) {
			JFolder::delete($this->_src.'/renderer/item');
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
		$release = $parent->get( "manifest" )->version;

		if(strtolower($type) == 'install'){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_INSTALL', $this->_ext_name, $release);
		}

		if(strtolower($type) == 'update'){
			echo JText::sprintf('PLG_ZLFRAMEWORK_SYS_UPDATE', $this->_ext_name, $release);
		}

		// remove obsolete
		$this->removeObsolete();
	}

	/**
	 * Removes obsolete files and folders
	 * @version 1.1
	 */
	private function removeObsolete()
	{
		// Remove files
		if(!empty($this->_obsolete['files'])) foreach($this->_obsolete['files'] as $file) {
			$f = JPATH_ROOT.'/'.$file;
			if(!JFile::exists($f)) continue;
			JFile::delete($f);
		}

		// Remove folders
		if(!empty($this->_obsolete['folders'])) foreach($this->_obsolete['folders'] as $folder) {
			$f = JPATH_ROOT.'/'.$folder;
			if(!JFolder::exists($f)) continue;
			JFolder::delete($f);
		}
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