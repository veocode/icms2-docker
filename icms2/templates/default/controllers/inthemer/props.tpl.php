<div class="content_item_props <?php echo $ctype['name']; ?>_item_props">
	<table>
		<tbody>
			<?php foreach($props_fieldsets as $fieldset){ ?>
				<?php if ($fieldset['title']){ ?>
					<tr>
						<td class="heading" colspan="2"><?php html($fieldset['title']); ?></td>
					</tr>
				<?php } ?>
				<?php if ($fieldset['fields']){ ?>
					<?php foreach($fieldset['fields'] as $prop){ ?>
						<?php if (isset($props_values[$prop['id']])) { ?>
						<?php $prop_field = $props_fields[$prop['id']]; ?>
							<tr>
								<td class="title"><?php html($prop['title']); ?></td>
								<td class="value">
									<?php echo $prop_field->setItem($item)->parse($props_values[$prop['id']]); ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>