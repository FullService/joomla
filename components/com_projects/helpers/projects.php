<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class ProjectsHelper
{
	
	/**
	 * Method to determine if a user is member of a project
	 * 
	 * @param $project_id ID of a project
	 * @param $user_id ID of a user
	 *  
	 * @return False in case the user is not a member of the project or ID of user group
	 * @since	1.6
	 */
	public function isMember($project_id=0, $user_id=0)
	{	
		if(!((int)$project_id)){
			return true;
		}
		
		if(!((int)$user_id)){
			return false;
		}
		
		// Check if is member
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(project_id)');
		$query->from('#__project_members AS a');
		$query->where('a.project_id = '.(int) $project_id .' AND a.user_id='.(int)$user_id);
		$db->setQuery($query);
		
		return $db->loadResult();
	}	
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions($portfolio_id=0, $project_id=0, $record=null)
	{	
		$user		= JFactory::getUser();
		$assets		= new JObject;
		$is_member 	= self::isMember($project_id, $user->id);
		$assetName 	= empty($portfolio_id)? 
			'com_projects': 
			'com_projects.category.'.(int)$portfolio_id;
		
		// is owner
		if($record instanceof JObject){
			$assets->set('is.owner', 
				(
					$user->id == $record->get('created_by') ||
					$user->id == $record->get('created_user_id')
				)
			);
		}
				
		// acctions
		$resources 	= array(
			'task',
			'ticket',
			'document'
		);
		$actions	= array(
			'.create',
			'.edit',
			'.delete'
		);
		
		$assets->set('is.member', $is_member);
		foreach ($resources as $resource){
			// Actions
			foreach ($actions as $action){
				$assets->set($resource.$action, 
					($is_member && $user->authorise($action, $assetName))
				);
			}
			
			// View
			$assets->set($resource.'.view', 
				(
					$is_member && 
					$assets->get($resource.'.create') ||
					$assets->get($resource.'.edit') ||
					$assets->get($resource.'.delete')  
				)	
			);
		}
		
		// More Actions
		$actions 	= array(
			'core.create',
			'core.edit',
			'core.edit.state',
			'core.delete'
		);
		foreach ($actions as $action){
			$assets->set($action, $user->authorise($action, $assetName));
		}
		
		return $assets;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $action
	 * @param unknown_type $portfolio_id
	 * @param unknown_type $project_id
	 */
	public static function canDo($action, $portfolio_id=0, $project_id=0, $record=null){
		static $assets;
		if(empty($assets)){
			$assets = self::getActions($portfolio_id, $project_id, $record);
		}
		
		return $assets->get($action, false);
	}
	
	
	/** i don t know if we need this function..
	 * Resets breadcrumb and adds "Projects" link as first
	 * 
	 * @return Reference to breadcrumb object
	 * @since	1.6
	 */
	public function &resetPathway()
	{
		$app = &JFactory::getApplication();
  		$bc = &$app->getPathway();
 		return $bc;
	}

	/**
	 * Method to get ID of actual menu item
	 * 
	 * @return ID of the actual menu item
	 * @since	1.6
	 */
	public function getMenuItemId()
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		
		$id = $menu->getActive()->id;
		if(!((int)$id))
		  $id = JRequest::getInt('Itemid',0);
		return $id;
	}
	
	/**
	 * Method to implode multidimensional array (associative)
	 * 
	 * @param $arr An array with information to implode
	 * @param $glue Glue (default is ',')
	 * 
	 * @return String with glued values
	 * @since	1.6
	 */
	public function implodeMultiDim($arr, $glue = ',')
	{
		$result = '';
		if(is_array($arr))
		{
			$c = count($arr);
			for($i = 0; $i < $c; $i++)
			{
				if(is_array($arr[$i])) // if the actual element is an array => use recursion
					$result .= ProjectsHelper::implodeMultiDim($arr[$i],$glue).$glue;
				else // if the actual element isn't an array => write it down
					$result .= $arr[$i].$glue;
      }
		}
		else // $arr isn't an array
		{
			return $arr; // return the variable
		}
		
		return substr($result, 0, strlen($result)-strlen($glue)); // cut off the last glue
	}
	
	
	/**
	 * Get links
	 * 
	 * Enter description here ...
	 * @param $key
	 * @param $append
	 */
	public static function getLink($key, $append='')
	{
		static $links;
		if(empty($links)){
			 $links = array(
				'form' => 'index.php?option=com_projects',
				'portfolios' => 'index.php?option=com_projects&view=portfolios&id=',
				'projects' => 'index.php?option=com_projects&view=projects&id=',
				'project' => 'index.php?option=com_projects&view=project&id=',
				'members' => 'index.php?option=com_projects&view=members&type=list&id=',
				'assign' => 'index.php?option=com_projects&view=members&type=assign&id=',
				'unassign' => 'index.php?option=com_projects&view=members&type=delete&id='
			);
		}
		return JRoute::_($links[$key].$append);	
	}	
}
?>