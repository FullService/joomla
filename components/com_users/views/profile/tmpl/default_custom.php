<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */
defined('_JEXEC') or die;
//load user_profile plugin language
$lang = &JFactory::getLanguage();
$lang->load( 'plg_user_profile', JPATH_ADMINISTRATOR );
?>

<fieldset id="users-profile-custom">
	<legend>
		<?php echo JText::_('COM_USERS_PROFILE_CUSTOM_LEGEND'); ?>
	</legend>
	<dl>
	<?php
	foreach($this->form->getFieldset('profile') as $field):
		if (!$field->hidden) :
	?>
		<dt><?php echo $field->label; ?></dt>
		<dd>
			<?php echo !empty($this->profile[$field->fieldname]) ? $this->profile[$field->fieldname] : JText::_('COM_USERS_PROFILE_VALUE_NOT_FOUND'); ?>
		</dd>
	<?php
		endif;
	endforeach;
	?>
	</dl>
</fieldset>