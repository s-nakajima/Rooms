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

App::uses('FrameFixture', 'Frames.Test/Fixture');

/**
 * 削除用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Rooms\Test\Fixture
 */
class Frame4deleteFixture extends FrameFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Frame';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'frames';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//削除されないデータ
		array(
			'id' => '2',
			'room_id' => '2',
			'box_id' => '3',
			'plugin_key' => 'test',
			'block_id' => '1',
			'key' => 'frame_1',
			'weight' => '1',
			'is_deleted' => '0',
		),
		//削除対象のデータ
		array(
			'id' => '4',
			'room_id' => '5',
			'box_id' => '3',
			'plugin_key' => 'test',
			'block_id' => '4',
			'key' => 'delete_frame_1',
			'weight' => '1',
			'is_deleted' => '0',
		),
		array(
			'id' => '6',
			'room_id' => '5',
			'box_id' => '3',
			'plugin_key' => 'test',
			'block_id' => '6',
			'key' => 'delete_frame_2',
			'weight' => '1',
			'is_deleted' => '0',
		),
	);

}
