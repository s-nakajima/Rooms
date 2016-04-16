<?php
/**
 * PluginsRooms edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/plugin_manager/css/style.css');
?>

<?php echo $this->element('Rooms.subtitle'); ?>
<?php echo $this->Rooms->spaceTabs($activeSpaceId); ?>
<?php echo $this->Wizard->navibar(RoomsAppController::WIZARD_PLUGINS_ROOMS); ?>
<?php echo $this->MessageFlash->description(__d('rooms', 'You can set the plugins to be used in the specified room. Press [OK] button to proceed.')); ?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('Room'); ?>
	<?php echo $this->NetCommonsForm->hidden('Room.id'); ?>

	<div class="panel-body">
		<div class="form-inline">
			<div class="clearfix">
				<?php echo $this->PluginsForm->checkboxPluginsRoom(
						array('div' => array('class' => 'plugin-checkbox-outer'))); ?>
			</div>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Wizard->buttons(RoomsAppController::WIZARD_PLUGINS_ROOMS); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>
