<?php
/**
 * RoomsHelper::statusLabel()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * RoomsHelper::statusLabel()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Case\View\Helper\RoomsHelper
 */
class RoomsHelperStatusLabelTest extends NetCommonsHelperTestCase {

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
		$this->loadHelper('Rooms.Rooms', $viewVars, $requestData);
	}

/**
 * statusLabel()のテスト用DataProvider
 *
 * ### 戻り値
 *  - active アクティブ
 *  - display 表示するかどうか
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		return array(
			array('active' => '1', 'display' => false),
			array('active' => '1', 'display' => true),
			array('active' => '0', 'display' => false),
			array('active' => '0', 'display' => true),
		);
	}

/**
 * statusLabel()のテスト
 *
 * @param int $active アクティブ
 * @param bool $display 表示するかどうか
 * @dataProvider dataProvider
 * @return void
 */
	public function testStatusLabel($active, $display) {
		//データ生成
		$room = array('Room' => array('active' => $active));
		$messageFormat = '%s';

		//テスト実施
		$result = $this->Rooms->statusLabel($room, $messageFormat, $display);

		//チェック
		if (! $active) {
			$expected = ' ' . __d('rooms', 'Under maintenance');
		} elseif ($display) {
			$expected = ' ' . __d('rooms', 'Open');
		} else {
			$expected = '';
		}
		$this->assertEquals($expected, $result);
	}

}
