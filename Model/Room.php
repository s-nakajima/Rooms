<?php
/**
 * Room Model
 *
 * @property Space $Space
 * @property Room $ParentRoom
 * @property Room $ChildRoom
 * @property Language $Language
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('RoomsAppModel', 'Rooms.Model');

/**
 * Room Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Model
 */
class Room extends RoomsAppModel {

/**
 * room id
 *
 * @var const
 */
	const
		PUBLIC_PARENT_ID = '1',
		PRIVATE_PARENT_ID = '2',
		ROOM_PARENT_ID = '3';

/**
 * TreeParser
 * __constructでセットする
 *
 * @var array
 */
	public static $treeParser;

/**
 * スペースルームIDのリスト
 *
 * @var array
 */
	public static $spaceRooms = array(
		self::PUBLIC_PARENT_ID,
		self::PRIVATE_PARENT_ID,
		self::ROOM_PARENT_ID,
	);

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',
		'Rooms.DeleteRoomAssociations',
		'Rooms.Room',
		'Rooms.SaveRoomAssociations',
		'Tree',
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Space' => array(
			'className' => 'Rooms.Space',
			'foreignKey' => 'space_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ParentRoom' => array(
			'className' => 'Rooms.Room',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ChildRoom' => array(
			'className' => 'Rooms.Room',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'RoomsLanguage' => array(
			'className' => 'Rooms.RoomsLanguage',
			'foreignKey' => 'room_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

/**
 * Constructor. Binds the model's database table to the object.
 *
 * @param bool|int|string|array $id Set this ID for this model on startup,
 * can also be an array of options, see above.
 * @param string $table Name of database table to use.
 * @param string $ds DataSource connection name.
 * @see Model::__construct()
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		self::$treeParser = chr(9);
	}

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		//RoomsLanguageのバリデーション
		$roomsLanguages = $this->data['RoomsLanguage'];
		if (! $this->RoomsLanguage->validateMany($roomsLanguages)) {
			$this->validationErrors = Hash::merge(
				$this->validationErrors,
				$this->RoomsLanguage->validationErrors
			);
			return false;
		}

		if (! isset($this->data['RoomRolePermission'])) {
			return true;
		}

		$this->loadModels(array(
			'RoomRolePermission' => 'Rooms.RoomRolePermission',
		));

		foreach ($this->data[$this->RoomRolePermission->alias] as $permission) {
			if (! $this->RoomRolePermission->validateMany($permission)) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->RoomRolePermission->validationErrors);
				return false;
			}
		}
		return parent::beforeValidate($options);
	}

/**
 * Called after each successful save operation.
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
 * @see Model::save()
 */
	public function afterSave($created, $options = array()) {
		//RoomsLanguage登録
		if (isset($this->data['RoomsLanguage'])) {
			$roomsLanguages = Hash::insert($this->data['RoomsLanguage'], '{n}.room_id', $this->data['Room']['id']);
			foreach ($roomsLanguages as $index => $roomsLanguage) {
				if (! $result = $this->RoomsLanguage->save($roomsLanguage, false, false)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
				$this->data['RoomsLanguage'][$index] = $result;
			}
		}

		//デフォルトデータ登録処理
		$room = $this->data;
		if ($created) {
			$this->saveDefaultRolesRoom($room);
			$this->saveDefaultRolesRoomsUser($room, true);
			$this->saveDefaultRolesPluginsRoom($room);
			$this->saveDefaultRoomRolePermission($room);
			$this->saveDefaultPage($room);
		}

		if (isset($room['RoomRolePermission'])) {
			if ($created) {
				$roomRolePermissions = $this->RoomRolePermission->find('all', array(
					'recursive' => 0,
					'conditions' => array(
						'RolesRoom.room_id' => $room['Room']['id'],
						'RoomRolePermission.permission' => array_keys($room['RoomRolePermission'])
					)
				));
				$roomRolePermissions = Hash::combine($roomRolePermissions,
					'{n}.RolesRoom.role_key',
					'{n}.RoomRolePermission',
					'{n}.RoomRolePermission.permission'
				);
				$room['RoomRolePermission'] = Hash::remove($room['RoomRolePermission'], '{s}.{s}.id');
				$room['RoomRolePermission'] = Hash::merge($roomRolePermissions, $room['RoomRolePermission']);
			}
			foreach ($room['RoomRolePermission'] as $permission) {
				if (! $this->RoomRolePermission->saveMany($permission, ['validate' => false])) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}
		}

		parent::afterSave($created, $options);
	}

/**
 * Called before every deletion operation.
 *
 * @param bool $cascade If true records that depend on this record will also be deleted
 * @return bool True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
 * @throws InternalErrorException
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function beforeDelete($cascade = true) {
		$roomId = $this->id;

		$children = $this->Room->children($roomId, false, 'Room.id', 'Room.rght');
		$roomIds = Hash::extract($children, '{n}.Room.id');
		$roomIds[] = $roomId;

		foreach ($roomIds as $childRoomId) {
			//frameデータの削除
			$this->deleteFramesByRoom($childRoomId);

			//pageデータの削除
			$this->deletePagesByRoom($childRoomId);

			//blockデータの削除
			$this->deleteBlocksByRoom($childRoomId);

			//Roomデータの削除
			if ($roomId === $childRoomId) {
				continue;
			}
			if (! $this->delete($childRoomId, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		return parent::beforeDelete($cascade);
	}

/**
 * Called after every deletion operation.
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterdelete
 */
	public function afterDelete() {
		//Roomの関連データの削除
		$this->deleteRoomAssociations($this->id);
	}

/**
 * ルームの登録処理
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveRoom($data) {
		$this->loadModels([
			'Room' => 'Rooms.Room',
			'RoomsLanguage' => 'Rooms.RoomsLanguage',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			//登録処理
			if (! $room = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $room;
	}

/**
 * 状態の登録処理
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveActive($data) {
		//トランザクションBegin
		$this->begin();

		try {
			//登録処理
			$this->id = $data['Room']['id'];
			if (! $this->saveField('active', (bool)$data['Room']['active'], array('callbacks' => false))) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

/**
 * ルームの削除処理
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function deleteRoom($data) {
		$this->loadModels([
			'Room' => 'Rooms.Room',
			'RoomsLanguage' => 'Rooms.RoomsLanguage',
		]);

		//トランザクションBegin
		$this->begin();

		try {
			//Roomデータの削除
			if (! $this->delete($data['Room']['id'], false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}
}