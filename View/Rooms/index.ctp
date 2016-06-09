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

<?php
	//ルーム管理全体の説明
	echo $this->MessageFlash->description(
		__d('rooms', 'You can add, edit and delete rooms in your NetCommons. And select the members to join in the rooms.')
	);
	//スペースタブ
	echo $this->Rooms->spaceTabs($activeSpaceId);

	//スペース編集の説明
	echo $this->RoomForm->editSpaceDescription($activeSpaceId);

	//ルーム作成の説明
	echo $this->RoomForm->addRoomDescription($activeSpaceId);

	//各ルームの説明
	echo $this->RoomForm->indexRoomDescription($activeSpaceId);
?>

<article class="rooms-manager">
	<?php echo $this->Rooms->roomsRender($activeSpaceId,
			array(
				'dataElemen' => 'Rooms.Rooms/render_room_index',
				'headElement' => 'Rooms.Rooms/render_header'
			)
		); ?>
</article>
