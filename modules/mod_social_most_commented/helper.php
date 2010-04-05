<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_social_most_commented
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

// Add the appropriate include paths for models.
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_comments'.DS.'models');

class modCommentsMostCommentedHelper
{
	function getList($params)
	{
		// get the comments rating model
		$model = &modCommentsMostCommentedHelper::getModel($params);

		// verify its a model

		$list = &$model->getItems();
		return $list;
	}

	function &getModel($params)
	{
		static $model;

		if (empty($model))
		{
			// get a comments comment model instance and set the context in the state
			$model = &JModel::getInstance('Threads', 'CommentsModel');
			$model->getState();

			// prime the list parameters.
			$model->setState('filter.context', $params->get('context'));
			$model->setState('list.ordering', 'comment_count DESC');
			$model->setState('list.limit', $params->get('limit', 5));
		}

		return $model;
	}
}
