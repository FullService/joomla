<?php

 /**
 * @version		/** $Id: default_form.php 11845 2009-05-27 23:28:59Z robs 
 * @package		Joomla.Site
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

	$script = '<!--
		function validateForm(frm) {
			var valid = document.formvalidator.isValid(frm);
			if (valid == false) {
				// do field validation
				if (frm.email.invalid) {
					alert("' . JText::_('Com_contact_contact_enter_valid_e-mail.', true) . '");
				} else if (frm.text.invalid) {
					alert("' . JText::_('Com_contact_contact_CONTACT_FORM_NC', true) . '");
				}
				return false;
			} else {
				frm.submit();
			}
		}
		// -->';
	$document = &JFactory::getDocument();
	$document->addScriptDeclaration($script); ?>

<?php if (isset($this->error)) : ?>
<div class="jcontact-error">
	<?php echo $this->error; ?>
</div>
<?php endif; ?>

<div class="jcontact-form">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" name="emailForm" id="emailForm" class="form-validate">
		<div class="jform-required">
			<?php echo JText::_('Com_contact_contact_required');?>
		</div>
		<div class="contact_email">
			<div>
				<label for="contact_name">
					<?php echo JText::_('Com_contact_contact_Email_name');?>
				</label>
				<input type="text" name="name" id="contact_name" size="30" class="inputbox" value="" />
			</div>
			<div>
				<label id="contact_emailmsg" for="contact_email">
					<?php echo JText::_('Com_contact_contact_Email_address');?>*
				</label>
				<input type="text" id="contact_email" name="email" size="30" value="" class="inputbox required validate-email" maxlength="100" />
			</div>
			<div>
				<label for="contact_subject">
					<?php echo JText::_('Com_contact_contact_Message_subject');?>:
				</label>
				<input type="text" name="subject" id="contact_subject" size="30" class="inputbox" value="" />
			</div>
			<div>
				<label id="contact_textmsg" for="contact_text">
					<?php echo JText::_('Com_contact_contact_Enter_message');?>:
				</label>
				<textarea cols="50" rows="10" name="text" id="contact_text" class="inputbox required"></textarea>
			</div>
			
			<?php if ($this->contact->params->get('show_email_copy')) : ?>
			<div>
				<input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />
				<label for="contact_email_copy">
					<?php echo JText::_('Com_contact_contact_EMAIL_A_COPY'); ?>
				</label>
			</div>
			<?php endif; ?>
			<div>
			<button class="button validate" type="submit"><?php echo JText::_('Com_contact_contact_Send'); ?></button>
			</div>
		</div>

	<input type="hidden" name="option" value="com_contact" />
	<input type="hidden" name="view" value="contact" />
	<input type="hidden" name="id" value="<?php echo $this->contact->id; ?>" />
	<input type="hidden" name="task" value="submit" />
	<input type="hidden" name="return" value="<?php echo $this->return; ?>" /> 
	<?php echo JHtml::_('form.token'); ?>
	</form>
</div>	
