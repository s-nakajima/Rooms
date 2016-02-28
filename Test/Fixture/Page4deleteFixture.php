<?php
/**
 * 削除用Fixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('PageFixture', 'Blocks.Test/Fixture');

/**
 * 削除用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Fixture
 */
class Page4deleteFixture extends PageFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Page';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'pages';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//削除されないデータ
		array(
			'id' => '1',
			'room_id' => '1',
			'parent_id' => null,
			'lft' => '1',
			'rght' => '2',
			'permalink' => '',
			'slug' => null,
			'is_published' => true,
			'from' => null,
			'to' => null,
			'is_container_fluid' => true,
		),
		//削除対象のデータ
		array(
			'id' => '2',
			'room_id' => '4',
			'parent_id' => null,
			'lft' => '3',
			'rght' => '4',
			'permalink' => 'delete_page_1',
			'slug' => 'delete_page_1',
			'is_published' => true,
			'from' => null,
			'to' => null,
			'is_container_fluid' => true,
		),
		array(
			'id' => '3',
			'room_id' => '4',
			'parent_id' => null,
			'lft' => '5',
			'rght' => '6',
			'permalink' => 'delete_page_2',
			'slug' => 'delete_page_2',
			'is_published' => true,
			'from' => null,
			'to' => null,
			'is_container_fluid' => true,
		),
	);

}
