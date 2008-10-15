<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Memcache cache storage handler
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */
class JCacheStorageMemcache extends JCacheStorage
{
	/**
	 * Use compression?
	 * @var int
	 */
	protected $_compress = null;

	/**
	 * Use persistent connections
	 * @var boolean
	 */
	protected $_persistent = false;

	protected $_hash = '';

	/**
	 * Constructor
	 *
	 * @access protected
	 * @param array $options optional parameters
	 */
	protectedfunction __construct( $options = array() )
	{
		if (!self::test()) {
			throw new JException('The memcache extension is not available', 0, E_NOTICE);
		}
		parent::__construct($options);

		$params =& JCacheStorageMemcache::getConfig();
		$this->_compress = (isset($params['compression'])) ? $params['compression'] : 0;

		// Get the site hash
		$this->_hash = $params['hash'];
	}

	/**
	 * return memcache connection object
	 *
	 * @static
	 * @access private
	 * @return object memcache connection object
	 */
	protected static function &getConnection() {
		static $db = null;
		if(is_null($db)) {
			$params =& JCacheStorageMemcache::getConfig();
			$persistent	= (isset($params['persistent'])) ? $params['persistent'] : false;
			// This will be an array of loveliness
			$servers	= (isset($params['servers'])) ? $params['servers'] : array();

			// Create the memcache connection
			$db = new Memcache;
			foreach($servers AS $server) {
				$db->addServer($server['host'], $server['port'], $persistent);
			}
		}
		return $db;
	}

	/**
	 * Return memcache related configuration
	 *
	 * @static
	 * @access private
	 * @return array options
	 */
	protected static function &getConfig() {
		static $params = null;
		if(is_null($params)) {
			$config =& JFactory::getConfig();
			$params = $config->getValue('config.memcache_settings');
			if (!is_array($params)) {
				$params = unserialize(stripslashes($params));
			}

			if (!$params) {
				$params = array();
			}
			$params['hash'] = $config->getValue('config.secret');
		}
		return $params;
	}

	/**
	 * Get cached data from memcache by id and group
	 *
	 * @access	public
	 * @param	string	$id			The cache data id
	 * @param	string	$group		The cache data group
	 * @param	boolean	$checkTime	True to verify cache time expiration threshold
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.5
	 */
	public function get($id, $group, $checkTime)
	{
		$db = self::getConnection();
		$cache_id = $this->_getCacheId($id, $group);
		return $db->get($cache_id);
	}

	/**
	 * Store the data to memcache by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	string	$data	The data to store in cache
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	public function store($id, $group, $data)
	{
		$db = self::getConnection();
		$cache_id = $this->_getCacheId($id, $group);
		return $db->set($cache_id, $data, $this->_compress, $this->_lifetime);
	}

	/**
	 * Remove a cached data entry by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	function remove($id, $group)
	{
		$db = self::getConnection();
		$cache_id = $this->_getCacheId($id, $group);
		return $db->delete($cache_id);
	}

	/**
	 * Clean cache for a group given a mode.
	 *
	 * group mode		: cleans all cache in the group
	 * notgroup mode	: cleans all cache not in the group
	 *
	 * @access	public
	 * @param	string	$group	The cache data group
	 * @param	string	$mode	The mode for cleaning cache [group|notgroup]
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	public function clean($group, $mode)
	{
		return true;
	}

	/**
	 * Garbage collect expired cache data
	 *
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	public function gc()
	{
		return true;
	}

	/**
	 * Test to see if the cache storage is available.
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	public static function test()
	{
		return (extension_loaded('memcache') && class_exists('Memcache'));
	}

}

