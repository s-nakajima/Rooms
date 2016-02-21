<?php
/**
 * RoomFormHelper::changeStatus()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');
App::uses('RoomFixture', 'Rooms.Test/Fixture');
App::uses('SpaceFixture', 'Rooms.Test/Fixture');

/**
 * RoomFormHelper::changeStatus()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Case\View\Helper\RoomFormHelper
 */
class RoomFormHelperChangeStatusTest extends NetCommonsHelperTestCase {

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
		$viewVars = array();
		$requestData = array();

		//Helperロード
		$this->loadHelper('Rooms.RoomForm', $viewVars, $requestData);
	}

/**
 * changeStatus()のテスト(公開中の場合)
 *
 * @return void
 */
	public function testChangeStatusActive() {
		//データ生成
		$room['Space'] = (new SpaceFixture())->records[0];
		$room['Room'] = (new RoomFixture())->records[1];
		$room['Room']['active'] = '1';

		//テスト実施
		$result = $this->RoomForm->changeStatus($room);

		//チェック
		$this->assertInput('form', null, '/rooms/active/1/4', $result);
		$this->assertInput('input', '_method', 'PUT', $result);
		$this->assertInput('input', 'data[Room][id]', '4', $result);
		$this->assertInput('input', 'data[Room][active]', '0', $result);
		$this->assertTextContains(__d('rooms', 'It will be in maintenance'), $result);
	}

/**
 * changeStatus()のテスト(準備中の場合)
 *
 * @return void
 */
	public function testChangeStatusInactive() {
		//データ生成
		$room['Space'] = (new SpaceFixture())->records[0];
		$room['Room'] = (new RoomFixture())->records[1];
		$room['Room']['active'] = '0';

		//テスト実施
		$result = $this->RoomForm->changeStatus($room);

		//チェック
		$this->assertInput('form', null, '/rooms/active/1/4', $result);
		$this->assertInput('input', '_method', 'PUT', $result);
		$this->assertInput('input', 'data[Room][id]', '4', $result);
		$this->assertInput('input', 'data[Room][active]', '1', $result);
		$this->assertTextContains(__d('rooms', 'Open the room'), $result);
	}

}
