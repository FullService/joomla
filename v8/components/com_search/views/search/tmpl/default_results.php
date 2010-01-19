<?php defined('_JEXEC') or die; ?>

<table class="contentpaneopen<?php echo $this->params->get('pageclass_sfx'); ?>">
	<tr>
		<td>
		<?php
		foreach($this->results as $result) : ?>
			<fieldset>
				<div>
					<span class="small<?php echo $this->params->get('pageclass_sfx'); ?>">
						<?php echo $this->pagination->limitstart + $result->count.'. ';?>
					</span>
					<?php if ($result->href) :
						if ($result->browsernav == 1) : ?>
							<a href="<?php echo JRoute::_($result->href); ?>" target="_blank">
							<?php echo $this->escape($result->title); ?>
							</a>						
						<?php else : ?>
							<a href="<?php echo JRoute::_($result->href); ?>">
							<?php echo $this->escape($result->title); ?>
							</a>
						<?php endif;?>
							
						<?php if ($result->section) : ?>
							<br />
							<span class="small<?php echo $this->params->get('pageclass_sfx'); ?>">
								(<?php echo $this->escape($result->section); ?>)
							</span>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div>
					<?php echo $result->text; ?>
				</div>
				<?php
					if ($this->params->get('show_date')) : ?>
				<div class="small<?php echo $this->params->get('pageclass_sfx'); ?>">
					<?php echo $result->created; ?>
				</div>
				<?php endif; ?>
			</fieldset>
		<?php endforeach; ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<div align="center">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		</td>
	</tr>
</table>