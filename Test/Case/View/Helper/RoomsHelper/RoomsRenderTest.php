<?php
/**
 * RoomsHelper::roomsRender()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * RoomsHelper::roomsRender()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Case\View\Helper\RoomsHelper
 */
class RoomsHelperRoomsRenderTest extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.rooms.rooms_language4test',
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
		$this->Room = ClassRegistry::init('Rooms.Room');
		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Rooms', 'TestRooms');
	}

/**
 * roomsRender()のテスト用DataProvider
 *
 * ### 戻り値
 *  - roomTreeList ルームツリーリスト
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		return array(
			// * roomTreeList指定なし
			array('roomTreeList' => null),
			// * roomTreeList指定あり
			array('roomTreeList' => array('6' => chr(9) . chr(9) . '6')),
		);
	}

/**
 * roomsRender()のテスト
 *
 * @param array $roomTreeList ルームツリーリスト
 * @dataProvider dataProvider
 * @return void
 */
	public function testRoomsRender($roomTreeList) {
		//テストデータ生成
		$viewVars = array();
		$viewVars['spaces'] = $this->Room->getSpaces();
		$viewVars['rooms'] = array(
			'5' => array('Room' => array('id' => '5')),
			'6' => array('Room' => array('id' => '6')),
		);
		$viewVars['roomTreeList'] = array('5' => chr(9) . '5');
		$requestData = array();

		//Helperロード
		$this->loadHelper('Rooms.Rooms', $viewVars, $requestData,
			array('plugin' => 'rooms', 'controller' => 'rooms', 'action' => 'index')
		);

		//データ生成
		$activeSpaceId = '2';
		$headElementPath = 'TestRooms.TestRoomsHelperRoomsRender/render_header';
		$dataElementPath = 'TestRooms.TestRoomsHelperRoomsRender/render_room_index';
		$paginator = false;

		//テスト実施
		$result = $this->Rooms->roomsRender(
			$activeSpaceId,
			array('dataElemen' => $dataElementPath, 'headElement' => $headElementPath),
			array('paginator' => $paginator, 'roomTreeList' => $roomTreeList)
		);

		//チェック
		$this->assertTextContains('View/Helper/TestRoomsHelperRoomsRender/render_header', $result);
		//$this->assertTextContains('/rooms/rooms/add/2/1', $result);
		//$this->assertTextContains('/rooms/rooms/edit/2/1', $result);
		if ($roomTreeList) {
			$this->assertTextContains('View/Helper/TestRoomsHelperRoomsRender/render_room_index/6/1', $result);
		} else {
			$this->assertTextContains('View/Helper/TestRoomsHelperRoomsRender/render_room_index/5/0', $result);
		}
	}

}
