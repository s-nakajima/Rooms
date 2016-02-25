<?php
/**
 * SaveRoomAssociationsBehavior::saveDefaultPage()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * SaveRoomAssociationsBehavior::saveDefaultPage()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Case\Model\Behavior\SaveRoomAssociationsBehavior
 */
class SaveRoomAssociationsBehaviorSaveDefaultPageTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.pages.languages_page',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'rooms';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Rooms', 'TestRooms');
		$this->TestModel = ClassRegistry::init('TestRooms.TestSaveRoomAssociationsBehaviorModel');
	}

/**
 * saveDefaultPage()テストのDataProvider
 *
 * ### 戻り値
 *  - data Room data
 *
 * @return array データ
 */
	public function dataProvider() {
		return array(
			array('data' => array('Room' => array('id' => '4', 'space_id' => '2')))
		);
	}

/**
 * saveDefaultPage()のテスト
 *
 * @param array $data 登録データ
 * @dataProvider dataProvider
 * @return void
 */
	public function testSaveDefaultPage($data) {
		//テストデータ作成
		Current::$current = Hash::insert(Current::$current, 'Language.id', '2');

		$roomId = $data['Room']['id'];

		//テスト実施
		$result = $this->TestModel->saveDefaultPage($data);
		$this->assertTrue($result);

		//チェック
		$pageId = $this->TestModel->Page->getLastInsertID();
		$this->__acualPage($roomId, $pageId);
		$this->__acualLanguagesPage($pageId);
		$this->__acualRoom($roomId, $pageId);
	}

/**
 * saveDefaultPage()のExceptionErrorテスト(Page->savePage())
 *
 * @param array $data 登録データ
 * @dataProvider dataProvider
 * @return void
 */
	public function testPageOnExceptionError($data) {
		$this->_mockForReturnFalse('TestModel', 'Pages.Page', 'savePage');

		//テスト実施
		$this->setExpectedException('InternalErrorException');
		$this->TestModel->saveDefaultPage($data);
	}

/**
 * saveDefaultPage()のExceptionErrorテスト(Room->updateAll())
 *
 * @param array $data 登録データ
 * @dataProvider dataProvider
 * @return void
 */
	public function testRoomOnExceptionError($data) {
		$this->_mockForReturnTrue('TestModel', 'Pages.Page', 'savePage');
		$this->_mockForReturnFalse('TestModel', 'Rooms.Room', 'updateAll');

		//テスト実施
		$this->setExpectedException('InternalErrorException');
		$this->TestModel->saveDefaultPage($data);
	}

/**
 * Pageのチェック
 *
 * @param int $roomId ルームID
 * @param int $pageId ページID
 * @return void
 */
	private function __acualPage($roomId, $pageId) {
		$expected = array('Page' => array (
			'id' => $pageId,
			'room_id' =>$roomId,
			'parent_id' => null,
			'lft' => '9',
			'rght' => '10',
			'permalink' => OriginalKeyBehavior::generateKey('Page', $this->TestModel->useDbConfig),
			'slug' => OriginalKeyBehavior::generateKey('Page', $this->TestModel->useDbConfig),
		));

		$result = $this->TestModel->Page->find('first', array(
			'recursive' => -1,
			'fields' => array_keys($expected['Page']),
			'conditions' => array('id' => $pageId),
		));
		$this->assertEquals($expected, $result);
	}

/**
 * LanguagesPageのチェック
 *
 * @param int $pageId ページID
 * @return void
 */
	private function __acualLanguagesPage($pageId) {
		$result = $this->TestModel->Page->LanguagesPage->find('all', array(
			'recursive' => -1,
			'fields' => array('id', 'page_id', 'language_id', 'name'),
			'conditions' => array('page_id' => $pageId),
		));

		$this->assertCount(1, $result);
		$this->assertEquals(array(
			'id' => '9',
			'page_id' => $pageId,
			'language_id' => '2',
			'name' => 'Top',
		), Hash::get($result, '0.LanguagesPage'));
	}

/**
 * Roomのチェック
 *
 * @param int $roomId ルームID
 * @param int $pageIdTop 最初のページID
 * @return void
 */
	private function __acualRoom($roomId, $pageIdTop) {
		$expected = array('Room' => array (
			'id' => $roomId,
			'space_id' => '2',
			'page_id_top' => $pageIdTop,
		));

		$result = $this->TestModel->Room->find('first', array(
			'recursive' => -1,
			'fields' => array_keys($expected['Room']),
			'conditions' => array('id' => $roomId),
		));
		$this->assertEquals($expected, $result);
	}

}
