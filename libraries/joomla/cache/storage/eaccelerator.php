<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 Klas Berlič
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * eAccelerator cache storage handler
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */

class JCacheStorageEaccelerator extends JCacheStorage
{
	/**
	 * Constructor
	 *
	 * @access protected
	 * @param array $options optional parameters
	 */
	function __construct($options = array())
	{
		parent::__construct($options);
	}

	/**
	 * Get cached data by id and group
	 *
	 * @access	public
	 * @param	string	$id			The cache data id
	 * @param	string	$group		The cache data group
	 * @param	boolean	$checkTime	True to verify cache time expiration threshold
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.5
	 */
	function get($id, $group, $checkTime)
	{
		$cache_id = $this->_getCacheId($id, $group);
		$cache_content = eaccelerator_get($cache_id);
		if ($cache_content === null)
		{
			return false;
		}
		return $cache_content;
	}
	
	 /**
	 * Get all cached data
	 *
	 *
	 * @access	public
	 * @return	array data
	 * @since	1.6
	 */
	function getAll()
	{	
		parent::getAll();
		
		$keys = eaccelerator_list_keys();

        $secret = $this->_hash;
        $data = array();		

		foreach ($keys as $key) {
			/* Trim leading ":" to work around list_keys namespace bug in eAcc. This will still work when bug is fixed */
			$name = ltrim($key['name'], ':');
			
			$namearr=explode('-',$name);
			
			if ($namearr !== false && $namearr[0]==$secret &&  $namearr[1]=='cache') {
			
			$group = $namearr[2];
			
			if (!isset($data[$group])) {
			$item = new JCacheStorageHelper();
			} else {
			$item = $data[$group];
			}

			$item->updateSize($key['size']/1024,$group);
			
			$data[$group] = $item;
			
			}
		}
	
					
		return $data;
	}
	
	/**
	 * Store the data to by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	string	$data	The data to store in cache
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	function store($id, $group, $data)
	{
		$cache_id = $this->_getCacheId($id, $group);
		return eaccelerator_put($cache_id, $data, $this->_lifetime);
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
		$cache_id = $this->_getCacheId($id, $group);
		return eaccelerator_rm($cache_id);
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
	function clean($group, $mode)
	{
		$keys = eaccelerator_list_keys();

        $secret = $this->_hash;
        foreach ($keys as $key) {
        /* Trim leading ":" to work around list_keys namespace bug in eAcc. This will still work when bug is fixed */
		$key['name'] = ltrim($key['name'], ':'); 
		
        if (strpos($key['name'], $secret.'-cache-'.$group.'-')===0 xor $mode != 'group')
					eaccelerator_rm($key['name']);
        }
		return true;
	}

	/**
	 * Garbage collect expired cache data
	 *
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	function gc()
	{
		return eaccelerator_gc();
	}

	/**
	 * Test to see if the cache storage is available.
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	static function test()
	{
		return (extension_loaded('eaccelerator') && function_exists('eaccelerator_get'));
	}

	
	/**
	 * Lock cached item
	 *
	 * @abstract
	 * @static
	 * @access public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	integer	$locktime Cached item max lock time
	 * @since	1.6
	 * @return boolean  True on success, false otherwise.
	 */
	public function lock($id,$group,$locktime)
	{			
		$returning = new stdClass();
		$returning->locklooped = false;
				
		$looptime = $locktime * 10;
		
		$cache_id = $this->_getCacheId($id, $group);
		
		$data_lock = eaccelerator_lock($cache_id);
				
		if ( $data_lock === false ) {

			$lock_counter = 0;

			// loop until you find that the lock has been released.  that implies that data get from other thread has finished
			while ( $data_lock === false ) {

				if ( $lock_counter > $looptime ) {
					$returning->locked = false;
					$returning->locklooped = true;
					break;
				}

				usleep(100);
				$data_lock = eaccelerator_lock($cache_id);
				$lock_counter++;
			}
			
		}
		$returning->locked = $data_lock;
		
		return $returning;	
	}
	
	/**
	 * Unlock cached item
	 *
	 * @abstract
	 * @static
	 * @access public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @since	1.6
	 * @return boolean  True on success, false otherwise.
	 */
	public function unlock($id,$group)
	{
		$cache_id = $this->_getCacheId($id, $group);
		return eaccelerator_unlock($cache_id);
	}
	
}
