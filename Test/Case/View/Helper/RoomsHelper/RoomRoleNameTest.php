<?php
/**
 * RoomsHelper::roomRoleName()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * RoomsHelper::roomRoleName()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Case\View\Helper\RoomsHelper
 */
class RoomsHelperRoomRoleNameTest extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

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

		//テストデータ生成
		$viewVars = array(
			'defaultRoles' => array(
				'room_administrator' => ['name' => 'Room administrator']
			)
		);
		$requestData = array();

		//Helperロード
		$this->loadHelper('Rooms.Rooms', $viewVars, $requestData);
	}

/**
 * settingTabs用DataProvider
 *
 * ### 戻り値
 *  - controller コントローラ
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		$results = array();
		$results[0] = array('room_administrator', 'Room administrator');
		$results[1] = array(array('RolesRoom' => array('role_key' => 'room_administrator')), 'Room administrator');
		$results[2] = array('aaaaa', '');
		$results[3] = array(array('RolesRoom' => array('role_key' => 'aaaaa')), '');

		return $results;
	}

/**
 * roomRoleName()のテスト
 *
 * @param string|array $roomRoleKey ルームロールキー
 * @param string $expected 期待値
 * @dataProvider dataProvider
 * @return void
 */
	public function testRoomRoleName($roomRoleKey, $expected) {
		//テスト実施
		$result = $this->Rooms->roomRoleName($roomRoleKey);

		//チェック
		$this->assertEquals($expected, $result);
	}

}
