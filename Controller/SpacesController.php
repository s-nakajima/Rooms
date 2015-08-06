<?php
/**
 * Rooms Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('RoomsAppController', 'Rooms.Controller');

/**
 * Rooms Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Controller
 */
class SpacesController extends RoomsAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Roles.DefaultRolePermission',
//		'Rooms.RoomsLanguage',
//		'Rooms.Room',
//		'Rooms.Space',
		'Rooms.SpacesLanguage',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		'M17n.SwitchLanguage',
		'Rooms.SpaceTabs',
	);

/**
 * edit
 *
 * @return void
 */
	public function edit($spaceId = null) {
		//スペースデータチェック
		if (! $this->SpaceTabs->exist($spaceId)) {
			$this->throwBadRequest();
			return;
		}
		$this->set('activeSpaceId', $spaceId);
		$this->request->data = $this->SpaceTabs->get($spaceId);

		$this->request->data['SpacesLanguage'] = $this->SpacesLanguage->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'space_id' => $spaceId,
			),
		));

		$data = $this->DefaultRolePermission->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'type' => $this->request->data['Space']['plugin'],
			),
		));
		$this->request->data['DefaultRolePermission'] = Hash::combine($data, '{n}.DefaultRolePermission.role_key', '{n}', '{n}.DefaultRolePermission.permission');
	}

}
