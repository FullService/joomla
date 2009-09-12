<?php
/**
 * @version		$Id$
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.path');

/**
 * Filesystem configuration model for the Joomla Core Installer.
 *
 * @package		Joomla.Installation
 * @since		1.6
 */
class JInstallationModelFilesystem extends JModel
{
	/**
	 * Find the ftp filesystem root for a given user/pass pair.
	 *
	 * @param	array	Configuration options.
	 * @return	mixed	Filesystem root for given FTP user, or boolean false if not found.
	 * @since	1.6
	 */
	function detectFtpRoot($options)
	{
		// Get the options as a JObject for easier handling.
		$options = JArrayHelper::toObject($options, 'JObject');

		jimport('joomla.client.ftp');

		// Connect and login to the FTP server.
		// Use binary transfer mode to be able to compare files.
		@$ftp = & JFTP::getInstance($options->get('ftpHost'), $options->get('ftpPort'), array('type' => FTP_BINARY));

		// Check to make sure FTP is connected and authenticated.
		if (!$ftp->isConnected()) {
			$this->setError(JText::_('NOCONNECT'));
			return false;
		}
		if (!$ftp->login($options->get('ftpUser'), $options->get('ftpPassword'))) {
			$this->setError(JText::_('NOLOGIN'));
			return false;
		}

		// Get the current working directory from the FTP server.
		$cwd = $ftp->pwd();
		if ($cwd === false) {
			$this->setError(JText::_('NOPWD'));
			return false;
		}
		$cwd = rtrim($cwd, '/');

		// Get a list of folders in the current working directory.
		$cwdFolders = $ftp->listDetails(null, 'folders');
		if ($cwdFolders === false || count($cwdFolders) == 0) {
			$this->setError(JText::_('NODIRECTORYLISTING'));
			return false;
		}

		// Get just the folder names from the list of folder data.
		for($i = 0, $n = count($cwdFolders); $i < $n; $i++)
		{
			$cwdFolders[$i] = $cwdFolders[$i]['name'];
		}

		// Check to see if Joomla is installed at the FTP current working directory.
		$paths = array();
		$known = array('administrator', 'components', 'installation', 'language', 'libraries', 'plugins');
		if (count(array_diff($known, $cwdFolders)) == 0) {
			$paths[] = $cwd.'/';
		}

		// Search through the segments of JPATH_SITE looking for root possibilities.
		$parts = explode(DS, JPATH_SITE);
		$tmp = '';
		for($i = count($parts) - 1; $i >= 0; $i--)
		{
			$tmp = '/'.$parts[$i].$tmp;
			if (in_array($parts[$i], $cwdFolders)) {
				$paths[] = $cwd.$tmp;
			}
		}

		// Check all possible paths for the real Joomla installation by comparing version files.
		$rootPath = false;
		$checkValue = file_get_contents(JPATH_LIBRARIES.DS.'joomla'.DS.'version.php');
		foreach($paths as $tmp)
		{
			$filePath = rtrim($tmp, '/').'/libraries/joomla/version.php';
			$buffer = null;
			@ $ftp->read($filePath, $buffer);
			if ($buffer == $checkValue) {
				$rootPath = $tmp;
				break;
			}
		}

		// Close the FTP connection.
		$ftp->quit();

		// Return an error if no root path was found.
		if ($rootPath === false) {
			$this->setError(JText::_('Unable to autodetect the FTP root folder.'));
			return false;
		}

		return $rootPath;
	}

	/**
	 * Verify the FTP settings as being functional and correct.
	 *
	 * @param	array	Configuration options.
	 * @return	mixed	Filesystem root for given FTP user, or boolean false if not found.
	 * @since	1.6
	 */
	function verifyFtpSettings($options)
	{
		// Get the options as a JObject for easier handling.
		$options = JArrayHelper::toObject($options, 'JObject');

		jimport('joomla.client.ftp');

		// Connect and login to the FTP server.
		@$ftp = & JFTP::getInstance($options->get('ftpHost'), $options->get('ftpPort'));

		// Check to make sure FTP is connected and authenticated.
		if (!$ftp->isConnected()) {
			$this->setError(JText::_('NOCONNECT'));
			return false;
		}
		if (!$ftp->login($options->get('ftpUser'), $options->get('ftpPassword'))) {
			$ftp->quit();
			$this->setError(JText::_('NOLOGIN'));
			return false;
		}

		// Since the root path will be trimmed when it gets saved to configuration.php,
		// we want to test with the same value as well.
		$root = rtrim($options->get('ftpRoot'), '/');

		// Verify PWD function
		if ($ftp->pwd() === false) {
			$ftp->quit();
			$this->setError(JText::_('NOPWD'));
			return false;
		}

		// Verify root path exists
		if (!$ftp->chdir($root)) {
			$ftp->quit();
			$this->setError(JText::_('NOROOT'));
			return false;
		}

		// Verify NLST function
		if (($rootList = $ftp->listNames()) === false) {
			$ftp->quit();
			$this->setError(JText::_('NONLST'));
			return false;
		}

		// Verify LIST function
		if ($ftp->listDetails() === false) {
			$ftp->quit();
			$this->setError(JText::_('NOLIST'));
			return false;
		}

		// Verify SYST function
		if ($ftp->syst() === false) {
			$ftp->quit();
			$this->setError(JText::_('NOSYST'));
			return false;
		}

		// Verify valid root path, part one
		$checkList = array('robots.txt', 'index.php');
		if (count(array_diff($checkList, $rootList))) {
			$ftp->quit();
			$this->setError(JText::_('INVALIDROOT'));
			return false;
		}

		// Verify RETR function
		$buffer = null;
		if ($ftp->read($root.'/libraries/joomla/version.php', $buffer) === false) {
			$ftp->quit();
			$this->setError(JText::_('NORETR'));
			return false;
		}

		// Verify valid root path, part two
		$checkValue = file_get_contents(JPATH_LIBRARIES.DS.'joomla'.DS.'version.php');
		if ($buffer !== $checkValue) {
			$ftp->quit();
			$this->setError(JText::_('INVALIDROOT'));
			return false;
		}

		// Verify STOR function
		if ($ftp->create($root.'/ftp_testfile') === false) {
			$ftp->quit();
			$this->setError(JText::_('NOSTOR'));
			return false;
		}

		// Verify DELE function
		if ($ftp->delete($root.'/ftp_testfile') === false) {
			$ftp->quit();
			$this->setError(JText::_('NODELE'));
			return false;
		}

		// Verify MKD function
		if ($ftp->mkdir($root.'/ftp_testdir') === false) {
			$ftp->quit();
			$this->setError(JText::_('NOMKD'));
			return false;
		}

		// Verify RMD function
		if ($ftp->delete($root.'/ftp_testdir') === false) {
			$ftp->quit();
			$this->setError(JText::_('NORMD'));
			return false;
		}

		$ftp->quit();
		return true;
	}

	/**
	 * Check the webserver user permissions for writing files/folders
	 *
	 * @static
	 * @return	boolean	True if correct permissions exist
	 * @since	1.5
	 */
	function checkPermissions()
	{
		if (!is_writable(JPATH_ROOT.'/tmp')) {
			return false;
		}
		if (!mkdir(JPATH_ROOT.'/tmp/test', 0755)) {
			return false;
		}
		if (!copy(JPATH_ROOT.'/tmp/index.html', JPATH_ROOT.'tmp/test/index.html')) {
			return false;
		}
		if (!chmod(JPATH_ROOT.'/tmp/test/index.html', 0777)) {
			return false;
		}
		if (!unlink(JPATH_ROOT.'/tmp/test/index.html')) {
			return false;
		}
		if (!rmdir(JPATH_ROOT.'/tmp/test')) {
			return false;
		}

		return true;
	}

	/**
	 * Verify the FTP configuration values are valid
	 *
	 * @static
	 * @param	string	$user	Username of the ftp user to determine root for
	 * @param	string	$pass	Password of the ftp user to determine root for
	 * @return	mixed	Boolean true on success or JError object on fail
	 * @since	1.5
	 */
	function checkSettings($user, $pass, $root, $host = '127.0.0.1', $port = '21')
	{
		jimport('joomla.client.ftp');
		$ftp = & JFTP::getInstance($host, $port);

		// Since the root path will be trimmed when it gets saved to configuration.php, we want to test with the same value as well
		$root = rtrim($root, '/');

		// Verify connection
		if (!$ftp->isConnected()) {
			$this->setError(JText::_('NOCONNECT'));
			return false;
		}

		// Verify username and password
		if (!$ftp->login($user, $pass)) {
			$ftp->quit();
			$this->setError(JText::_('NOLOGIN'));
			return false;
		}

		// Verify PWD function
		if ($ftp->pwd() === false) {
			$ftp->quit();
			$this->setError(JText::_('NOPWD'));
			return false;
		}

		// Verify root path exists
		if (!$ftp->chdir($root)) {
			$ftp->quit();
			$this->setError(JText::_('NOROOT'));
			return false;
		}

		// Verify NLST function
		if (($rootList = $ftp->listNames()) === false) {
			$ftp->quit();
			$this->setError(JText::_('NONLST'));
			return false;
		}

		// Verify LIST function
		if ($ftp->listDetails() === false) {
			$ftp->quit();
			$this->setError(JText::_('NOLIST'));
			return false;
		}

		// Verify SYST function
		if ($ftp->syst() === false) {
			$ftp->quit();
			$this->setError(JText::_('NOSYST'));
			return false;
		}

		// Verify valid root path, part one
		$checkList = array('CHANGELOG.php', 'COPYRIGHT.php', 'index.php', 'INSTALL.php', 'LICENSE.php');
		if (count(array_diff($checkList, $rootList))) {
			$ftp->quit();
			$this->setError(JText::_('INVALIDROOT'));
			return false;
		}

		// Verify RETR function
		$buffer = null;
		if ($ftp->read($root.'/libraries/joomla/version.php', $buffer) === false) {
			$ftp->quit();
			$this->setError(JText::_('NORETR'));
			return false;
		}

		// Verify valid root path, part two
		$checkValue = file_get_contents(JPATH_LIBRARIES.DS.'joomla'.DS.'version.php');
		if ($buffer !== $checkValue) {
			$ftp->quit();
			$this->setError(JText::_('INVALIDROOT'));
			return false;
		}

		// Verify STOR function
		if ($ftp->create($root.'/ftp_testfile') === false) {
			$ftp->quit();
			$this->setError(JText::_('NOSTOR'));
			return false;
		}

		// Verify DELE function
		if ($ftp->delete($root.'/ftp_testfile') === false) {
			$ftp->quit();
			$this->setError(JText::_('NODELE'));
			return false;
		}

		// Verify MKD function
		if ($ftp->mkdir($root.'/ftp_testdir') === false) {
			$ftp->quit();
			$this->setError(JText::_('NOMKD'));
			return false;
		}

		// Verify RMD function
		if ($ftp->delete($root.'/ftp_testdir') === false) {
			$ftp->quit();
			$this->setError(JText::_('NORMD'));
			return false;
		}

		return true;
	}

	/**
	 * Set default folder permissions
	 *
	 * @param string $path The full file path
	 * @param string $buffer The buffer to write
	 * @return boolean True on success
	 * @since 1.5
	 */
	function setFolderPermissions($folder, $options)
	{
		// Get the options as a JObject for easier handling.
		$options = JArrayHelper::toObject($options, 'JObject');

		// Initialize variables.
		$ftpFlag = false;
		$ftpRoot = $options->ftpRoot;

		// Determine if the path is "chmodable".
		if (!JPath::canChmod(JPath::clean(JPATH_SITE.DS.$folder))) {
			$ftpFlag = true;
		}

		// Do NOT use ftp if it is not enabled
		if (empty($options->ftpEnable)) {
			$ftpFlag = false;
		}

		if ($ftpFlag == true)
		{
			// Connect the FTP client
			jimport('joomla.client.ftp');
			$client = & JFTP::getInstance($options['ftpHost'], $options['ftpPort']);
			$client->login($options['ftpUser'], $options['ftpPassword']);

			//Translate path for the FTP account
			$path = JPath::clean($ftpRoot."/".$folder);

			/*
			 * chmod using ftp
			 */
			if (!$client->chmod($path, '0755'))
			{
				$ret = false;
			}

			$client->quit();
			$ret = true;
		} else
		{

			$path = JPath::clean(JPATH_SITE.DS.$folder);

			if (!@ chmod($path, octdec('0755')))
			{
				$ret = false;
			} else
			{
				$ret = true;
			}
		}

		return $ret;
	}

	/**
	 * Inserts ftp variables to mainframe registry
	 * Needed to activate ftp layer for file operations in safe mode
	 *
	 * @param array The post values
	 */
	function setFTPCfg($vars)
	{
		$app	= &JFactory::getApplication();
		$arr = array();
		$arr['ftp_enable'] = $vars['ftpEnable'];
		$arr['ftp_user'] = $vars['ftpUser'];
		$arr['ftp_pass'] = $vars['ftpPassword'];
		$arr['ftp_root'] = $vars['ftpRoot'];
		$arr['ftp_host'] = $vars['ftpHost'];
		$arr['ftp_port'] = $vars['ftpPort'];

		$app->setCfg($arr, 'config');
	}

	function _chmod($path, $mode)
	{
		$app	= &JFactory::getApplication();
		$ret = false;

		// Initialize variables
		$ftpFlag = true;
		$ftpRoot = $app->getCfg('ftp_root');

		// Do NOT use ftp if it is not enabled
		if ($app->getCfg('ftp_enable') != 1)
		{
			$ftpFlag = false;
		}

		if ($ftpFlag == true)
		{
			// Connect the FTP client
			jimport('joomla.client.ftp');
			$ftp = & JFTP::getInstance($app->getCfg('ftp_host'), $app->getCfg('ftp_port'));
			$ftp->login($app->getCfg('ftp_user'), $app->getCfg('ftp_pass'));

			//Translate the destination path for the FTP account
			$path = JPath::clean(str_replace(JPATH_SITE, $ftpRoot, $path), '/');

			// do the ftp chmod
			if (!$ftp->chmod($path, $mode))
			{
				// FTP connector throws an error
				return false;
			}
			$ftp->quit();
			$ret = true;
		} else
		{
			$ret = @ chmod($path, $mode);
		}

		return $ret;
	}
}