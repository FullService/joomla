<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="projects-module">
	<h4><?php echo JText::_('COM_PROJECTS_PROJECT_TICKETS_LIST');?></h4>
	<div class="projects-content">
		<?php
			$c = count($this->tickets); 
			if($c) : // list tickets ?>
			<ul class="ulList">
			<?php
				for($i = 0; $i < $c; $i++) {
					?>
					<li><a href="<?php echo ProjectsHelper::getLink('task.view.ticket',$this->tickets[$i]->id)?>"><?php echo $this->tickets[$i]->title?></a></li>
					<?php
				} ?>
			</ul>
			<?php
			else:
				echo JText::_('COM_PROJECTS_PROJECT_NO_TICKET').'<br /><br />';
			endif
		?>
		<a href="<?php echo ProjectsHelper::getLink('tasks.ticket', $this->item->id); ?>" class="readmore">
			<?php echo JText::_('COM_PROJECTS_PROJECT_TICKETS_LIST_LINK'); ?></a>	
	</div>
</div>