<?php
/**
 * SpaceFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * SpaceFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Fixture
 */
class SpaceFixture extends CakeTestFixture {

/**
 * Records
 * ※実際のroom_id_rootとは異なる。
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'parent_id' => null,
			'lft' => '1',
			'rght' => '8',
			'type' => '1',
			'plugin_key' => null,
			'default_setting_action' => null,
			'room_id_root' => '2',
			'page_id_top' => '1',
			'permalink' => '',
			'is_m17n' => false,
		),
		array(
			'id' => '2',
			'parent_id' => '1',
			'lft' => '2',
			'rght' => '3',
			'type' => '2',
			'plugin_key' => 'public_space',
			'default_setting_action' => 'rooms/index/2',
			'room_id_root' => '2',
			'page_id_top' => '1',
			'permalink' => '',
			'is_m17n' => true,
		),
		array(
			'id' => '3',
			'parent_id' => '1',
			'lft' => '4',
			'rght' => '5',
			'type' => '3',
			'plugin_key' => 'private_space',
			'default_setting_action' => '',
			'room_id_root' => '3',
			'page_id_top' => '2',
			'permalink' => 'private',
			'is_m17n' => false
		),
		array(
			'id' => '4',
			'parent_id' => '1',
			'lft' => '6',
			'rght' => '7',
			'type' => '4',
			'plugin_key' => 'community_space',
			'default_setting_action' => 'rooms/index/4',
			'room_id_root' => '4',
			'page_id_top' => '3',
			'permalink' => 'community',
			'is_m17n' => false
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Rooms') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new RoomsSchema())->tables['spaces'];
		parent::init();
	}

}
