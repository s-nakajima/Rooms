<?php
/**
 * Rooms Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * Rooms Component
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Controller\Component
 */
class RoomsComponent extends Component {

/**
 * 一覧表示の参加者リスト件数
 *
 * @var const
 */
	const LIST_LIMIT_ROOMS_USERS = 5;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		//コンポーネント内でPaginatorを使うため、Paginatorがロードされている必要がある
		$controller->Paginator = $controller->Components->load('Paginator');

		//Modelの呼び出し
		$controller->Room = ClassRegistry::init('Rooms.Room');
		$controller->Role = ClassRegistry::init('Roles.Role');
		$controller->RolesRoomsUser = ClassRegistry::init('Rooms.RolesRoomsUser');

		$this->controller = $controller;
	}

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param Controller $controller Controller with components to startup
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::startup
 */
	public function startup(Controller $controller) {
		//スペースデータ取得＆viewVarsにセット
		$spaces = $controller->Room->getSpaces();
		$controller->set('spaces', $spaces);
		$controller->helpers[] = 'Rooms.Rooms';

		$defaultRoles = $controller->Role->find('list', array(
			'recursive' => -1,
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_system' => true,
				'language_id' => Current::read('Language.id'),
				'type' => Role::ROLE_TYPE_ROOM
			),
			'order' => array('id' => 'asc')
		));
		$controller->set('defaultRoles', $defaultRoles);
	}

/**
 * ルームデータ取得
 *
 * @param int $spaceId スペースID
 * @return void
 */
	public function setRoomsForPaginator($spaceId) {
		$controller = $this->controller;

		//ルームデータ取得
		$spaces = $controller->Room->getSpaces();
		$controller->Paginator->settings = $controller->Room->getRoomsCondtions($spaceId);
		$rooms = $controller->Paginator->paginate('Room');
		$rooms = Hash::combine($rooms, '{n}.Room.id', '{n}');
		$controller->set('rooms', $rooms);

		$roomIds = array_keys($rooms);

		//Treeリスト取得
		$roomTreeList = $controller->Room->generateTreeList(
				array('Room.id' => array_merge(array($spaces[$spaceId]['Room']['id']), $roomIds)), null, null, Room::$treeParser);
		$controller->set('roomTreeList', $roomTreeList);

		//参加者リスト取得
		$rolesRoomsUsers = array();
		foreach ($roomIds as $roomId) {
			$result = $controller->RolesRoomsUser->getRolesRoomsUsers(
				array('Room.id' => $roomId),
				array(
					'fields' => array(
						'User.id', 'User.handlename'
					),
					'order' => array(
						'RoomRole.weight' => 'asc'
					),
					'limit' => self::LIST_LIMIT_ROOMS_USERS + 1
				)
			);
			$rolesRoomsUsers[$roomId] = $result;
		}
		$controller->set('rolesRoomsUsers', $rolesRoomsUsers);
	}

}
