<?php
/**
 * RoomsRolesFormHelper::checkboxRoomRoles()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * RoomsRolesFormHelper::checkboxRoomRoles()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Case\View\Helper\RoomsRolesFormHelper
 */
class RoomsRolesFormHelperCheckboxRoomRolesTest extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.rooms.room_role',
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
		$RoomRole = ClassRegistry::init('Rooms.RoomRole');
		$Role = ClassRegistry::init('Roles.Role');

		//テストデータ生成
		$viewVars = array();
		$roomRoles = $RoomRole->find('all', array('recursive' => -1));
		$viewVars['roles'] = Hash::combine($roomRoles, '{n}.RoomRole.role_key', '{n}.RoomRole');

		$requestData = array();

		$roles = $Role->find('all', array('recursive' => -1,
			'conditions' => array(
				'Role.type' => Role::ROLE_TYPE_ROOM,
				'Role.language_id' => Current::read('Language.id'),
			),
		));
		$requestData['Role'] = Hash::combine($roles, '{n}.Role.key', '{n}.Role');

		$requestData['RoomRolePermission'] = array('content_publishable' => array(
			'room_administrator' => array(
				'id' => '1',
				'value' => '1',
				'fixed' => true,
			),
			'chief_editor' => array(
				'id' => '2',
				'value' => '1',
				'fixed' => false,
			),
			'editor' => array(
				'id' => '3',
				'value' => '0',
				'fixed' => false,
			),
			'general_user' => array(
				'id' => '4',
				'value' => '0',
				'fixed' => true,
			),
			'visitor' => array(
				'id' => '5',
				'value' => '0',
				'fixed' => true,
			),
		));

		//Helperロード
		$this->loadHelper('Rooms.RoomsRolesForm', $viewVars, $requestData);
	}

/**
 * checkboxRoomRoles()のテスト
 *
 * @return void
 */
	public function testCheckboxRoomRoles() {
		//テスト実施
		$result = $this->RoomsRolesForm->checkboxRoomRoles('RoomRolePermission.content_publishable', array());

		//チェック
		$this->__assertCheckboxRoomRoles($result);
	}

/**
 * checkboxRoomRoles()のテスト
 *
 * @return void
 */
	public function testWithLabel() {
		//テスト実施
		$result = $this->RoomsRolesForm->checkboxRoomRoles('RoomRolePermission.content_publishable', array(
			'label' => 'Content publishable label'
		));

		//チェック
		$pattern = '<label for="RoomRolePermissionContentPublishable" class="control-label">Content publishable label</label>';
		$this->assertTextContains($pattern, $result);

		$this->__assertCheckboxRoomRoles($result);
	}

/**
 * checkboxRoomRoles()のチェック
 *
 * @param string $result 結果
 * @return void
 */
	private function __assertCheckboxRoomRoles($result) {
		$pattern = 'ng-controller="RoomRolePermissions" ng-init="RolePermission\.initialize\(.*?\)"';
		$this->assertRegExp('/' . $pattern . '/', $result);

		//ルーム管理者
		$name = 'data[RoomRolePermission][content_publishable][room_administrator][id]';
		$this->assertInput('input', $name, '1', $result);

		$name = 'data[RoomRolePermission][content_publishable][room_administrator][value]';
		$domId = 'RoomRolePermissionContentPublishableRoomAdministratorValue';
		$pattern = '<input type="hidden" name="' . preg_quote($name, '/') . '" id="' . $domId . '_" value="0"';
		$this->assertRegExp('/' . $pattern . '/', $result);
		$pattern = '<input type="checkbox" name="' . preg_quote($name, '/') . '" disabled="disabled" value="1" id="' . $domId . '" checked="checked"\/>';
		$this->assertRegExp('/' . $pattern . '/', $result);

		//編集長
		$name = 'data[RoomRolePermission][content_publishable][chief_editor][id]';
		$this->assertInput('input', $name, '2', $result);

		$name = 'data[RoomRolePermission][content_publishable][chief_editor][value]';
		$domId = 'RoomRolePermissionContentPublishableChiefEditorValue';
		$pattern = '<input type="hidden" name="' . preg_quote($name, '/') . '" id="' . $domId . '_" value="0"';
		$this->assertRegExp('/' . $pattern . '/', $result);
		$pattern = '<input type="checkbox" name="' . preg_quote($name, '/') . '" ng-click=".+?" value="1" id="' . $domId . '" checked="checked"\/>';
		$this->assertRegExp('/' . $pattern . '/', $result);

		//編集者
		$name = 'data[RoomRolePermission][content_publishable][editor][id]';
		$this->assertInput('input', $name, '3', $result);

		$name = 'data[RoomRolePermission][content_publishable][editor][value]';
		$domId = 'RoomRolePermissionContentPublishableEditorValue';
		$pattern = '<input type="hidden" name="' . preg_quote($name, '/') . '" id="' . $domId . '_" value="0"';
		$this->assertRegExp('/' . $pattern . '/', $result);
		$pattern = '<input type="checkbox" name="' . preg_quote($name, '/') . '" ng-click=".+?" value="1" id="' . $domId . '"\/>';
		$this->assertRegExp('/' . $pattern . '/', $result);

		//一般
		$name = 'data[RoomRolePermission][content_publishable][general_user][id]';
		$this->assertTextNotContains($name, $result);

		$name = 'data[RoomRolePermission][content_publishable][general_user][value]';
		$this->assertTextNotContains($name, $result);

		//ビジター
		$name = 'data[RoomRolePermission][content_publishable][visitor][id]';
		$this->assertTextNotContains($name, $result);

		$name = 'data[RoomRolePermission][content_publishable][visitor][value]';
		$this->assertTextNotContains($name, $result);
	}

}
