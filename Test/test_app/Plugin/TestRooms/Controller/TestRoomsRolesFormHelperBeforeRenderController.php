<?php
/**
 * RoomsRolesFormHelperテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * RoomsRolesFormHelperテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\test_app\Plugin\TestRooms\Controller
 */
class TestRoomsRolesFormHelperBeforeRenderController extends AppController {

/**
 * 使用ヘルパー
 *
 * @var array
 */
	public $helpers = array(
		'Rooms.RoomsRolesForm'
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->autoRender = true;
	}

}
