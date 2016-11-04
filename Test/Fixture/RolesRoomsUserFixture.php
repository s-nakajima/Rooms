<?php
/**
 * RolesRoomsUserFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * RolesRoomsUserFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Fixture
 */
class RolesRoomsUserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'roles_room_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'room_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'access_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'last_accessed' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'previous_accessed' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		// * room_id=2、ユーザID=1
		array(
			'id' => '1',
			'roles_room_id' => '1',
			'user_id' => '1',
			'room_id' => '2',
			'access_count' => '0',
			'last_accessed' => null,
			'previous_accessed' => null,
		),
		// * room_id=2、ユーザID=2
		array(
			'id' => '2',
			'roles_room_id' => '2',
			'user_id' => '2',
			'room_id' => '2',
			'access_count' => '0',
			'last_accessed' => null,
			'previous_accessed' => null,
		),
		// * room_id=2、ユーザID=3
		array(
			'id' => '3',
			'roles_room_id' => '3',
			'user_id' => '3',
			'room_id' => '2',
			'access_count' => '0',
			'last_accessed' => null,
			'previous_accessed' => null,
		),
		// * room_id=2、ユーザID=4
		array(
			'id' => '4',
			'roles_room_id' => '4',
			'user_id' => '4',
			'room_id' => '2',
			'access_count' => '0',
			'last_accessed' => null,
			'previous_accessed' => null,
		),
		// * room_id=2、ユーザID=5
		array(
			'id' => '5',
			'roles_room_id' => '5',
			'user_id' => '5',
			'room_id' => '2',
			'access_count' => '0',
			'last_accessed' => null,
			'previous_accessed' => null,
		),
		// * 別ルーム(room_id=5)
		array(
			'id' => '6',
			'roles_room_id' => '6',
			'user_id' => '1',
			'room_id' => '5',
			'access_count' => '0',
			'last_accessed' => null,
			'previous_accessed' => null,
		),
		// * 別ルーム(room_id=6、ブロックなし)
		array(
			'id' => '7',
			'roles_room_id' => '7',
			'user_id' => '1',
			'room_id' => '6',
			'access_count' => '0',
			'last_accessed' => null,
			'previous_accessed' => null,
		),

		//サイト全体
		// * room_id=1、ユーザID=1
		array(
			'id' => '11',
			'roles_room_id' => '15',
			'user_id' => '1',
			'room_id' => '1',
		),
	);

}
