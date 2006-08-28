<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Templates
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

/**
 * @package Joomla
 * @subpackage Templates
 */
class JTemplatesController
{
	/**
	* Compiles a list of installed, version 4.5+ templates
	*
	* Based on xml files found.  If no xml file found the template
	* is ignored
	*/
	function viewTemplates()
	{
		global $mainframe;

		// Initialize some variables
		$db		=& JFactory::getDBO();
		$option = JRequest::getVar('option');
		$client	= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

		// Initialize the pagination variables
		$limit		= $mainframe->getUserStateFromRequest('limit', 'limit', $mainframe->getCfg('list_limit'));
		$limitstart	= $mainframe->getUserStateFromRequest("$option.limitstart", 'limitstart', 0);

		$select[] 			= mosHTML::makeOption('0', JText::_('Site'));
		$select[] 			= mosHTML::makeOption('1', JText::_('Administrator'));
		$lists['client'] 	= mosHTML::selectList($select, 'client', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $client->id);

		$tBaseDir = $client->path.DS.'templates';

		//get template xml file info
		$rows = array();
		$rows = JTemplatesHelper::parseXMLTemplateFiles($tBaseDir);

		// set dynamic template information
		for($i = 0; $i < count($rows); $i++)  {
			$rows[$i]->assigned  = JTemplatesHelper::isTemplateAssigned($rows[$i]->directory);
			$rows[$i]->published = JTemplatesHelper::isTemplateDefault($rows[$i]->directory, $client->id);
		}

		jimport('joomla.presentation.pagination');
		$page = new JPagination(count($rows), $limitstart, $limit);

		$rows = array_slice($rows, $page->limitstart, $page->limit);

		JTemplatesView::showTemplates($rows, $lists, $page, $option, $client);
	}

	/**
	* Show the template with module position in an iframe
	*/
	function previewTemplate()
	{
		$template	= JRequest::getVar('id', '', 'method', 'word');
		$option 	= JRequest::getVar('option');
		$client		= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

		if (!$template)
		{
			return JError::raiseWarning( 500, 'Template not specified' );
		}

		JTemplatesView::previewTemplate($template, true, $client, $option);
	}

	/**
	* Publish, or make current, the selected template
	*/
	function publishTemplate()
	{
		global $mainframe;

		// Initialize some variables
		$db		= & JFactory::getDBO();
		$cid	= JRequest::getVar('cid', array(), 'method', 'array');
		$option	= JRequest::getVar('option');
		$client	= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

		if (count( $cid ))
		{
			$template	= preg_replace( '#\W#', '', $cid[0] );

			$query = "DELETE FROM #__templates_menu" .
					"\n WHERE client_id = $client->id" .
					"\n AND menuid = 0";
			$db->setQuery($query);
			$db->query();
	
			$query = "INSERT INTO #__templates_menu" .
					"\n SET client_id = $client->id, template = ".$db->Quote( $template ).", menuid = 0";
			$db->setQuery($query);
			$db->query();
		}

		$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id);
	}

	function editTemplate()
	{
		jimport('joomla.filesystem.path');

		// Initialize some variables
		$db	    	= & JFactory::getDBO();
		$cid		= JRequest::getVar('cid', array(), 'method', 'array');
		$option		= JRequest::getVar('option');
		$client		= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

		if (count( $cid ) == 0)
		{
			return JError::raiseWarning( 500, 'Template not specified' );
		}

		$tBaseDir	= JPath::clean($client->path.DS.'templates');
		$template	= preg_replace( '#\W#', '', $cid[0] );

		if (!is_dir( $tBaseDir . DS . $template ))
		{
			//return JError::raiseError( 500, 'Please specify a template' );
			return JError::raiseWarning( 500, 'Template not found' );
		}

		$ini		= $client->path.DS.'templates'.DS.$template.DS.'params.ini';
		$xml		= $client->path.DS.'templates'.DS.$template.DS.'templateDetails.xml';

		$row = JTemplatesHelper::parseXMLTemplateFile($tBaseDir, $template);
		$row->published = JTemplatesHelper::isTemplateDefault($row->directory, $client->id);

		jimport('joomla.filesystem.file');
		// Read the ini file
		if (JFile::exists($ini)) {
			$content = JFile::read($ini);
		} else {
			$content = null;
		}

		$params = new JParameter($content, $xml, 'template');

		$lists['published']  = mosHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published);

		$lists['selections'] = '';
		if($client->id == '1')  {
			$lists['selections'] =  JText::_("Can't assign an administrator template");
		} else  {
			if(JTemplatesHelper::isTemplateDefault($row->directory, $client->id)) {
				$lists['selections'] =  JText::_("Can't assign a default template");
			} else {
				$lists['selections'] = JTemplatesHelper::createMenuList($template);
			}
		}

		JTemplatesView::editTemplate($row, $lists, $params, $option, $client);
	}

	function saveTemplate()
	{
		global $mainframe;

		// Initialize some variables
		$db	   		 = & JFactory::getDBO();

		$template	= JRequest::getVar('id', '', 'method', 'word');
		$option		= JRequest::getVar('option', '', '', 'word');
		$client		= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$menus		= JRequest::getVar('selections', array (), 'post', 'array');
		$params		= JRequest::getVar('params', array (), '', 'array');

		if (!$template) {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}

		$file = $client->path.DS.'templates'.DS.$template.DS.'params.ini';

		jimport('joomla.filesystem.file');
		if (JFile::exists($file) && is_array($params))
		{
			$txt = null;
			foreach ($params as $k => $v) {
				$txt .= "$k=$v\n";
			}

			if (!JFile::write($file, $txt)) {
				$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('Failed to open file for writing.'));
			}
		}

		$query = "DELETE FROM #__templates_menu" .
				"\n WHERE client_id =" .$client->id.
				"\n AND template = " . $db->Quote( $template ) .
				"\n AND menuid <> 0";
		$db->setQuery($query);
		$db->query();

		if (!in_array('', $menus))
		{
			foreach ($menus as $menuid)
			{
				$menuid = (int) $menuid;

				// If 'None' is not in array
				if ($menuid != -999)
				{
					// check if there is already a template assigned to this menu item
					$query = "DELETE FROM #__templates_menu" .
							"\n WHERE client_id = 0" .
							"\n AND menuid = $menuid";
					$db->setQuery($query);
					$db->query();

					$query = "INSERT INTO #__templates_menu" .
							"\n SET client_id = 0, template = ". $db->Quote( $template ) .", menuid = $menuid";
					$db->setQuery($query);
					$db->query();
				}
			}
		}

		$task = JRequest::getVar('task');
		if($task == 'apply') {
			$mainframe->redirect('index2.php?option='.$option.'&task=edit&cid[]='.$template.'&client='.$client->id);
		} else {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id);
		}
	}

	function cancelTemplate()
	{
		global $mainframe;

		// Initialize some variables
		$option	= JRequest::getVar('option');
		$client	= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

		$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id);
	}

	function editTemplateSource()
	{
		global $mainframe;

		// Initialize some variables
		$option		= JRequest::getVar('option');
		$client		= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$template	= JRequest::getVar('id', '', 'method', 'word');
		$file		= $client->path.DS.'templates'.DS.$template.DS.'index.php';

		// Read the source file
		jimport('joomla.filesystem.file');
		$content = JFile::read($file);

		if ($content !== false) {
			$content = htmlspecialchars($content);
			JTemplatesView::editTemplateSource($template, $content, $option, $client);
		} else {
			$msg = sprintf(JText::_('Operation Failed Could not open'), $file);
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, $msg);
		}
	}

	function saveTemplateSource()
	{
		global $mainframe;

		// Initialize some variables
		$option			= JRequest::getVar('option');
		$client			= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$template		= JRequest::getVar('id', '', 'method', 'word');
		$enableWrite	= JRequest::getVar('enable_write', 0, '', 'int');
		$filecontent	= JRequest::getVar('filecontent', '', '', '', _J_ALLOWRAW);

		if (!$template) {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}

		if (!$filecontent) {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('Content empty.'));
		}

		$file = $client->path.DS.'templates'.DS.$template.DS.'index.php';

		jimport('joomla.filesystem.file');
		if (JFile::write($file, $filecontent))
		{
			$task = JRequest::getVar('task');
			switch($task)
			{
				case 'apply_source' :
					$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id.'&task=edit_source&template='.$template);
					break;

				case 'save_source'  :
				default          :
					$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id);
					break;
			}
		}
		else {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('Failed to open file for writing.'));
		}
	}

	function chooseTemplateCSS()
	{
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$option 	= JRequest::getVar('option');
		$template	= JRequest::getVar('id', '', 'method', 'word');
		$client		= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

		if ($client->id == 1)
		{
			// Admin template css dir
			$a_dir = JPATH_ADMINISTRATOR.DS.'templates'.DS.$template.DS.'css';
			// List .css files
			jimport('joomla.filesystem.folder');
			$a_files = JFolder::files($a_dir, $filter = '\.css$', $recurse = false, $fullpath = false);
			$fs_dir = null;
			$fs_files = null;

			JTemplatesView::chooseCSSFiles($template, $a_dir, $a_files, $option, $client);

		}
		else
		{
			// Template css dir
			$f_dir = JPATH_SITE.DS.'templates'.DS.$template.DS.'css';

			// List template .css files
			jimport('joomla.filesystem.folder');
			$f_files = JFolder::files($f_dir, $filter = '\.css$', $recurse = false, $fullpath = false);

			JTemplatesView::chooseCSSFiles($template, $f_dir, $f_files, $option, $client);

		}
	}

	function editTemplateCSS()
	{
		global $mainframe;

		/*
		 * Initialize some variables
		 */
		$option		= JRequest::getVar('option');
		$client		= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$template	= JRequest::getVar('id', '', 'method', 'word');
		$filename	= JRequest::getVar('filename');

		jimport('joomla.filesystem.file');
		$content = JFile::read($client->path.$filename);

		if ($content !== false) {
			$content = htmlspecialchars($content);
			JTemplatesView::editCSSSource($template, $filename, $content, $option, $client);
		}
		else {
			$msg = sprintf(JText::_('Operation Failed Could not open'), $client->path.$filename);
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, $msg);
		}
	}

	function saveTemplateCSS()
	{
		global $mainframe;

		// Initialize some variables
		$option			= JRequest::getVar('option');
		$client			= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$template		= JRequest::getVar('id', '', 'method', 'word');
		$filename		= JRequest::getVar('filename');
		$filecontent	= JRequest::getVar('filecontent', '', '', '', _J_ALLOWRAW);

		if (!$template) {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('No template specified.'));
		}

		if (!$filecontent) {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('Content empty.'));
		}

		jimport('joomla.filesystem.file');
		if (JFile::write($client->path.$filename, $filecontent))
		{
			$task = JRequest::getVar('task');
			switch($task)
			{
				case 'apply_css' :
					$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id.'&task=edit_css&template='.$template.'&filename='.$filename);
					break;

				case 'save_css'  :
				default          :
					$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id);
					break;
			}
		}
		else {
			$mainframe->redirect('index2.php?option='.$option.'&client='.$client->id, JText::_('Operation Failed').': '.JText::_('Failed to open file for writing.'));
		}
	}

	/**
	*/
	function editPositions()
	{
		// Initialize some variables
		$db		= & JFactory::getDBO();
		$option	= JRequest::getVar('option');

		$query = "SELECT *" .
				"\n FROM #__template_positions";
		$db->setQuery($query);
		$positions = $db->loadObjectList();

		JTemplatesView::editPositions($positions, $option);
	}

	/**
	*/
	function savePositions()
	{
		global $mainframe;

		// Initialize some variables
		$db					= & JFactory::getDBO();
		$option				= JRequest::getVar('option');
		$positions			= JRequest::getVar('position', array (), 'post', 'array');
		$descriptions		= JRequest::getVar('description', array (), 'post', 'array');

		$query = "DELETE FROM #__template_positions";
		$db->setQuery($query);
		$db->query();

		foreach ($positions as $id => $position)
		{
			$position = trim($db->getEscaped($position));
			$description = $descriptions[$id];
			if ($position != '')
			{
				$id = intval($id);
				$query = "INSERT INTO #__template_positions" .
						"\n VALUES ( $id, '$position', '$description' )";
				$db->setQuery($query);
				$db->query();
			}
		}
		$mainframe->redirect('index2.php?option='.$option.'&task=positions', JText::_('Positions saved'));
	}
}
?>