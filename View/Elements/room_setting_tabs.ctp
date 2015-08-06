<?php
/**
 * Space setting tabs template
 *   - $activeSpaceId: Active spaces.id.
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

if ($this->params['action'] === 'add') {
	$disabled = 'disabled';
	$urlRooms = '';
	$urlRoomsUsers = '';
	$urlPluginsRooms = '';
} else {
	$disabled = '';
	$urlRooms = '/rooms/rooms/' . $this->params['action'] . '/' . h($activeRoomId) . '/';
	$urlRoomsUsers = '/rooms/rooms_users/edit/' . h($activeRoomId) . '/';
	$urlPluginsRooms = '/rooms/plugins_rooms/edit/' . h($activeRoomId) . '/';
}
?>

<ul class="nav nav-pills" role="tablist">
	<li class="<?php echo ($this->params['controller'] === 'rooms' ? 'active' : $disabled); ?>">
		<?php echo $this->Html->link(__d('rooms', 'General setting'), $urlRooms); ?>
	</li>

	<li class="<?php echo ($this->params['controller'] === 'rooms_users' ? 'active' : $disabled); ?>">
		<?php echo $this->Html->link(__d('rooms', 'Select the members to join'), $urlRoomsUsers); ?>
	</li>

	<li class="<?php echo ($this->params['controller'] === 'plugins_rooms' ? 'active' : $disabled); ?>">
		<?php echo $this->Html->link(__d('rooms', 'Select the plugins to join'), $urlPluginsRooms); ?>
	</li>
</ul>

<br>
