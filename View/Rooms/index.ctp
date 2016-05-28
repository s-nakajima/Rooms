<?php
/**
 * Rooms index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->element('Rooms.subtitle'); ?>
<?php echo $this->Rooms->spaceTabs($activeSpaceId); ?>
<?php echo $this->MessageFlash->description(
		__d('rooms', 'You can add, edit and delete rooms in your NetCommons. And select the members to join in the rooms.')
	); ?>

<article class="rooms-manager">
	<?php echo $this->Rooms->roomsRender($activeSpaceId, [
			'dataElemen' => 'Rooms.Rooms/render_room_index',
			'headElement' => 'Rooms.Rooms/render_header']
		); ?>
</article>
