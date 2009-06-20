<?php
/**
 * @version		$Id$
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
1. Copyright and disclaimer
---------------------------
This application is opensource software released under the GPL.  Please
see source code and the LICENSE file


2. Changelog
------------
This is a non-exhaustive (but still near complete) changelog for
Joomla! 1.5, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.

Legend:

* -> Security Fix
# -> Bug Fix
$ -> Language fix or change
+ -> Addition
^ -> Change
- -> Removed
! -> Note

20-Jun-2009 Andrew Eddie
 ^ Converted admin com_content to MVC
 ^ Converted 'frontpage' to 'featured' articles.
 ^ Integrated trash handling into com_content.
 - Removed com_frontpage and com_trash.
 ^ Converted com_menus to nested sets.
 ^ Redesigned com_menus menu item edit page.
 ^ Integrated trash handling into com_menus.
 ^ Changed article router helper to ContentRoute::article and ContentRoute::category
 ^ Standardise nested set left and right fields as lft and rgt.
 + Added xreference field to jos_categories to enable sync with external data sources (replaces keyref parameter).
 + Added added language field to jos_categories (replaces parameter).
 + Added metadesc, metakey, metadata fields to jos_categories.
 + Added created_user_id, created_timte, modified_user_id, modified_time fields to jos_categories.
 + Added language and hits fields to jos_categories.
 ^ Change jos_session.session_id to VARCHAR(32).
 - Removed form handling methods from JModelItem (now in JModelForm).
 ^ Moved menu helper methods from com_menus to JHtmlMenu.
 + Added JHtmlJGrid (temporary class).
 ^ Removed the need for JSession to use JTable (saves loading that class unnecessarily).
 ^ Add a temporary recursion block in JError::raiseError.

16-Jun-2009 Hannes Papenberg
 # Fixed categories implementation. Changed sample data and fixed bugs in com_newsfeeds

16-Jun-2009 Andrew Eddie
 + Added placeholder folders for language override files.
 ^ JModelForm::getForm now returns false on error.
 + Added magic JUri::__toString method.
 + Added maxlenth attribute to TEXT parameter type.
 # Jhtml::calendar now load script declarations once for each field.
 - Remove fake destructor from JSession.

15-Jun-2009 Ercan Ozkaya
 ^ Refactored com_cache

14-Jun-2009 Hannes Papenberg
 ^ Implemented nested categories
 # Fixed old references to #__templates_menu to work with the new #__menu_template

05-Jun-2009 Ian MacLennan
 + Added layouts from 1.5.11 to milkyway template as overrides

02-Jun-2009 Sam Moffatt
 + Added language override system
 + Added JVersion::getUserAgent
 + Added JFactory::getStream

01-Jun-2009 Louis Landry
 ^ Reworked installation app to use JForm and cleanup of variable names -- first pass.
 ! Installation language strings and JavaScript behaviors need to be reworked still.

01-Jun-2009 Ercan Ozkaya
 - Removed obsolete Mootree version
 - Removed index.js from khepri
 ^ Started refactoring of JTabs
 ^ Merged core-uncompressed.js and core.js

31-Jun-2009 Rob Schley
 ^ Moved LICENSE.php out of the installation folder and renamed to LICENSE.txt.
 ^ Updated the license declarations.
 ^ Updated some copyright declarations that still referred to 2008.

31-May-2009 Ercan Ozkaya
 + Added usergroup filter to debug plugin
 + Added groups property to JUser

31-May-2009 Sam Moffatt
 ^ Added extra functionality to GMail auth plugin (configurable verify peer, user blacklisting and domain control)
 ^ Set the type for all authentication plugins
 ^ Updated loader to handle non-existent files properly
 + Added ability to override default language in configuration.php file
 ^ Updates to improve performance of archiving subsystem
 + Added JStream class
 ^ Minor updates to database system
 - Removed backlink migration from plugin installation SQL

31-May-2009 Ercan Ozkaya
 ^ Merged refactored com_checkin from experimental branch

30-May-2009 Sam Moffatt
 ^ Fix to htaccess to permit different extensions from /component SEF'd links

30-May-2009 Hannes Papenberg
 ^ Changed template manager to better support new template styles

29-May-2009 Hannes Papenberg
 ^ MVCed com_modules in the backend

29-May-2009 Ercan Ozkaya
 + Added CodeMirror editor plugin

29-May-2009 Ercan Ozkaya
 + Added file list section to language meta files
 - Removed legacy methods from JLanguage
 - Removed com_polls language files
 - Removed legacy fields from language meta files (locale, winCodePage, backwardLang, pdfFontName)
 ! Committers, please update language meta files when adding/removing translation files

29-May-2009 Louis Landry
 + Added redirect manager component/plugin.

28-May-2009 Louis Landry
 ^ Replaced the combobox behavior -- still needs a few tweaks.

28-May-2009 Ercan Ozkaya
 # Fixed sample data installation error
 # Fixed com_members reference in mod_quickicon

28-May-2009 Hannes Papenberg
 ^ Updated TinyMCE to version 3.2.4.1
 ^ Implemented menu item specific parameter sets for templates

28-May-2009 Rob Schley
 # Fixed Fatal Error call to undefined method JAdministrator::getSiteURL() in the template manager.
 - Dropped the batch update interface from com_users because it has never worked.
 # Fixed the toggle buttons in the users list of com_users.
 # Fixed the allowed action list associated with a group not being translated in com_users.
 # Fixed the "User Groups Having Access" feature in com_users.

27-May-2009 Rob Schley
 # Fixed the root_user setting getting lost when updating global configuration.
 # Fixed the Switcher JavaScript behavior.
 # Fixed some rendering problems with the submenu in com_config and com_admin.
 # Fixed the default access level setting not being added to the configuration file on install.
 # Fixed problems with the installation app trying to connect to the database becuase of JModel.
 # Fixed version numbers in the installation app.
 # Fixed many more references to gid and removed the gid columns from the users and sessions table.
 # Fixed checks against gid in com_content. Frontend editing should work now!
 # Fixed checks against gid in com_weblinks submit form.
 - Removed the gid field from the user form.
 # Fixed JElement. Changed it back to a concrete class until other areas are changed to not use it as such.
 # Fixed the group based filtering in com_content admin interface.
 # Fixed part of the component config screen. Still not quite correct but at least it saves now.
 # Fixed the recipients drop down in com_messages administrator interface.
 # Fixed the authors drop down in com_content administrator interface.
 ^ Cleaned up JMenuSite.
 ^ Reworked the JAccess::getAuthorisedUsergroups() method.
 # Fixed some global configuration issues.
 + Added a default access setting to global configuration.

27-May-2009 Andrew Eddie
 - Dropped PEAR package and JArchive::create
 ^ Deprecated JHtmlList::accesslevel, use JHtmlList::accessLevels instead
 ^ Corrected JHtmlGrid::access to use new values (still needs work - not scalable)
 - Deprecated passing hidemainmenu through Toolbar buttons (should be done in the view)
 ! HTML package soft-converted to PHP 5
 ^ Refactored backend com_weblinks; added access control field.

26-May-2009 Andrew Eddie
 + Added new ACL schema
 - Removed jos_core_acl tables
 - Removed jos_groups; adjusted joins in queries
 - Upgraded JFactory
 - Removed phpgacl libraries
 - Removed JAuthorization (replaced with JAcl)
 + Added exclusion support for modules (don't show on page)
 - Removed ADODB compatibility methods from JDatabase
 + Added chaining support to JQuery

25-May-2009 Louis Landry
 + Added JSON registry format.
 ^ Changed default registry format to JSON -- dynamically converts from INI.
 _ Removed unnecessary constructors from plugin classes
 - Removed php4 and 5.0 compatability files
 - Removed JTemplate and pattemplate
 + Added JAccess and supporting libraries.
 ^ Updated JTableUser to support new ACL
 + Added JTableAsset, JTableUsergroup
 + Added Joomla namespace to core.js for Joomla native JavaScript methods.
 ! Installation application is under construction.

25-May-2009 Rob Schley
 - Removed DOMIT from the libraries. Deprecated JFactory::getXMLParser('dom');
 + Added the onBeforeRender plugin event. Refactored some of the event handling logic for applications.
 ^ Ported over Anthony Ferrara's changes to the plugin and event system for better performance.
 - Removed XStandard
 ^ Cleaned up the site index and application files.
 ^ Changed JClass references to JObject.
 # Fixed a fatal error in JForm.
 + Added JController::getInstance() to fetch a controller instance.
 + Added JModelList, JModelItem, and JModelForm.
 + Added JQuery.
 + Added com_users.

22-May-2009 Louis Landry
 - Removed PDF support.
 ^ Moved the openid library package into the plugins/authentication directory.

22-May-2009 Rob Schley
 - Removed phputf8 from the libraries.
 ^ Moved the geshi library package into the plugins/content directory.
 - Removed a ton of legacy code.
 - Removed a ton of deprecated code.
 # Fixed an issue with the Media Manager constantly reloading the page with MooTools 1.2.
 + Added JForm libraries package.
 ^ Removed references to behavior.mootools.
 ^ Fixed the admin menu to work with MooTools 1.2.
 ^ Merged in changes to JHtmlBehavior for the JavaScript framework.
 ^ Updated JS from for Mootools 1.2.

13-May-2009 Ian MacLennan
 # [#13898] There is no translated description for component/module/plugin/etc. after installation
 # [#15417] db->updateObject function outdated
 # [#15727] Module cache, id, Itemid
 # [#16314] PHPDoc Comment for JFactory getDocument method Incorrect
 # [#16349] Revert 16122 and Replace sr-ME language files

09-May-2009 Kevin Devine
 # [#15909] RSS 2.0 feeds try to turn mailto: links into absolute urls
 # [#16211] Atom Link in RSS feed causes validation problems
 # [#16007] JFile::getName() loses first character

05-May-2009 Ian MacLennan
 # [#15541] Article Catagory Layout incorrectly sorted
 # [#15699] Global Configuration Undefined Variable warning
 # [#15740] At login use of task=register instead of view=register
 # [#15853] Search results pagination broken when two or more search terms, SEF enabled
 # [#15912] Error in KEPRI Template, css for icon 'article'
 # [#15963] The align attribute in mod_mainmenu helper causes problems with some templates

24-Apr-2009 Kevin Devine
 # [#15446] Atom feed does not validate for Contact Category and Weblinks Category Layouts
 # [#13890] < Prev and Next > links do not follow SEF rules (duplicate content)
 # [#15691] Need better control of who can upload files
 # [#16112] Change in Montengrin Language ISO Code
 # [#15551] Inconsistent prefix for index.php in com_users #2
 # [#15913] Change in installation version display (patch included)
 # [#15701] Joomla 1.5.10 breaks package installation due to new installer PHP code
