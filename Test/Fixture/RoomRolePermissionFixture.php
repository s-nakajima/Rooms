<?php
/**
 * RoomRolePermissionFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * RoomRolePermissionFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Fixture
 */
class RoomRolePermissionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'roles_room_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'comment' => 'Role type
e.g.) roomRole, announcementBlockRole, bbsBlockRole
'),
		'permission' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Permission name
e.g.) createPage, editOtherContent, publishContent', 'charset' => 'utf8'),
		'value' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null),
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
		array(
			'id' => '1',
			'roles_room_id' => '1',
			'permission' => 'page_editable',
			'value' => '1',
		),
		array(
			'id' => '2',
			'roles_room_id' => '1',
			'permission' => 'block_editable',
			'value' => '1',
		),
		array(
			'id' => '3',
			'roles_room_id' => '1',
			'permission' => 'content_readable',
			'value' => '1',
		),
		array(
			'id' => '4',
			'roles_room_id' => '1',
			'permission' => 'content_creatable',
			'value' => '1',
		),
		array(
			'id' => '5',
			'roles_room_id' => '1',
			'permission' => 'content_editable',
			'value' => '1',
		),
		array(
			'id' => '6',
			'roles_room_id' => '1',
			'permission' => 'content_publishable',
			'value' => '1',
		)
	);

}