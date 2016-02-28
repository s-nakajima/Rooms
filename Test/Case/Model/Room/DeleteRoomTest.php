<?php
/**
 * Room::deleteRoom()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsDeleteTest', 'NetCommons.TestSuite');

/**
 * Room::deleteRoom()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Case\Model\Room
 */
class RoomDeleteRoomTest extends NetCommonsDeleteTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.rooms.roles_room4test',
		'plugin.rooms.roles_rooms_user4test',
		'plugin.rooms.room4test',
		'plugin.rooms.room_role',
		'plugin.rooms.room_role_permission',
		'plugin.rooms.rooms_language4test',
		'plugin.rooms.space',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'rooms';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'Room';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'deleteRoom';

/**
 * Delete用DataProvider
 *
 * ### 戻り値
 *  - data: 削除データ
 *  - associationModels: 削除確認の関連モデル array(model => conditions)
 *
 * @return array テストデータ
 */
	public function dataProviderDelete() {
		$data['Room'] = array('id' => '1');
		$association = array();

		$results = array();
		$results[0] = array($data, $association);

		return $results;
	}

/**
 * Deleteのテスト
 *
 * @param array|string $data 削除データ
 * @param array $associationModels 削除確認の関連モデル array(model => conditions)
 * @dataProvider dataProviderDelete
 * @return void
 */
	public function testDelete($data, $associationModels = null) {
		$model = $this->_modelName;
		$this->$model = $this->getMockForModel('Rooms.Room', array(
			'deleteFramesByRoom', 'deletePagesByRoom', 'deleteBlocksByRoom', 'deleteRoomAssociations'
		));
		$this->_mockForReturnTrue($model, 'Rooms.Room', 'deleteFramesByRoom', 4);
		$this->_mockForReturnTrue($model, 'Rooms.Room', 'deletePagesByRoom', 4);
		$this->_mockForReturnTrue($model, 'Rooms.Room', 'deleteBlocksByRoom', 4);
		$this->_mockForReturnTrue($model, 'Rooms.Room', 'deleteRoomAssociations', 4);

		//テスト実施
		parent::testDelete($data, $associationModels);
	}

/**
 * ExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderDeleteOnExceptionError() {
		$data['Room'] = array('id' => '1');

		return array(
			array($data, 'Rooms.Room', 'delete'),
		);
	}

/**
 * DeleteAllのExceptionErrorテスト
 *
 * @return void
 */
	public function testDeleteAllOnExceptionError() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//TreeBehaviorでもdeleteAllが実行されるため、計２回となる
		$this->_mockForReturnFalse($model, 'Rooms.Room', 'deleteAll', 2);

		$this->setExpectedException('InternalErrorException');
		$data['Room'] = array('id' => '1');
		$this->$model->$method($data);
	}

}
