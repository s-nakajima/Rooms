<?php
/**
 * Space Model
 *
 * @property Space $ParentSpace
 * @property Room $Room
 * @property Space $ChildSpace
 * @property Language $Language
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('RoomsAppModel', 'Rooms.Model');

/**
 * Space Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Model
 */
class Space extends RoomsAppModel {

/**
 * Table name
 *
 * @var string
 */
	public $useTable = 'spaces';

/**
 * Space id
 *
 * @var const
 */
	const
		WHOLE_SITE_ID = '1',
		PUBLIC_SPACE_ID = '2',
		PRIVATE_SPACE_ID = '3',
		ROOM_SPACE_ID = '4';

/**
 * DefaultParticipationFixed
 *
 * @var bool
 */
	public $participationFixed = false;

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Tree',
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ParentSpace' => array(
			'className' => 'Rooms.Space',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Room' => array(
			'className' => 'Rooms.Room',
			'foreignKey' => 'space_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ChildSpace' => array(
			'className' => 'Rooms.Space',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * RoomSpaceルームのデフォルト値
 *
 * @param array $data 初期値データ配列
 * @return array RoomSpaceルームのデフォルト値配列
 */
	public function createRoom($data = array()) {
		$this->loadModels([
			'Room' => 'Rooms.Room',
			'RoomsLanguage' => 'Rooms.RoomsLanguage',
		]);

		$result = $this->Room->create(Hash::merge(array(
			'id' => null,
			'active' => true,
		), $data));

		$languages = Current::readM17n(null, 'Language');
		foreach ($languages as $i => $language) {
			$roomsLanguage = $this->RoomsLanguage->create(array(
				'id' => null,
				'language_id' => $language['Language']['id'],
				'room_id' => null,
				'name' => '',
			));

			$result['RoomsLanguage'][$i] = $roomsLanguage['RoomsLanguage'];
		}

		return $result;
	}

}
