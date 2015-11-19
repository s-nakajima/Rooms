<?php
/**
 * Room Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ModelBehavior', 'Model');

/**
 * Room Behavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Model\Behavior
 */
class RoomBehavior extends ModelBehavior {

/**
 * スペースデータ
 *
 * @var array
 */
	private static $__spaces;

/**
 * Setup this behavior with the specified configuration settings.
 *
 * @param Model $model Model using this behavior
 * @param array $config Configuration settings for $model
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		parent::setup($model, $config);

		$model->loadModels([
			'RolesRoom' => 'Rooms.RolesRoom',
			'RolesRoomsUser' => 'Rooms.RolesRoomsUser',
			'Room' => 'Rooms.Room',
			'RoomsLanguage' => 'Rooms.RoomsLanguage',
			'Space' => 'Rooms.Space',
		]);
	}

/**
 * ルームデータ取得用の条件取得
 *
 * @param Model $model ビヘイビア呼び出し元モデル
 * @param int $spaceId SpaceId
 * @param array $conditions 条件配列
 * @return array ルームデータ取得条件
 */
	public function getRoomsCondtions(Model $model, $spaceId, $conditions = array()) {
		$options = Hash::merge(array(
			//'recursive' => 0,
			'conditions' => array(
				'Room.space_id' => $spaceId,
				$model->Room->alias . '.page_id_top NOT' => null,
			),
			'order' => 'Room.lft',
		), array('conditions' => $conditions));

		return $options;
	}

/**
 * ルームデータ取得用の条件取得
 *
 * @param Model $model ビヘイビア呼び出し元モデル
 * @param array $conditions 条件配列
 * @return array ルームデータ取得条件
 */
	public function getReadableRoomsCondtions(Model $model, $conditions = array()) {
		$spaceIds = array();
		$spaceIds[] = Space::PUBLIC_SPACE_ID;
		if (Current::read('User.id')) {
			$spaceIds[] = Space::ROOM_SPACE_ID;
			$joinType = 'INNER';
		} else {
			$joinType = 'LEFT';
		}
		if (Current::read('User.UserRoleSetting.use_private_room')) {
			$spaceIds[] = Space::PRIVATE_SPACE_ID;
		}

		$options = Hash::merge(array(
			//'recursive' => -1,
			'fields' => array(
				$model->Room->alias . '.*',
				$model->RolesRoom->alias . '.*',
				$model->RolesRoomsUser->alias . '.*',
				$model->Space->alias . '.*',
			),
			'conditions' => array(
				$model->Room->alias . '.space_id' => $spaceIds,
				$model->Room->alias . '.page_id_top NOT' => null,
				//$model->Room->alias . '.root_id' => $rootIds,
			),
			'joins' => array(
				array(
					'table' => $model->RolesRoomsUser->table,
					'alias' => $model->RolesRoomsUser->alias,
					'type' => $joinType,
					'conditions' => array(
						$model->RolesRoomsUser->alias . '.room_id' . ' = ' . $model->Room->alias . ' .id',
						$model->RolesRoomsUser->alias . '.user_id' => Current::read('User.id'),
					),
				),
				array(
					'table' => $model->RolesRoom->table,
					'alias' => $model->RolesRoom->alias,
					'type' => $joinType,
					'conditions' => array(
						$model->RolesRoomsUser->alias . '.roles_room_id' . ' = ' . $model->RolesRoom->alias . ' .id',
						$model->RolesRoom->alias . '.room_id' . ' = ' . $model->Room->alias . ' .id',
					),
				),
			),
			'order' => 'Room.lft',
		), array('conditions' => $conditions));

		return $options;
	}

/**
 * スペースデータ取得
 *
 * @param Model $model ビヘイビア呼び出し元モデル
 * @return array スペースデータ配列
 */
	public function getSpaces(Model $model) {
		if (self::$__spaces) {
			return self::$__spaces;
		}

		//スペースデータ取得
		$model->Room->unbindModel(array(
			'belongsTo' => array('ParentRoom'),
			'hasMany' => array('ChildRoom')
		));
		$spaces = $model->Room->find('all', array(
			//'recursive' => 0,
			//'fields' => '*',
			'conditions' => array(
				$model->Room->alias . '.parent_id' => null,
			),
			'order' => 'Room.lft'
		));

		$spaces = Hash::combine($spaces, '{n}.Room.id', '{n}');

		$result = $model->RolesRoom->find('all', array(
			'recursive' => -1,
			'fields' => '*',
			'conditions' => array(
				$model->RolesRoom->alias . '.room_id' => array(Room::PUBLIC_PARENT_ID, Room::PRIVATE_PARENT_ID),
			),
			'joins' => array(
				array(
					'table' => $model->RolesRoomsUser->table,
					'alias' => $model->RolesRoomsUser->alias,
					'type' => 'INNER',
					'conditions' => array(
						$model->RolesRoomsUser->alias . '.roles_room_id' . ' = ' . $model->RolesRoom->alias . ' .id',
						$model->RolesRoomsUser->alias . '.user_id' => Current::read('User.id'),
					),
				),
			),
		));
		$result = Hash::combine($result, '{n}.RolesRoom.room_id', '{n}');

		self::$__spaces = Hash::combine(Hash::merge($spaces, $result), '{n}.Space.id', '{n}');

		return self::$__spaces;
	}

/**
 * ロールルームデータの取得
 *
 * @param Model $model ビヘイビア呼び出し元モデル
 * @param array $conditions 条件配列
 * @return array
 */
	public function getRolesRooms(Model $model, $conditions = array()) {
		$conditions = Hash::merge(array(
			'Room.page_id_top NOT' => null,
		), $conditions);

		$rolesRooms = $model->RolesRoom->find('all', array(
			'recursive' => 0,
			'fields' => array(
				$model->RolesRoom->alias . '.*',
				$model->Room->alias . '.*',
			),
			'conditions' => $conditions,
		));

		return $rolesRooms;
	}

}